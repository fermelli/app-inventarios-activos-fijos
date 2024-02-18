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
    async softDestroy(id) {
        return await service.patch(`/entradas-articulos/${id}/desactivar`);
    },
    async restore(id) {
        return await service.patch(`/entradas-articulos/${id}/activar`);
    },
    async anular(id) {
        return await service.patch(`/entradas-articulos/${id}/anular`);
    },
};
