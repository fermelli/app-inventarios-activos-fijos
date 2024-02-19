import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/salidas-articulos", {
            params: opciones.params,
        });
    },
    async indexUsuario(opciones = { params: {} }) {
        return await service.get("/salidas-articulos/usuario", {
            params: opciones.params,
        });
    },
    async show(id) {
        return await service.get(`/salidas-articulos/${id}`);
    },
    async aprobar(id) {
        return await service.patch(`/salidas-articulos/${id}/aprobar`);
    },
    async rechazar(id) {
        return await service.patch(`/salidas-articulos/${id}/rechazar`);
    },
    async entregar(id) {
        return await service.patch(`/salidas-articulos/${id}/entregar`);
    },
    async anular(id) {
        return await service.patch(`/salidas-articulos/${id}/anular`);
    },
    async showReportePdf(id) {
        return await service.get(`/salidas-articulos/${id}/reporte-pdf`);
    },
};
