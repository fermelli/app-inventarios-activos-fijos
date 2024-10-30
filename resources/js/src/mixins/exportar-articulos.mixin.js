import reportePdfMixin from "./reporte-pdf.mixin";

export default {
    mixins: [reportePdfMixin],
    data() {
        return {
            exportandoArticulos: false,
            metodoServicioObtenerExportarExcel: async () =>
                new Promise((resolve, reject) => {
                    reject(
                        "No se ha definido el servicio para exportar los artículos",
                    );
                }),
            metodoServicioObtenerReportePdf: async () =>
                new Promise((resolve, reject) => {
                    reject(
                        "No se ha definido el servicio para obtener el reporte PDF",
                    );
                }),
            metodoServicioGenerarEtiquetaPdf: async () =>
                new Promise((resolve, reject) => {
                    reject(
                        "No se ha definido el servicio para generar la etiqueta del artículo",
                    );
                }),
            metodoServicioGenerarEtiquetasPdf: async () =>
                new Promise((resolve, reject) => {
                    reject(
                        "No se ha definido el servicio para generar las etiquetas de los artículos",
                    );
                }),
        };
    },
    methods: {
        async exportarArticulosExcel(nombreArchivo = "Archivo") {
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
                const { data } = await this.metodoServicioObtenerExportarExcel({
                    params,
                });
                const blob = new Blob([data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const link = document.createElement("a");

                link.href = window.URL.createObjectURL(blob);
                link.download = `${nombreArchivo}.xlsx`;
                link.click();
            } catch (error) {
                console.log(error);

                const mensaje =
                    error?.response?.data?.message ||
                    error.message ||
                    error ||
                    `Error al exportar los ${nombreArchivo}`;

                this.toast.error(mensaje);
            } finally {
                this.exportandoArticulos = false;
            }
        },
        async exportarArticulosPdf(sinPaginacion = false) {
            const params = {
                orden_direccion: "desc",
                con_eliminados: true,
                pagina: this.pagina,
                items_por_pagina: this.itemsPorPagina,
                busqueda: this.busqueda,
                categoria_id: this.categoria_id,
                sin_paginacion: sinPaginacion,
            };

            this.exportandoArticulos = true;

            try {
                const { data } = await this.metodoServicioObtenerReportePdf({
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
        async exportarArticulosPdfSinPaginacion() {
            await this.exportarArticulosPdf(true);
        },
        async generarEtiquetaArticuloPdf(articuloId, params = {}) {
            this.exportandoArticulos = true;

            try {
                const { data } = await this.metodoServicioGenerarEtiquetaPdf(
                    articuloId,
                    {
                        params,
                    },
                );
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
        async generarEtiquetasArticulosPdf(params = {}) {
            this.exportandoArticulos = true;

            try {
                const { data } = await this.metodoServicioGenerarEtiquetasPdf({
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
