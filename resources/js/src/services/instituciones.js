import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/instituciones", { params: opciones.params });
    },
    async store(datos) {
        return await service.post("/instituciones", datos);
    },
    async show(id) {
        return await service.get(`/instituciones/${id}`);
    },
    async update(id, datos) {
        return await service.patch(`/instituciones/${id}`, datos);
    },
    async destroy(id) {
        return await service.delete(`/instituciones/${id}`);
    },
    async softDestroy(id) {
        return await service.patch(`/instituciones/${id}/desactivar`);
    },
    async restore(id) {
        return await service.patch(`/instituciones/${id}/activar`);
    },
};
