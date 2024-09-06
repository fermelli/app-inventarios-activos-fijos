<script>
import SalidaArticuloService from "./../../services/salidas-articulos";
import { useToast } from "vue-toastification";
import vistaMixin from "../../mixins/vista.mixin";
import solicitudesSalidasVistaMixin from "./mixins/solicitudes-salidas-vista.mixin";
import reportePdfMixin from "../../mixins/reporte-pdf.mixin";

export default {
    name: "SalidasVista",
    mixins: [vistaMixin, solicitudesSalidasVistaMixin, reportePdfMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Salida Artículos",
            metodoServicioObtenerItem: SalidaArticuloService.show,
            metodoServicioObtenerReportePdf:
                SalidaArticuloService.showReportePdf,
        };
    },
    computed: {
        titulo() {
            return this.tipo === "usuario"
                ? "Mis Salidas de Artículos"
                : "Salidas de Artículos";
        },
        botonEntregarOAnularDeshabilitado() {
            return (
                this.itemSeleccionado?.detalles_transacciones.length == 0 ||
                this.itemSeleccionado?.detalles_transacciones.some(
                    (detalle) => Number(detalle.cantidad) <= 0,
                ) ||
                this.itemSeleccionado?.estado_solicitud !==
                    this.estadosSolicitudes.aprobada ||
                this.realizandoAccion
            );
        },
    },
    methods: {
        async obtenerSalidasArticulos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda = payload?.search;

            try {
                const servicio =
                    this.tipo === "usuario"
                        ? SalidaArticuloService.indexUsuario
                        : SalidaArticuloService.index;
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
        confirmarAtencion(accion) {
            if (!["entregar", "anular"].includes(accion)) {
                return;
            }

            this.manejarDialogoConfirmacionAccion(accion);
        },
        async atenderSalidaArticulos(accion) {
            if (!["entregar", "anular"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "entregar") {
                    await SalidaArticuloService.entregar(
                        this.itemSeleccionado.id,
                    );
                } else if (accion === "anular") {
                    await SalidaArticuloService.anular(
                        this.itemSeleccionado.id,
                    );
                }

                this.toast.success(
                    "Salida de Artículos entregada exitosamente",
                );
                this.cancelarAccion();
                this.obtenerSalidasArticulos();
                this.cerrarDialogoMostrarItem();
            } catch (error) {
                console.log(error);

                const mensaje =
                    error.response?.data?.mensaje ||
                    "Ocurrió un error al entregar la salida de artículos";

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
            <div class="d-flex flex-wrap align-center">
                <h2 class="text-h5">{{ titulo }}</h2>

                <v-btn
                    class="ml-2"
                    color="primary"
                    density="compact"
                    icon="mdi-reload"
                    :loading="cargandoItems"
                    :disabled="cargandoItems"
                    title="Recargar"
                    @click="obtenerSalidasArticulos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorSolicitudes
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerSalidasArticulos"
                @mostrar-item="mostrarItem"
            />
        </v-col>

        <v-dialog
            v-model="mostradoDialogoMostrarItem"
            width="1200"
            persistent
            scrollable
        >
            <v-card>
                <v-card-title>
                    <div
                        class="d-flex flex-wrap justify-space-between align-center"
                    >
                        <span class="text-h6">{{ nombreItem }}</span>

                        <v-btn
                            class="my-1"
                            color="blue-grey"
                            density="compact"
                            variant="text"
                            icon="mdi-close"
                            title="Cerrar"
                            @click="cerrarDialogoMostrarItem"
                        />
                    </div>
                </v-card-title>

                <v-card-text class="pa-4">
                    <v-row dense>
                        <v-col
                            v-for="(dato, indice) in listadoDatos"
                            :key="indice"
                            class="py-0"
                            cols="12"
                            lg="3"
                        >
                            <v-list lines="two" class="py-0" density="compact">
                                <v-list-item
                                    class="py-0"
                                    density="compact"
                                    :title="dato.titulo"
                                    :subtitle="dato.subtitulo"
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12" class="py-0">
                            <v-list
                                lines="three"
                                class="py-0"
                                density="compact"
                            >
                                <v-list-item
                                    class="py-0"
                                    density="compact"
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
                    <div
                        class="d-flex flex-wrap justify-space-between align-center"
                    >
                        <v-btn
                            v-if="mostradoBotonAccion"
                            class="ma-1"
                            color="success"
                            density="compact"
                            prepend-icon="mdi-check"
                            title="Entregar Salida de Artículos"
                            :disabled="botonEntregarOAnularDeshabilitado"
                            @click="() => confirmarAtencion('entregar')"
                        >
                            Entregar
                        </v-btn>

                        <v-btn
                            v-if="mostradoBotonAccion"
                            class="ma-1"
                            color="error"
                            density="compact"
                            prepend-icon="mdi-close"
                            title="Anular Salida de Artículos"
                            :disabled="botonEntregarOAnularDeshabilitado"
                            @click="() => confirmarAtencion('anular')"
                        >
                            Anular
                        </v-btn>

                        <v-btn
                            class="ma-1"
                            color="primary"
                            density="compact"
                            prepend-icon="mdi-printer"
                            title="Reporte PDF"
                            @click="mostrarReportePdf"
                        >
                            Reporte PDF
                        </v-btn>

                        <v-btn
                            class="ma-1"
                            color="secondary"
                            density="compact"
                            prepend-icon="mdi-close"
                            title="Cerrar"
                            @click="cerrarDialogoMostrarItem"
                        >
                            Cerrar
                        </v-btn>
                    </div>
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
