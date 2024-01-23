import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/categorias", { params: opciones.params });
    },
    async indexPadresConHijas(opciones = { params: {} }) {
        return await service.get("/categorias/padres-con-hijas", {
            params: opciones.params,
        });
    },
    async store(datos) {
        return await service.post("/categorias", datos);
    },
    async show(id) {
        return await service.get(`/categorias/${id}`);
    },
    async update(id, datos) {
        return await service.patch(`/categorias/${id}`, datos);
    },
    async destroy(id) {
        return await service.delete(`/categorias/${id}`);
    },
    async softDestroy(id) {
        return await service.patch(`/categorias/${id}/desactivar`);
    },
    async restore(id) {
        return await service.patch(`/categorias/${id}/activar`);
    },
};
