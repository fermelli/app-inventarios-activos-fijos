import service from "./service";

export default {
    async index(opciones = { params: {} }) {
        return await service.get("/asignaciones-activos-fijos", {
            params: opciones.params,
        });
    },
    async store(datos) {
        return await service.post("/asignaciones-activos-fijos", datos);
    },
    async show(id) {
        return await service.get(`/asignaciones-activos-fijos/${id}`);
    },
    async devolver(id, datos) {
        return await service.patch(
            `/asignaciones-activos-fijos/${id}/devolver`,
            datos,
        );
    },
};
