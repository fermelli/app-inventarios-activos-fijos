import ArticuloService from "../services/articulos";
import reportePdfMixin from "./reporte-pdf.mixin";

export default {
    mixins: [reportePdfMixin],
    data() {
        return {
            categoria_id: null,
            exportandoArticulos: false,
            metodoServicioObtenerReportePdf: ArticuloService.showReportePdf,
        };
    },
    methods: {
        async obtenerArticulos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda =
                !!payload && "search" in payload
                    ? payload?.search
                    : this.busqueda;
            this.categoria_id =
                !!payload && "categoria_id" in payload
                    ? payload?.categoria_id
                    : this.categoria_id;

            try {
                const { data } = await ArticuloService.index({
                    params: {
                        orden_direccion: "desc",
                        con_eliminados: true,
                        pagina: this.pagina,
                        items_por_pagina: this.itemsPorPagina,
                        busqueda: this.busqueda,
                        categoria_id: this.categoria_id,
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
