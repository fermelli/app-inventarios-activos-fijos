import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/entradas-articulos", {
            params: opciones.params,
        });
    },
    async store(datos) {
        return await service.post("/entradas-articulos", datos);
    },
    async show(id) {
        return await service.get(`/entradas-articulos/${id}`);
    },
    async softDestroy(id) {
        return await service.patch(`/entradas-articulos/${id}/desactivar`);
    },
    async restore(id) {
        return await service.patch(`/entradas-articulos/${id}/activar`);
    },
    async anular(id) {
        return await service.patch(`/entradas-articulos/${id}/anular`);
    },
    async showReportePdf(id) {
        return await service.get(`/entradas-articulos/${id}/reporte-pdf`);
    },
    async importar(formData) {
        return await service.post("/entradas-articulos/importar", formData, {
            timeout: 600000,
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },
    async formatoImportacion() {
        return await service.get("/entradas-articulos/formato-importacion", {
            responseType: "blob",
        });
    },
};
