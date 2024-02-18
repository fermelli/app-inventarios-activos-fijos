import TablaDatosServidorSolicitudes from "../components/TablaDatosServidorSolicitudes.vue";
import TablaDatosDetallesSolicitudes from "../components/TablaDatosDetallesSolicitudes.vue";
import { ESTADOS_SOLICITUDES, ROLES } from "../../../utils/constantes";
import { mapGetters } from "vuex";

export default {
    components: {
        TablaDatosServidorSolicitudes,
        TablaDatosDetallesSolicitudes,
    },
    props: {
        tipo: {
            type: String,
            required: true,
            validator: (valor) => ["usuario", "todas"].includes(valor),
        },
    },
    data() {
        return {
            nombreItem: "Solicitud Artículos",
            pagina: 1,
            itemsPorPagina: parseInt(
                localStorage.getItem(`itemsPorPagina-${this.$route.name}`) ||
                    10,
            ),
            busqueda: null,
            totalItems: 0,
            mostradoDialogoMostrarItem: false,
            accion: null,
            roles: ROLES,
        };
    },
    computed: {
        listadoDatos() {
            if (!this.itemSeleccionado) {
                return [];
            }

            const listado = [
                {
                    titulo: "Número Solicitud",
                    subtitulo: this.itemSeleccionado.numero_solicitud,
                },
                {
                    titulo: "Solicitante",
                    subtitulo: this.itemSeleccionado.solicitante?.nombre,
                },
                {
                    titulo: "N° de Artículos",
                    subtitulo:
                        this.itemSeleccionado.detalles_transacciones.length,
                },
                {
                    titulo: "Estado Solicitud",
                    subtitulo: this.itemSeleccionado.estado_solicitud,
                },
                {
                    titulo: "Creado en",
                    subtitulo: this.itemSeleccionado.creado_en,
                },
            ];

            if (
                this.itemSeleccionado.estado_solicitud !==
                ESTADOS_SOLICITUDES.pendiente
            ) {
                listado.splice(
                    listado.length - 1,
                    0,
                    {
                        titulo: "Atendido por",
                        subtitulo: this.itemSeleccionado.usuario?.nombre,
                    },
                    {
                        titulo: "Fecha y Hora de Atención",
                        subtitulo: this.itemSeleccionado.fecha_hora_atencion,
                    },
                );

                if (
                    this.itemSeleccionado.estado_solicitud ===
                    ESTADOS_SOLICITUDES.entregada
                ) {
                    listado.splice(
                        listado.length - 1,
                        0,
                        {
                            titulo: "Entregado por",
                            subtitulo:
                                this.itemSeleccionado.despachante?.nombre,
                        },
                        {
                            titulo: "Fecha y Hora de Entrega",
                            subtitulo: this.itemSeleccionado.fecha_hora_entrega,
                        },
                    );
                }

                if (
                    this.itemSeleccionado.estado_solicitud ===
                    ESTADOS_SOLICITUDES.anulada
                ) {
                    listado.splice(
                        listado.length - 1,
                        0,
                        {
                            titulo: "Anulado por",
                            subtitulo: this.itemSeleccionado.anulador?.nombre,
                        },
                        {
                            titulo: "Fecha y Hora de Anulación",
                            subtitulo:
                                this.itemSeleccionado.fecha_hora_anulacion,
                        },
                    );
                }
            }

            return listado;
        },
        ...mapGetters("autenticacion", ["usuarioAutenticado"]),
        mostradoBotonAccion() {
            return (
                this.usuarioAutenticado.rol === this.roles.administrador &&
                this.tipo === "todas"
            );
        },
    },
    methods: {
        crearDatosItem() {
            return {
                id: null,
                numero_solicitud: null,
                solicitante_id: null,
                solicitante: null,
                observacion: null,
                estado_solicitud: null,
                usuario_id: null,
                usuario: null,
                detalles_transacciones: [],
                creado_en: null,
            };
        },
        async mostrarItem(itemId) {
            try {
                const { data } = await this.metodoServicioObtenerItem(itemId);

                this.itemSeleccionado = data.datos;
                this.mostradoDialogoMostrarItem = true;
            } catch (error) {
                console.log(error);
            }
        },
        cerrarDialogoMostrarItem() {
            this.mostradoDialogoMostrarItem = false;
            this.itemSeleccionado = this.crearDatosItem();
        },
        manejarDialogoConfirmacionAccion(accion) {
            this.mensajeDialogoConfirmacion = /*html */ `¿Está seguro que desea <strong>${accion.toUpperCase()}</strong> la salida de artículos con N° de Solicitud <strong>${this.itemSeleccionado.numero_solicitud}</strong> solicitado por <strong>${this.itemSeleccionado.solicitante?.nombre}</strong>?`;
            this.funcionDialogoConfirmacion = () =>
                this.atenderSalidaArticulos(accion);
            this.mostradoDialogoConfirmacion = true;
        },
    },
};
