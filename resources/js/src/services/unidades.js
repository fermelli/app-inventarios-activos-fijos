import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/unidades", { params: opciones.params });
    },
    async store(datos) {
        return await service.post("/unidades", datos);
    },
    async show(id) {
        return await service.get(`/unidades/${id}`);
    },
    async update(id, datos) {
        return await service.patch(`/unidades/${id}`, datos);
    },
    async destroy(id) {
        return await service.delete(`/unidades/${id}`);
    },
};
