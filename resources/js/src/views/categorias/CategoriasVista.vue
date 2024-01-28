<script>
import CategoriaService from "./../../services/categorias";
import { useToast } from "vue-toastification";

const aplanarCategorias = (
    categorias,
    categoriasPadresConHijasAplanadas,
    nivel = 0,
) => {
    categorias.forEach((categoria) => {
        categoriasPadresConHijasAplanadas.push({
            id: categoria.id,
            nombre: categoria.nombre,
            nombre_mostrado: `${"…".repeat(nivel)} ${categoria.nombre}`,
            categoria_padre_id: categoria.categoria_padre_id,
            eliminado_en: categoria.eliminado_en,
            nivel,
        });

        if (categoria.categorias_hijas?.length > 0) {
            aplanarCategorias(
                categoria.categorias_hijas,
                categoriasPadresConHijasAplanadas,
                nivel + 1,
            );
        }
    });
};

export default {
    name: "CategoriasVista",
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            categorias: [],
            cargandoCategorias: false,
            categoriasPadresConHijasAplanadas: [],
            cargandoCategoriasPadresConHijas: false,
            formulario: this.crearObtetoFormulario(),
            mostradoDialogoFormulario: false,
            guardandoCategoria: false,
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
            reglasValidacionNombre: [
                (valor) => !!valor || "El nombre es requerido",
                (valor) =>
                    (valor && valor.length <= 100) ||
                    "El nombre debe tener menos de 100 caracteres",
            ],
            reglasValidacionCategoriaPadreId: [
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
            ],
        };
    },
    computed: {
        tituloDialogoFormulario() {
            return this.formulario.id
                ? "Editar Categoría"
                : "Registrar Categoría";
        },
    },
    created() {
        this.recargarDatosCategorias();
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
        async obtenerCategoriasPadresConHijas() {
            this.cargandoCategoriasPadresConHijas = true;

            try {
                const { data } = await CategoriaService.indexPadresConHijas({
                    params: { orden_direccion: "asc", con_eliminados: true },
                });
                const categoriasPadresConHijas = data.datos;

                this.categoriasPadresConHijasAplanadas = [];

                aplanarCategorias(
                    categoriasPadresConHijas,
                    this.categoriasPadresConHijasAplanadas,
                );
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoCategoriasPadresConHijas = false;
            }
        },
        async recargarDatosCategorias() {
            await this.obtenerCategorias();
            await this.obtenerCategoriasPadresConHijas();
        },
        async guardarCategoria() {
            this.guardandoCategoria = true;

            try {
                if (this.formulario.id) {
                    await CategoriaService.update(
                        this.formulario.id,
                        this.formulario,
                    );
                } else {
                    await CategoriaService.store(this.formulario);
                }

                this.toast.success("Categoría guardada exitosamente");
                this.recargarDatosCategorias();
                this.cancelarGuardado();
            } catch (error) {
                console.log(error);
            } finally {
                this.guardandoCategoria = false;
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
                this.recargarDatosCategorias();
                this.cancelarAccion();
            } catch (error) {
                console.log(error);
            } finally {
                this.realizandoAccion = false;
            }
        },
        crearObtetoFormulario() {
            return {
                nombre: null,
                categoria_padre_id: null,
            };
        },
        mostrarDialogoFormulario(item) {
            this.formulario = item
                ? {
                      ...item,
                  }
                : this.crearObtetoFormulario();
            this.mostradoDialogoFormulario = true;
        },
        cancelarGuardado() {
            this.mostradoDialogoFormulario = false;
            this.formulario = this.crearObtetoFormulario();
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
                    @click="mostrarDialogoFormulario"
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
                    @click="recargarDatosCategorias"
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
                    <v-form
                        :loading="guardandoCategoria"
                        @submit.prevent="guardarCategoria"
                    >
                        <v-text-field
                            v-model="formulario.nombre"
                            class="mb-2"
                            label="Nombre"
                            name="nombre"
                            type="text"
                            density="compact"
                            :rules="reglasValidacionNombre"
                            required
                            clearable
                        />

                        <div class="d-flex align-start">
                            <v-autocomplete
                                v-model="formulario.categoria_padre_id"
                                class="mb-2"
                                :items="categoriasPadresConHijasAplanadas"
                                item-value="id"
                                item-title="nombre_mostrado"
                                label="Categoría Padre"
                                name="categoria_padre_id"
                                density="compact"
                                clear-on-select
                                clearable
                                no-data-text="No hay ítems disponibles"
                                :rules="reglasValidacionCategoriaPadreId"
                            >
                                <template #selection="{ item }">
                                    {{ item.raw.nombre }}
                                </template>

                                <template #item="{ item, props }">
                                    <v-list-item
                                        v-bind="props"
                                        :disabled="item.raw.id == formulario.id"
                                    />
                                </template>
                            </v-autocomplete>

                            <v-menu location="bottom right">
                                <template #activator="{ props }">
                                    <v-btn
                                        class="ml-2"
                                        color="primary"
                                        icon="mdi-menu-down"
                                        size="small"
                                        v-bind="props"
                                    />
                                </template>

                                <v-list density="compact">
                                    <v-list-item link>
                                        <v-list-item-title>
                                            Nuevo
                                        </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item link>
                                        <v-list-item-title>
                                            Editar
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>

                        <v-btn
                            color="primary"
                            density="compact"
                            prepend-icon="mdi-content-save"
                            title="Guardar"
                            type="submit"
                            :disabled="guardandoCategoria"
                        >
                            Guardar
                        </v-btn>

                        <v-btn
                            class="ml-2"
                            color="blue-grey"
                            density="compact"
                            prepend-icon="mdi-close"
                            title="Cancelar"
                            @click="cancelarGuardado"
                        >
                            Cancelar
                        </v-btn>
                    </v-form>
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
