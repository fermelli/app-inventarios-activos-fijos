<script>
import DashboardService from "./../../services/dashboard";
import TarjetaCantidadRegistros from "../../components/TarjetaCantidadRegistros.vue";
import TarjetaTablaArticulosInfo from "../../components/TarjetaTablaArticulosInfo.vue";

export default {
    name: "DashboardVista",
    components: {
        TarjetaCantidadRegistros,
        TarjetaTablaArticulosInfo,
    },
    data() {
        return {
            cantidadRegistros: {
                articulos: {
                    cantidad: 0,
                    titulo: "Artículos",
                    colorIcono: "primary",
                    icono: "mdi-package-variant-closed",
                    ruta: { name: "articulos" },
                },
                activos_fijos: {
                    cantidad: 0,
                    titulo: "Activos Fijos",
                    colorIcono: "primary",
                    icono: "mdi-archive-arrow-down-outline",
                    ruta: { name: "activos-fijos" },
                },
                activos_fijos_activos: {
                    cantidad: 0,
                    titulo: "Activos Fijos Activos",
                    colorIcono: "success",
                    icono: "mdi-archive-arrow-down-outline",
                    ruta: { name: "activos-fijos" },
                },
                activos_fijos_de_baja: {
                    cantidad: 0,
                    titulo: "Activos Fijos de Baja",
                    colorIcono: "error",
                    icono: "mdi-archive-arrow-down-outline",
                    ruta: { name: "activos-fijos" },
                },
                entradas_validas: {
                    cantidad: 0,
                    titulo: "Entradas Válidas",
                    colorIcono: "success",
                    icono: "mdi-arrow-right",
                    ruta: { name: "entradas" },
                },
                entradas_anuladas: {
                    cantidad: 0,
                    titulo: "Entradas Anuladas",
                    colorIcono: "error",
                    icono: "mdi-arrow-right",
                    ruta: { name: "entradas" },
                },
                solicitudes_pendientes: {
                    cantidad: 0,
                    titulo: "Solicitudes Pendientes",
                    colorIcono: "warning",
                    icono: "mdi-file-document-multiple-outline",
                    ruta: { name: "solicitudes" },
                },
                solicitudes_aprobadas: {
                    cantidad: 0,
                    titulo: "Solicitudes Aprobadas",
                    colorIcono: "success",
                    icono: "mdi-file-document-multiple-outline",
                    ruta: { name: "solicitudes" },
                },
                solicitudes_rechazadas: {
                    cantidad: 0,
                    titulo: "Solicitudes Rechazadas",
                    colorIcono: "error",
                    icono: "mdi-file-document-multiple-outline",
                    ruta: { name: "solicitudes" },
                },
                salidas_entregadas: {
                    cantidad: 0,
                    titulo: "Salidas Entregadas",
                    colorIcono: "success",
                    icono: "mdi-arrow-left",
                    ruta: { name: "salidas" },
                },
                salidas_anuladas: {
                    cantidad: 0,
                    titulo: "Salidas Anuladas",
                    colorIcono: "error",
                    icono: "mdi-arrow-left",
                    ruta: { name: "salidas" },
                },
                activos_fijos_asignados: {
                    cantidad: 0,
                    titulo: "Activos Fijos Asignados",
                    colorIcono: "success",
                    icono: "mdi-archive-check-outline",
                },
                activos_fijos_no_asignados: {
                    cantidad: 0,
                    titulo: "Activos Fijos No Asignados",
                    colorIcono: "warning",
                    icono: "mdi-archive-remove-outline",
                },
            },
            articulosConStockMinimo: {
                titulo: "Artículos con Stock Mínimo",
                icono: "mdi-package-variant-closed",
                colorIcono: "primary",
                items: [],
                cargando: false,
                cabeceras: [
                    { title: "#", key: "index", filterable: false },
                    { title: "Código", key: "codigo" },
                    { title: "Artículo", key: "nombre" },
                    { title: "Unidad", key: "unidad" },
                    { title: "Cantidad", key: "stock" },
                ],
            },
            articulosRecientementeVencidos: {
                titulo: "Artículos Recientemente Vencidos",
                icono: "mdi-package-variant-closed",
                colorIcono: "error",
                items: [],
                cargando: false,
                cabeceras: [
                    { title: "#", key: "index", filterable: false },
                    { title: "Código", key: "codigo" },
                    { title: "Artículo", key: "nombre" },
                    { title: "Unidad", key: "unidad" },
                    { title: "Cantidad", key: "cantidad" },
                    { title: "Fecha Vencimiento", key: "fecha_vencimiento" },
                ],
            },
            articulosProximosVencer: {
                titulo: "Artículos Próximos a Vencer",
                icono: "mdi-package-variant-closed",
                colorIcono: "warning",
                items: [],
                cargando: false,
                cabeceras: [
                    { title: "#", key: "index", filterable: false },
                    { title: "Código", key: "codigo" },
                    { title: "Artículo", key: "nombre" },
                    { title: "Unidad", key: "unidad" },
                    { title: "Cantidad", key: "cantidad" },
                    { title: "Fecha Vencimiento", key: "fecha_vencimiento" },
                ],
            },
        };
    },
    created() {
        this.actualizarDashboard();
    },
    methods: {
        async obtenerCantidadRegistros() {
            try {
                const { data } = await DashboardService.cantidadRegistros();

                Object.keys(data?.datos).forEach((key) => {
                    if (key in this.cantidadRegistros) {
                        this.cantidadRegistros[key].cantidad = data.datos[key];
                    }
                });
            } catch (error) {
                console.log(error);
            }
        },
        async obtenerArticulosConStockMinimo() {
            this.articulosConStockMinimo.cargando = true;

            try {
                const { data } =
                    await DashboardService.articulosConStockMinimo();

                this.articulosConStockMinimo.items = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.articulosConStockMinimo.cargando = false;
            }
        },
        async obtenerArticulosRecientementeVencidos() {
            this.articulosRecientementeVencidos.cargando = true;

            try {
                const { data } =
                    await DashboardService.articulosRecientementeVencidos();

                this.articulosRecientementeVencidos.items = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.articulosRecientementeVencidos.cargando = false;
            }
        },
        async obtenerArticulosProximosVencer() {
            this.articulosProximosVencer.cargando = true;

            try {
                const { data } =
                    await DashboardService.articulosProximosVencer();

                this.articulosProximosVencer.items = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.articulosProximosVencer.cargando = false;
            }
        },
        actualizarDashboard() {
            this.obtenerCantidadRegistros();
            this.obtenerArticulosConStockMinimo();
            this.obtenerArticulosRecientementeVencidos();
            this.obtenerArticulosProximosVencer();
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex flex-wrap align-center">
                <h2 class="text-h5">Dashboard</h2>

                <v-btn
                    class="ml-2"
                    color="primary"
                    density="compact"
                    icon="mdi-reload"
                    title="Recargar"
                    @click="actualizarDashboard"
                />
            </div>
        </v-col>

        <v-col
            v-for="(datosCantidad, key) in cantidadRegistros"
            :key="key"
            cols="12"
            md="4"
            lg="3"
            xl="2"
        >
            <TarjetaCantidadRegistros :datos="datosCantidad" />
        </v-col>

        <v-col cols="12" />

        <v-col cols="12" md="6" lg="4">
            <TarjetaTablaArticulosInfo :datos="articulosConStockMinimo" />
        </v-col>

        <v-col cols="12" md="6" lg="4">
            <TarjetaTablaArticulosInfo
                :datos="articulosRecientementeVencidos"
            />
        </v-col>

        <v-col cols="12" md="6" lg="4">
            <TarjetaTablaArticulosInfo :datos="articulosProximosVencer" />
        </v-col>
    </v-row>
</template>
