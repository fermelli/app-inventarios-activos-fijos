<script>
import UbicacionService from "./../../services/ubicaciones";
import { useToast } from "vue-toastification";
import FormularioUbicacion from "./components/FormularioUbicacion.vue";
import TablaDatosUbicaciones from "./components/TablaDatosUbicaciones.vue";
import vistaMixin from "../../mixins/vista.mixin";

export default {
    name: "CategoriasVista",
    components: {
        FormularioUbicacion,
        TablaDatosUbicaciones,
    },
    mixins: [vistaMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Ubicación",
        };
    },
    created() {
        this.obtenerUbicaciones();
    },
    methods: {
        async obtenerUbicaciones() {
            this.cargandoItems = true;

            try {
                const { data } = await UbicacionService.index({
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
            if (!["eliminar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "eliminar") {
                    await UbicacionService.destroy(this.itemSeleccionado.id);
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerUbicaciones();
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
            };
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex align-center">
                <h2 class="text-h5">Ubicaciones</h2>

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
                    @click="obtenerUbicaciones"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosUbicaciones
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
                    <FormularioUbicacion
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerUbicaciones"
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
