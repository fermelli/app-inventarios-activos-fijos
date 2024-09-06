<script>
import UnidadService from "./../../services/unidades";
import { useToast } from "vue-toastification";
import FormularioUnidad from "./components/FormularioUnidad.vue";
import TablaDatosUnidades from "./components/TablaDatosUnidades.vue";
import vistaMixin from "../../mixins/vista.mixin";

export default {
    name: "UnidadesVista",
    components: {
        FormularioUnidad,
        TablaDatosUnidades,
    },
    mixins: [vistaMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Unidad",
        };
    },
    created() {
        this.obtenerUnidades();
    },
    methods: {
        async obtenerUnidades() {
            this.cargandoItems = true;

            try {
                const { data } = await UnidadService.index({
                    params: { orden_direccion: "desc" },
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
                    await UnidadService.destroy(this.itemSeleccionado.id);
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerUnidades();
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
            <div class="d-flex flex-wrap align-center">
                <h2 class="text-h5">Unidades</h2>

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
                    @click="obtenerUnidades"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosUnidades
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
            <FormularioUnidad
                :datos="datosItem"
                :nombre-item="nombreItem"
                @actualizar-listado="obtenerUnidades"
                @cancelar-guardado="cancelarGuardado"
            />
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
