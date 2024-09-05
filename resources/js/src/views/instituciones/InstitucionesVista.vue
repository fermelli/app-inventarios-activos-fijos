<script>
import InstitucionService from "./../../services/instituciones";
import { useToast } from "vue-toastification";
import FormularioInstitucion from "./components/FormularioInstitucion.vue";
import TablaDatosInstituciones from "./components/TablaDatosInstituciones.vue";
import vistaMixin from "../../mixins/vista.mixin";

export default {
    name: "InstitucionesVista",
    components: {
        FormularioInstitucion,
        TablaDatosInstituciones,
    },
    mixins: [vistaMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Institución",
        };
    },
    created() {
        this.obtenerInstituciones();
    },
    methods: {
        async obtenerInstituciones() {
            this.cargandoItems = true;

            try {
                const { data } = await InstitucionService.index({
                    params: { orden_direccion: "desc", con_eliminados: true },
                });

                this.items = data.datos;
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
                    await InstitucionService.destroy(this.itemSeleccionado.id);
                } else if (accion === "desactivar") {
                    await InstitucionService.softDestroy(
                        this.itemSeleccionado.id,
                    );
                } else if (accion === "activar") {
                    await InstitucionService.restore(this.itemSeleccionado.id);
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerInstituciones();
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
                direccion: null,
                telefono: null,
            };
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex flex-wrap align-center">
                <h2 class="text-h5">Instituciones</h2>

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
                    @click="obtenerInstituciones"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosInstituciones
                :items="items"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
            />
        </v-col>

        <v-dialog
            v-model="mostradoDialogoFormulario"
            width="440"
            persistent
            scrollable
        >
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioInstitucion
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerInstituciones"
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
