<script>
import CategoriaService from "./../../services/categorias";
import { useToast } from "vue-toastification";
import FormularioCategoria from "./components/FormularioCategoria.vue";

export default {
    name: "CategoriasVista",
    components: {
        FormularioCategoria,
    },
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            categorias: [],
            cargandoCategorias: false,
            datosItem: this.crearDatosItem(),
            mostradoDialogoFormulario: false,
            categoriaSeleccionada: null,
            mensajeDialogoConfirmacion: null,
            mostradoDialogoConfirmacion: false,
            funcionDialogoConfirmacion: () => {},
            realizandoAccion: false,
            busqueda: null,
            headers: [
                { title: "#", key: "nro", sortable: false, filterable: false },
                { title: "Nombre", key: "nombre" },
                { title: "Categoría Padre", key: "categoria_padre.nombre" },
                {
                    title: "Nro. de categorias hijas",
                    key: "categorias_hijas.length",
                    filterable: false,
                },
                {
                    title: "Acciones",
                    key: "acciones",
                    sortable: false,
                    filterable: false,
                },
            ],
            itemsPorPaginaOpciones: [
                { value: 10, title: "10" },
                { value: 15, title: "15" },
                { value: 30, title: "30" },
                { value: 50, title: "50" },
                { value: -1, title: "Todos" },
            ],
            itemsPorPagina:
                Number(
                    localStorage.getItem(`itemsPorPagina-${this.$route.name}`),
                ) || 10,
            paginaActual: 1,
        };
    },
    computed: {
        tituloDialogoFormulario() {
            return this.datosItem.id
                ? "Editar Categoría"
                : "Registrar Categoría";
        },
    },
    created() {
        this.obtenerCategorias();
    },
    methods: {
        async obtenerCategorias() {
            this.cargandoCategorias = true;

            try {
                const { data } = await CategoriaService.index({
                    params: { orden_direccion: "desc", con_eliminados: true },
                });

                this.categorias = data.datos.map((categoria) => {
                    const { categorias_hijas_inmediatas, ...datosCategoria } =
                        categoria;

                    return {
                        ...datosCategoria,
                        categorias_hijas: categorias_hijas_inmediatas,
                    };
                });
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoCategorias = false;
            }
        },
        async accionCategoria(accion) {
            if (!["eliminar", "desactivar", "activar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "eliminar") {
                    await CategoriaService.destroy(
                        this.categoriaSeleccionada.id,
                    );
                } else if (accion === "desactivar") {
                    await CategoriaService.softDestroy(
                        this.categoriaSeleccionada.id,
                    );
                } else if (accion === "activar") {
                    await CategoriaService.restore(
                        this.categoriaSeleccionada.id,
                    );
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerCategorias();
                this.cancelarAccion();
            } catch (error) {
                console.log(error);
            } finally {
                this.realizandoAccion = false;
            }
        },
        crearDatosItem() {
            return {
                id: null,
                nombre: null,
                categoria_padre_id: null,
            };
        },
        mostrarDialogoFormulario(item) {
            this.datosItem = item
                ? {
                      ...item,
                  }
                : this.crearDatosItem();
            this.mostradoDialogoFormulario = true;
        },
        cancelarGuardado() {
            this.mostradoDialogoFormulario = false;
            this.datosItem = this.crearDatosItem();
        },
        mostrarDialogoConfirmacion(item, accion) {
            switch (accion) {
                case "eliminar":
                    this.mensajeDialogoConfirmacion = `
                        ¿Está seguro de eliminar la categoria
                        <strong>${item.nombre}</strong>. Esta acción no se puede deshacer.
                    `;
                    break;
                case "desactivar":
                    this.mensajeDialogoConfirmacion = `
                        ¿Está seguro de desactivar la categoria
                        <strong>${item.nombre}</strong>?.
                    `;
                    break;
                case "activar":
                    this.mensajeDialogoConfirmacion = `
                        ¿Está seguro de activar la categoria
                        <strong>${item.nombre}</strong>?.
                    `;
                    break;
                default:
                    break;
            }
            this.funcionDialogoConfirmacion = () =>
                this.accionCategoria(accion);
            this.categoriaSeleccionada = item;
            this.mostradoDialogoConfirmacion = true;
        },
        cancelarAccion() {
            this.mostradoDialogoConfirmacion = false;
            this.mensajeDialogoConfirmacion = null;
            this.funcionDialogoConfirmacion = () => {};
            this.categoriaSeleccionada = null;
        },
        mostrarNotificacionAccionRealizada(accion) {
            const accionRealizada =
                accion === "eliminar"
                    ? "eliminada"
                    : accion === "desactivar"
                      ? "desactivada"
                      : "activada";

            this.toast.success(`Categoría ${accionRealizada} exitosamente`);
        },
        actualizarItemsPorPagina(itemsPorPagina) {
            localStorage.setItem(
                `itemsPorPagina-${this.$route.name}`,
                itemsPorPagina,
            );

            this.itemsPorPagina = itemsPorPagina;
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex align-center">
                <h2 class="text-h5">Categorias</h2>

                <v-btn
                    class="ml-2"
                    color="success"
                    density="compact"
                    prepend-icon="mdi-plus"
                    title="Registrar"
                    @click="() => mostrarDialogoFormulario()"
                >
                    Registrar
                </v-btn>

                <v-btn
                    class="ml-2"
                    color="primary"
                    density="compact"
                    icon="mdi-reload"
                    :loading="cargandoCategorias"
                    :disabled="cargandoCategorias"
                    title="Recargar"
                    @click="obtenerCategorias"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="categorias"
                :search="busqueda"
                item-key="id"
                no-data-text="No hay ítems disponibles"
                items-per-page-text="Ítems por página"
                loading-text="Cargando ítems..."
                :items-per-page="itemsPorPagina"
                page-text="{0}-{1} de {2}"
                :items-per-page-options="itemsPorPaginaOpciones"
                density="compact"
                :loading="cargandoCategorias"
                @update:items-per-page="actualizarItemsPorPagina"
                @update:page="paginaActual = $event"
            >
                <template #top>
                    <v-text-field
                        v-model="busqueda"
                        class="mb-2"
                        density="compact"
                        label="Buscar"
                        name="buscar"
                        type="text"
                        prepend-icon="mdi-magnify"
                        clearable
                    />
                </template>

                <template #[`item.nro`]="{ index }">
                    {{ index + 1 + itemsPorPagina * (paginaActual - 1) }}
                </template>

                <template #[`item.acciones`]="{ item }">
                    <v-btn
                        color="primary"
                        density="compact"
                        icon="mdi-pencil"
                        title="Editar"
                        @click="mostrarDialogoFormulario(item)"
                    />

                    <v-btn
                        class="ml-2"
                        color="error"
                        density="compact"
                        icon="mdi-trash-can"
                        title="Eliminar"
                        :disabled="item.categorias_hijas.length > 0"
                        @click="mostrarDialogoConfirmacion(item, 'eliminar')"
                    />

                    <v-btn
                        v-if="!item.eliminado_en"
                        class="ml-2"
                        color="error"
                        density="compact"
                        icon="mdi-cancel"
                        title="Desactivar"
                        @click="mostrarDialogoConfirmacion(item, 'desactivar')"
                    />

                    <v-btn
                        v-else
                        class="ml-2"
                        color="success"
                        density="compact"
                        icon="mdi-check"
                        title="Activar"
                        @click="mostrarDialogoConfirmacion(item, 'activar')"
                    />
                </template>
            </v-data-table>
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="440">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioCategoria
                        :datos="datosItem"
                        @actualizar-listado="obtenerCategorias"
                        @cancelar-guardado="cancelarGuardado"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="mostradoDialogoConfirmacion" persistent width="440">
            <v-card>
                <v-card-title>
                    <span class="text-h6">Confirmación de acción</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <p v-html="mensajeDialogoConfirmacion" />
                </v-card-text>

                <v-card-actions>
                    <v-btn
                        color="primary"
                        density="compact"
                        prepend-icon="mdi-check"
                        title="Aceptar"
                        :loading="realizandoAccion"
                        :disabled="realizandoAccion"
                        @click="funcionDialogoConfirmacion"
                    >
                        Aceptar
                    </v-btn>

                    <v-btn
                        class="ml-2"
                        color="blue-grey"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cancelar"
                        :disabled="realizandoAccion"
                        @click="cancelarAccion"
                    >
                        Cancelar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
