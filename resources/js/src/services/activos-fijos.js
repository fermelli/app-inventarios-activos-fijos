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
};
