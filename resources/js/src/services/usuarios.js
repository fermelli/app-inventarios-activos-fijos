import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/usuarios", { params: opciones.params });
    },
    async show(id) {
        return await service.get(`/usuarios/${id}`);
    },
    async update(id, datos) {
        return await service.patch(`/usuarios/${id}`, datos);
    },
    async cambiarRol(id, datos) {
        return await service.patch(`/usuarios/${id}/cambiar-rol`, datos);
    },
    async destroy(id) {
        return await service.delete(`/usuarios/${id}`);
    },
    async softDestroy(id) {
        return await service.patch(`/usuarios/${id}/desactivar`);
    },
    async restore(id) {
        return await service.patch(`/usuarios/${id}/activar`);
    },
};
