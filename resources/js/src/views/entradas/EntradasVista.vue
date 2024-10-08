<script>
import EntradaArticuloService from "./../../services/entradas-articulos";
import { useToast } from "vue-toastification";
import FormularioEntrada from "./components/FormularioEntrada.vue";
import TablaDatosServidorEntradas from "./components/TablaDatosServidorEntradas.vue";
import vistaMixin from "../../mixins/vista.mixin";
import TablaDatosDetallesEntradas from "./components/TablaDatosDetallesEntradas.vue";
import { ESTADOS_ENTRADAS, ROLES } from "../../utils/constantes";
import reportePdfMixin from "../../mixins/reporte-pdf.mixin";
import dialogoFormularioImportarMixin from "../../mixins/dialogo-formulario-importar.mixin";

export default {
    name: "EntradasVista",
    components: {
        FormularioEntrada,
        TablaDatosServidorEntradas,
        TablaDatosDetallesEntradas,
    },
    mixins: [vistaMixin, reportePdfMixin, dialogoFormularioImportarMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Entrada Artículo",
            pagina: 1,
            itemsPorPagina: parseInt(
                localStorage.getItem(`itemsPorPagina-${this.$route.name}`) ||
                    10,
            ),
            busqueda: null,
            totalItems: 0,
            mostradoDialogoMostrarItem: false,
            roles: ROLES,
            estadosEntradas: ESTADOS_ENTRADAS,
            metodoServicioObtenerReportePdf:
                EntradaArticuloService.showReportePdf,
            metodoImportar: EntradaArticuloService.importar,
            metodoFormatoImportacion: EntradaArticuloService.formatoImportacion,
            tituloArchivoEjemploImportacion:
                "Formato de Importación de Entradas de Artículos",
        };
    },
    computed: {
        listadoDatos() {
            if (!this.itemSeleccionado) {
                return [];
            }

            const listado = [
                {
                    titulo: "Fecha",
                    subtitulo: this.itemSeleccionado.fecha,
                },
                {
                    titulo: "Comprobante",
                    subtitulo: this.itemSeleccionado.comprobante,
                },
                {
                    titulo: "Número Comprobante",
                    subtitulo: this.itemSeleccionado.numero_comprobante,
                },
                {
                    titulo: "Estado",
                    subtitulo: this.itemSeleccionado.estado_entrada,
                },
                {
                    titulo: "Institución",
                    subtitulo: this.itemSeleccionado.institucion?.nombre,
                },
                {
                    titulo: "Registrado por",
                    subtitulo: this.itemSeleccionado.usuario?.nombre,
                },
                {
                    titulo: "N° de Artículos",
                    subtitulo:
                        this.itemSeleccionado.detalles_transacciones.length,
                },
                {
                    titulo: "Creado en",
                    subtitulo: this.itemSeleccionado.creado_en,
                },
            ];

            if (
                this.itemSeleccionado.estado_entrada ===
                this.estadosEntradas.anulada
            ) {
                listado.splice(
                    listado.length - 1,
                    0,
                    {
                        titulo: "Anulada por",
                        subtitulo: this.itemSeleccionado.anulador?.nombre,
                    },
                    {
                        titulo: "Fecha Hora Anulación",
                        subtitulo: this.itemSeleccionado.fecha_hora_anulacion,
                    },
                );
            }

            return listado;
        },
        botonAnularDeshabilitado() {
            return (
                this.itemSeleccionado?.detalles_transacciones.length == 0 ||
                this.itemSeleccionado?.detalles_transacciones.some(
                    (detalle) =>
                        Number(detalle.cantidad) <= 0 ||
                        Number(detalle.cantidad) >
                            Number(detalle.articulo_lote.cantidad),
                ) ||
                this.itemSeleccionado?.estado_entrada !==
                    this.estadosEntradas.valida ||
                this.realizandoAccion
            );
        },
    },
    methods: {
        async obtenerEntradasArticulos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda = payload?.search;

            try {
                const { data } = await EntradaArticuloService.index({
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
                    await EntradaArticuloService.softDestroy(
                        this.itemSeleccionado.id,
                    );
                } else if (accion === "activar") {
                    await EntradaArticuloService.restore(
                        this.itemSeleccionado.id,
                    );
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerEntradasArticulos();
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
                institucion_id: null,
                institucion: null,
                fecha: null,
                comprobante: null,
                numero_comprobante: null,
                estado_entrada: null,
                usuario_id: null,
                usuario: null,
                anulador_id: null,
                anulador: null,
                fecha_hora_anulacion: null,
                observacion: null,
                detalles_transacciones: [],
            };
        },
        async mostrarItem(itemId) {
            try {
                const { data } = await EntradaArticuloService.show(itemId);

                this.itemSeleccionado = data.datos;
                this.mostradoDialogoMostrarItem = true;
            } catch (error) {
                console.log(error);
            }
        },
        cerrarDialogoMostrarItem() {
            this.mostradoDialogoMostrarItem = false;
            this.itemSeleccionado = this.crearDatosItem();
        },
        confirmarAtencion(accion) {
            if (!["anular"].includes(accion)) {
                return;
            }

            this.mensajeDialogoConfirmacion = /*html */ `¿Está seguro que desea <strong>${accion.toUpperCase()}</strong> la entrada de artículos con N° de ${this.itemSeleccionado.comprobante.toUpperCase()} <strong>${this.itemSeleccionado.numero_comprobante}</strong> registrado por <strong>${this.itemSeleccionado.usuario?.nombre}</strong>?`;
            this.funcionDialogoConfirmacion = () =>
                this.anularEntradaArticulos();
            this.mostradoDialogoConfirmacion = true;
        },
        async anularEntradaArticulos() {
            this.realizandoAccion = true;

            try {
                await EntradaArticuloService.anular(this.itemSeleccionado.id);

                this.toast.success("Entrada de Artículos anulada exitosamente");
                this.cancelarAccion();
                this.obtenerEntradasArticulos();
                this.cerrarDialogoMostrarItem();
            } catch (error) {
                console.log(error);

                const mensaje =
                    error.response?.data?.mensaje ||
                    "Ocurrió un error al anular la entrada de artículos";

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
                <h2 class="text-h5">Entrada Artículos</h2>

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
                    prepend-icon="mdi-file-import-outline"
                    title="Importar"
                    @click="() => (mostradoDialogoFormularioImportar = true)"
                >
                    Importar
                </v-btn>

                <v-btn
                    class="ml-2"
                    color="primary"
                    density="compact"
                    icon="mdi-reload"
                    :loading="cargandoItems"
                    :disabled="cargandoItems"
                    title="Recargar"
                    @click="obtenerEntradasArticulos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorEntradas
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerEntradasArticulos"
                @mostrar-item="mostrarItem"
            />
        </v-col>

        <v-dialog
            v-model="mostradoDialogoFormulario"
            width="1200"
            persistent
            scrollable
        >
            <FormularioEntrada
                :datos="datosItem"
                :nombre-item="nombreItem"
                @actualizar-listado="obtenerEntradasArticulos"
                @cancelar-guardado="cancelarGuardado"
            />
        </v-dialog>

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
                            @click="mostradoDialogoMostrarItem = false"
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
                                    :subtitle="
                                        itemSeleccionado?.observacion || '-'
                                    "
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12">
                            <TablaDatosDetallesEntradas
                                :detalles-transacciones="
                                    itemSeleccionado.detalles_transacciones
                                "
                                :editable="false"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <div
                        class="d-flex flex-wrap justify-space-between align-center"
                    >
                        <v-btn
                            class="ma-1"
                            color="error"
                            density="compact"
                            prepend-icon="mdi-close"
                            title="Anular Entrada de Artículos"
                            :disabled="botonAnularDeshabilitado"
                            @click="() => confirmarAtencion('anular')"
                        >
                            Anular Entrada de Artículos
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
                            color="blue-grey"
                            density="compact"
                            prepend-icon="mdi-close"
                            title="Cerrar"
                            @click="mostradoDialogoMostrarItem = false"
                        >
                            Cerrar
                        </v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioImportar"
            width="560"
            persistent
            scrollable
        >
            <FormularioImportar
                :datos="datosFormularioImportar"
                :metodo-importar="metodoImportar"
                :metodo-formato-importacion="metodoFormatoImportacion"
                :titulo-archivo-ejemplo-importacion="
                    tituloArchivoEjemploImportacion
                "
                nombre-item="Importación de Entradas"
                @actualizar-listado="obtenerEntradasArticulos"
                @cancelar-guardado="cancelarGuardadoImportar"
            />
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
