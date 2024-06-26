import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/articulos", { params: opciones.params });
    },
    async store(datos) {
        return await service.post("/articulos", datos);
    },
    async show(id) {
        return await service.get(`/articulos/${id}`);
    },
    async update(id, datos) {
        return await service.patch(`/articulos/${id}`, datos);
    },
    async destroy(id) {
        return await service.delete(`/articulos/${id}`);
    },
    async softDestroy(id) {
        return await service.patch(`/articulos/${id}/desactivar`);
    },
    async restore(id) {
        return await service.patch(`/articulos/${id}/activar`);
    },
    async importar(formData) {
        return await service.post("/articulos/importar", formData, {
            timeout: 600000,
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },
    async formatoImportacion() {
        return await service.get("/articulos/formato-importacion", {
            responseType: "blob",
        });
    },
    async exportar(opciones = { params: {} }) {
        return await service.get("/articulos/exportar", {
            responseType: "blob",
            params: opciones.params,
        });
    },
    async showReportePdf(opciones = { params: {} }) {
        return await service.get("/articulos/reporte-pdf", {
            params: opciones.params,
        });
    },
};
