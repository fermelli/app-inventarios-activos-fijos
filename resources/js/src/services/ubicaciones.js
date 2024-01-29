import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/ubicaciones", { params: opciones.params });
    },
    async store(datos) {
        return await service.post("/ubicaciones", datos);
    },
    async show(id) {
        return await service.get(`/ubicaciones/${id}`);
    },
    async update(id, datos) {
        return await service.patch(`/ubicaciones/${id}`, datos);
    },
    async destroy(id) {
        return await service.delete(`/ubicaciones/${id}`);
    },
};
