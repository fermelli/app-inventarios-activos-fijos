import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/solicitudes-articulos", {
            params: opciones.params,
        });
    },
    async indexUsuario(opciones = { params: {} }) {
        return await service.get("/solicitudes-articulos/usuario", {
            params: opciones.params,
        });
    },
    async store(datos) {
        return await service.post("/solicitudes-articulos", datos);
    },
    async softDestroy(id) {
        return await service.patch(`/solicitudes-articulos/${id}/desactivar`);
    },
    async restore(id) {
        return await service.patch(`/solicitudes-articulos/${id}/activar`);
    },
};
