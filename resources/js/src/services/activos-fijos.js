import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/activos-fijos", { params: opciones.params });
    },
    async store(datos) {
        return await service.post("/activos-fijos", datos);
    },
    async show(id) {
        return await service.get(`/activos-fijos/${id}`);
    },
    async update(id, datos) {
        return await service.patch(`/activos-fijos/${id}`, datos);
    },
    async destroy(id) {
        return await service.delete(`/activos-fijos/${id}`);
    },
    async darBaja(id, datos) {
        return await service.patch(`/activos-fijos/${id}/dar-baja`, datos);
    },
    async importar(formData) {
        return await service.post("/activos-fijos/importar", formData, {
            timeout: 600000,
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },
    async formatoImportacion() {
        return await service.get("/activos-fijos/formato-importacion", {
            responseType: "blob",
        });
    },
    async exportar(opciones = { params: {} }) {
        return await service.get("/activos-fijos/exportar", {
            responseType: "blob",
            params: opciones.params,
        });
    },
    async showReportePdf(opciones = { params: {} }) {
        return await service.get("/activos-fijos/reporte-pdf", {
            params: opciones.params,
        });
    },
    async generarEtiquetaPdf(id, opciones = { params: {} }) {
        return await service.get(`/activos-fijos/${id}/etiqueta-pdf`, {
            params: opciones.params,
        });
    },
};
