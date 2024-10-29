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
    async cantidadActivosFijosPorEstadoAgrupadoPorCategoria() {
        return await service.get(
            "/dashboard/cantidad-activos-fijos-por-estado-agrupados-por-categoria",
        );
    },
    async usuariosConMasActivosFijosAsignados() {
        return await service.get(
            "/dashboard/usuarios-con-mas-activos-fijos-asignados",
        );
    },
    async ubicacionesConMasActivosFijosAsignados() {
        return await service.get(
            "/dashboard/ubicaciones-con-mas-activos-fijos-asignados",
        );
    },
};
