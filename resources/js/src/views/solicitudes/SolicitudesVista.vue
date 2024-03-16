<script>
import SolicitudArticuloService from "./../../services/solicitudes-articulos";
import { useToast } from "vue-toastification";
import FormularioSolicitud from "./components/FormularioSolicitud.vue";
import vistaMixin from "../../mixins/vista.mixin";
import SalidaArticuloService from "../../services/salidas-articulos";
import solicitudesSalidasVistaMixin from "./mixins/solicitudes-salidas-vista.mixin";
import reportePdfMixin from "../../mixins/reporte-pdf.mixin";

export default {
    name: "SolicitudesVista",
    components: {
        FormularioSolicitud,
    },
    mixins: [vistaMixin, solicitudesSalidasVistaMixin, reportePdfMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Solicitud Artículos",
            metodoServicioObtenerItem: SolicitudArticuloService.show,
            metodoServicioObtenerReportePdf:
                SolicitudArticuloService.showReportePdf,
        };
    },
    computed: {
        titulo() {
            return this.tipo === "usuario"
                ? "Mis Solicitudes de Artículos"
                : "Solicitudes de Artículos";
        },
        botonAprobarSalidaDeshabilitado() {
            return (
                this.itemSeleccionado?.detalles_transacciones.length == 0 ||
                this.itemSeleccionado?.detalles_transacciones.some(
                    (detalle) =>
                        Number(detalle.cantidad) >
                        Number(detalle.articulo.cantidad),
                ) ||
                this.itemSeleccionado?.estado_solicitud !==
                    this.estadosSolicitudes.pendiente ||
                this.realizandoAccion
            );
        },
        botonRechazarSalidaDeshabilitado() {
            return (
                this.itemSeleccionado?.estado_solicitud !==
                    this.estadosSolicitudes.pendiente || this.realizandoAccion
            );
        },
    },
    methods: {
        async obtenerSolicitudesArticulos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda = payload?.search;

            try {
                const servicio =
                    this.tipo === "usuario"
                        ? SolicitudArticuloService.indexUsuario
                        : SolicitudArticuloService.index;
                const { data } = await servicio({
                    params: {
                        orden_direccion: "desc",
                        con_eliminados: true,
                        pagina: this.pagina,
                        items_por_pagina: this.itemsPorPagina,
                        busqueda: this.busqueda,
                    },
                });

                this.items = data.datos;
                this.totalItems = data.metadatos.total || 0;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoItems = false;
            }
        },
        async accionItem(accion) {
            if (!["desactivar", "activar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "desactivar") {
                    await SolicitudArticuloService.softDestroy(
                        this.itemSeleccionado.id,
                    );
                } else if (accion === "activar") {
                    await SolicitudArticuloService.restore(
                        this.itemSeleccionado.id,
                    );
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerSolicitudesArticulos();
                this.cancelarAccion();
            } catch (error) {
                console.log(error);
            } finally {
                this.realizandoAccion = false;
            }
        },
        confirmarAtencion(accion) {
            if (!["aprobar", "rechazar"].includes(accion)) {
                return;
            }

            this.manejarDialogoConfirmacionAccion(accion);
        },
        async atenderSalidaArticulos(accion) {
            if (!["aprobar", "rechazar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "aprobar") {
                    await SalidaArticuloService.aprobar(
                        this.itemSeleccionado.id,
                    );
                } else if (accion === "rechazar") {
                    await SalidaArticuloService.rechazar(
                        this.itemSeleccionado.id,
                    );
                }

                this.toast.success("Salida de Artículos atendida exitosamente");
                this.cancelarAccion();
                this.obtenerSolicitudesArticulos();
                this.cerrarDialogoMostrarItem();
            } catch (error) {
                console.log(error);

                const mensaje =
                    error.response?.data?.mensaje ||
                    "Ocurrió un error al atender la salida de artículos";

                this.toast.error(mensaje);
            } finally {
                this.realizandoAccion = false;
            }
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex align-center">
                <h2 class="text-h5">{{ titulo }}</h2>

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
                    @click="obtenerSolicitudesArticulos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorSolicitudes
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerSolicitudesArticulos"
                @mostrar-item="mostrarItem"
            />
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="1200">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioSolicitud
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        :tipo="tipo"
                        @actualizar-listado="obtenerSolicitudesArticulos"
                        @cancelar-guardado="cancelarGuardado"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="mostradoDialogoMostrarItem" width="1200">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ nombreItem }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <v-row>
                        <v-col
                            v-for="(dato, indice) in listadoDatos"
                            :key="indice"
                            class="py-0"
                            cols="12"
                            lg="3"
                        >
                            <v-list lines="two">
                                <v-list-item
                                    class="py-0"
                                    :title="dato.titulo"
                                    :subtitle="dato.subtitulo"
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12" class="py-0">
                            <v-list lines="three">
                                <v-list-item
                                    class="py-0"
                                    title="Observación"
                                    :subtitle="itemSeleccionado?.observacion"
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12">
                            <TablaDatosDetallesSolicitudes
                                :detalles-transacciones="
                                    itemSeleccionado.detalles_transacciones
                                "
                                :editable="false"
                                :mostrado-stock="mostradoStock"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-btn
                        v-if="mostradoBotonAccion"
                        color="success"
                        density="compact"
                        prepend-icon="mdi-check"
                        title="Aprobar Salida de Artículos"
                        :disabled="botonAprobarSalidaDeshabilitado"
                        @click="() => confirmarAtencion('aprobar')"
                    >
                        Aprobar Salida
                    </v-btn>

                    <v-btn
                        v-if="mostradoBotonAccion"
                        color="error"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Rechazar Salida de Artículos"
                        :disabled="botonRechazarSalidaDeshabilitado"
                        @click="() => confirmarAtencion('rechazar')"
                    >
                        Rechazar Salida
                    </v-btn>

                    <v-btn
                        class="ml-2"
                        color="primary"
                        density="compact"
                        prepend-icon="mdi-printer"
                        title="Reporte PDF"
                        @click="mostrarReportePdf"
                    >
                        Reporte PDF
                    </v-btn>

                    <v-btn
                        class="ml-2"
                        color="secondary"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cerrar"
                        @click="cerrarDialogoMostrarItem"
                    >
                        Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <DialogoConfirmacion
            v-model="mostradoDialogoConfirmacion"
            :mensaje="mensajeDialogoConfirmacion"
            :realizando-accion="realizandoAccion"
            @aceptar="funcionDialogoConfirmacion"
            @cancelar="cancelarAccion"
        />

        <DialogoReportePdf
            v-model="mostradoDialogoReportePdf"
            :pdf-src="pdfSrc"
            @cerrar="mostradoDialogoReportePdf = false"
        />
    </v-row>
</template>
