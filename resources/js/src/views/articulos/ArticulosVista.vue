<script>
import ArticuloService from "./../../services/articulos";
import { useToast } from "vue-toastification";
import FormularioArticulo from "./components/FormularioArticulo.vue";
import TablaDatosServidorArticulos from "./components/TablaDatosServidorArticulos.vue";
import vistaMixin from "../../mixins/vista.mixin";
import articulosMixin from "../../mixins/articulos.mixin";
import dialogoFormularioImportarMixin from "../../mixins/dialogo-formulario-importar.mixin";
import reportePdfMixin from "../../mixins/reporte-pdf.mixin";

export default {
    name: "ArticulosVista",
    components: {
        FormularioArticulo,
        TablaDatosServidorArticulos,
    },
    mixins: [
        vistaMixin,
        articulosMixin,
        dialogoFormularioImportarMixin,
        reportePdfMixin,
    ],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Artículo",
            pagina: 1,
            itemsPorPagina: parseInt(
                localStorage.getItem(`itemsPorPagina-${this.$route.name}`) ||
                    10,
            ),
            totalItems: 0,
            busqueda: null,
            metodoImportar: ArticuloService.importar,
            metodoFormatoImportacion: ArticuloService.formatoImportacion,
            tituloArchivoEjemploImportacion:
                "Formato de Importación de Artículos",
            exportandoArticulos: false,
            metodoServicioObtenerReportePdf: ArticuloService.showReportePdf,
        };
    },
    methods: {
        async accionItem(accion) {
            if (!["eliminar", "desactivar", "activar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "eliminar") {
                    await ArticuloService.destroy(this.itemSeleccionado.id);
                } else if (accion === "desactivar") {
                    await ArticuloService.softDestroy(this.itemSeleccionado.id);
                } else if (accion === "activar") {
                    await ArticuloService.restore(this.itemSeleccionado.id);
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerArticulos();
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
                categoria_id: null,
                unidad_id: null,
                ubicacion_id: null,
                codigo: null,
                nombre: null,
                cantidad: null,
                articulos_lotes: [],
            };
        },
        async exportarArticulosExcel() {
            this.exportandoArticulos = true;
            const params = {
                orden_direccion: "desc",
                con_eliminados: true,
                pagina: this.pagina,
                items_por_pagina: this.itemsPorPagina,
                busqueda: this.busqueda,
                categoria_id: this.categoria_id,
            };

            try {
                const { data } = await ArticuloService.exportar({
                    params,
                });
                const blob = new Blob([data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const link = document.createElement("a");

                link.href = window.URL.createObjectURL(blob);
                link.download = "Artículos.xlsx";
                link.click();
            } catch (error) {
                console.log(error);

                const mensaje =
                    error?.response?.data?.message ||
                    error.message ||
                    error ||
                    "Error al descargar el formato de ejemplo";

                this.toast.error(mensaje);
            } finally {
                this.exportandoArticulos = false;
            }
        },
        async exportarArticulosPdf() {
            const params = {
                orden_direccion: "desc",
                con_eliminados: true,
                pagina: this.pagina,
                items_por_pagina: this.itemsPorPagina,
                busqueda: this.busqueda,
                categoria_id: this.categoria_id,
            };

            this.exportandoArticulos = true;

            try {
                const { data } = await ArticuloService.showReportePdf({
                    params,
                });
                const datos = data?.datos;
                const mensaje = data?.mensaje;
                const { pdf } = datos;

                this.toast.success(mensaje || "Reporte PDF generado");
                this.pdfSrc = `data:application/pdf;base64,${pdf}`;
                this.mostradoDialogoReportePdf = true;
            } catch (error) {
                console.log(error);
            } finally {
                this.exportandoArticulos = false;
            }
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex flex-wrap align-center">
                <h2 class="text-h5">Artículos</h2>

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
                    @click="obtenerArticulos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorArticulos
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                :exportando-items="exportandoArticulos"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerArticulos"
                @exportar-pdf="exportarArticulosPdf"
                @exportar-excel="exportarArticulosExcel"
            />
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="440">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioArticulo
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerArticulos"
                        @cancelar-guardado="cancelarGuardado"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioImportar"
            persistent
            width="560"
        >
            <v-card>
                <v-card-title>
                    <div class="d-flex justify-space-between align-center">
                        <span class="text-h6">Importar Artículos</span>

                        <v-btn
                            class="ml-2"
                            color="primary"
                            variant="text"
                            density="compact"
                            prepend-icon="mdi-microsoft-excel"
                            :loading="descargandoFormatoEjemplo"
                            :disabled="descargandoFormatoEjemplo"
                            title="Descargar Formato de Importación"
                            @click="descargarFormatoImportacion"
                        >
                            Formato de Ejemplo
                        </v-btn>
                    </div>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioImportar
                        :datos="datosFormularioImportar"
                        :metodo-importar="metodoImportar"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerArticulos"
                        @cancelar-guardado="cancelarGuardadoImportar"
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

        <DialogoReportePdf
            v-model="mostradoDialogoReportePdf"
            :pdf-src="pdfSrc"
            @cerrar="mostradoDialogoReportePdf = false"
        />
    </v-row>
</template>
