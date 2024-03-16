<script>
import CategoriaService from "./../../services/categorias";
import { useToast } from "vue-toastification";
import FormularioCategoria from "./components/FormularioCategoria.vue";
import TablaDatosCategorias from "./components/TablaDatosCategorias.vue";
import vistaMixin from "../../mixins/vista.mixin";

export default {
    name: "CategoriasVista",
    components: {
        FormularioCategoria,
        TablaDatosCategorias,
    },
    mixins: [vistaMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Categoría",
        };
    },
    created() {
        this.obtenerCategorias();
    },
    methods: {
        async obtenerCategorias() {
            this.cargandoItems = true;

            try {
                const { data } = await CategoriaService.index({
                    params: { orden_direccion: "desc", con_eliminados: true },
                });

                this.items = data.datos.map((categoria) => {
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
                this.cargandoItems = false;
            }
        },
        async accionItem(accion) {
            if (!["eliminar", "desactivar", "activar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "eliminar") {
                    await CategoriaService.destroy(this.itemSeleccionado.id);
                } else if (accion === "desactivar") {
                    await CategoriaService.softDestroy(
                        this.itemSeleccionado.id,
                    );
                } else if (accion === "activar") {
                    await CategoriaService.restore(this.itemSeleccionado.id);
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
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex flex-wrap align-center">
                <h2 class="text-h5">Categorías</h2>

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
                    :loading="cargandoItems"
                    :disabled="cargandoItems"
                    title="Recargar"
                    @click="obtenerCategorias"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosCategorias
                :items="items"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
            />
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="440">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioCategoria
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerCategorias"
                        @cancelar-guardado="cancelarGuardado"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <DialogoConfirmacion
            v-model="mostradoDialogoConfirmacion"
            :mensaje="mensajeDialogoConfirmacion"
            :realizando-accion="realizandoAccion"
            @aceptar="funcionDialogoConfirmacion"
            @cancelar="cancelarAccion"
        />
    </v-row>
</template>
