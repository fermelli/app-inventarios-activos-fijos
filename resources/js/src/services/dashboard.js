import service from "./service";

export default {
    async cantidadRegistros() {
        return await service.get("/dashboard/cantidad-registros");
    },
    async articulosConStockMinimo() {
        return await service.get("/dashboard/articulos-con-stock-minimo");
    },
    async articulosRecientementeVencidos() {
        return await service.get("/dashboard/articulos-recientemente-vencidos");
    },
    async articulosProximosVencer() {
        return await service.get("/dashboard/articulos-proximos-vencer");
    },
};
