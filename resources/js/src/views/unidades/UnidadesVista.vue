<script>
import UnidadService from "./../../services/unidades";
import { useToast } from "vue-toastification";
import FormularioUnidad from "./components/FormularioUnidad.vue";
import DialogoConfirmacion from "../../components/DialogoConfirmacion.vue";
import TablaDatosUnidades from "./components/TablaDatosUnidades.vue";

export default {
    name: "UnidadesVista",
    components: {
        FormularioUnidad,
        DialogoConfirmacion,
        TablaDatosUnidades,
    },
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            unidades: [],
            cargandoUnidades: false,
            datosItem: this.crearDatosItem(),
            mostradoDialogoFormulario: false,
            unidadSeleccionada: null,
            mensajeDialogoConfirmacion: "",
            mostradoDialogoConfirmacion: false,
            funcionDialogoConfirmacion: () => {},
            realizandoAccion: false,
        };
    },
    computed: {
        tituloDialogoFormulario() {
            return this.datosItem.id ? "Editar Unidad" : "Registrar Unidad";
        },
    },
    created() {
        this.obtenerUnidades();
    },
    methods: {
        async obtenerUnidades() {
            this.cargandoUnidades = true;

            try {
                const { data } = await UnidadService.index({
                    params: { orden_direccion: "desc" },
                });

                this.unidades = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoUnidades = false;
            }
        },
        async accionUnidad(accion) {
            if (!["eliminar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "eliminar") {
                    await UnidadService.destroy(this.unidadSeleccionada.id);
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
                        ¿Está seguro de eliminar la unidad
                        <strong>${item.nombre}</strong>. Esta acción no se puede deshacer.
                    `;
                    break;
                case "desactivar":
                    this.mensajeDialogoConfirmacion = `
                        ¿Está seguro de desactivar la unidad
                        <strong>${item.nombre}</strong>?.
                    `;
                    break;
                case "activar":
                    this.mensajeDialogoConfirmacion = `
                        ¿Está seguro de activar la unidad
                        <strong>${item.nombre}</strong>?.
                    `;
                    break;
                default:
                    break;
            }
            this.funcionDialogoConfirmacion = () => this.accionUnidad(accion);
            this.unidadSeleccionada = item;
            this.mostradoDialogoConfirmacion = true;
        },
        cancelarAccion() {
            this.mostradoDialogoConfirmacion = false;
            this.mensajeDialogoConfirmacion = null;
            this.funcionDialogoConfirmacion = () => {};
            this.unidadSeleccionada = null;
        },
        mostrarNotificacionAccionRealizada(accion) {
            const accionRealizada =
                accion === "eliminar"
                    ? "eliminada"
                    : accion === "desactivar"
                      ? "desactivada"
                      : "activada";

            this.toast.success(`Unidad ${accionRealizada} exitosamente`);
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex align-center">
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
                    :loading="cargandoUnidades"
                    :disabled="cargandoUnidades"
                    title="Recargar"
                    @click="obtenerUnidades"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosUnidades
                :unidades="unidades"
                :cargando-unidades="cargandoUnidades"
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
                    <FormularioUnidad
                        :datos="datosItem"
                        @actualizar-listado="obtenerUnidades"
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
