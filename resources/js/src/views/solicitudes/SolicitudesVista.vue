<script>
import SolicitudArticuloService from "./../../services/solicitudes-articulos";
import { useToast } from "vue-toastification";
import FormularioSolicitud from "./components/FormularioSolicitud.vue";
import TablaDatosServidorSolicitudes from "./components/TablaDatosServidorSolicitudes.vue";
import vistaMixin from "../../mixins/vista.mixin";
import TablaDatosDetallesSolicitudes from "./components/TablaDatosDetallesSolicitudes.vue";

export default {
    name: "SolicitudesVista",
    components: {
        FormularioSolicitud,
        TablaDatosServidorSolicitudes,
        TablaDatosDetallesSolicitudes,
    },
    mixins: [vistaMixin],
    props: {
        tipo: {
            type: String,
            required: true,
            validator: (valor) => ["usuario", "todas"].includes(valor),
        },
    },
    setup() {
        const toast = useToast();

        return { toast };
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
        };
    },
    computed: {
        titulo() {
            return this.tipo === "usuario"
                ? "Mis Solicitudes de Artículos"
                : "Solicitudes de Artículos";
        },
        listadoDatos() {
            if (!this.itemSeleccionado) {
                return [];
            }

            return [
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
                    titulo: "Creado en",
                    subtitulo: this.itemSeleccionado.creado_en,
                },
            ];
        },
    },
    methods: {
        async obtenerSolicitudesArticulos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda = payload?.search;

            try {
                const servicio =
                    this.tipo === "usuario"
                        ? SolicitudArticuloService.indexUsuario
                        : SolicitudArticuloService.index;
                const { data } = await servicio({
                    params: {
                        orden_direccion: "desc",
                        con_eliminados: true,
                        pagina: this.pagina,
                        items_por_pagina: this.itemsPorPagina,
                        busqueda: this.busqueda,
                    },
                });

                this.items = data.datos;
                this.totalItems = data.metadatos.total || 0;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoItems = false;
            }
        },
        async accionItem(accion) {
            if (!["desactivar", "activar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "desactivar") {
                    await SolicitudArticuloService.softDestroy(
                        this.itemSeleccionado.id,
                    );
                } else if (accion === "activar") {
                    await SolicitudArticuloService.restore(
                        this.itemSeleccionado.id,
                    );
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerSolicitudesArticulos();
                this.cancelarAccion();
            } catch (error) {
                console.log(error);
            } finally {
                this.realizandoAccion = false;
            }
        },
        crearDatosItem() {
            return {
                id: null,
                numero_solicitud: null,
                solicitante_id: null,
                solicitante: null,
                observacion: null,
                detalles_transacciones: [],
            };
        },
        async mostrarItem(itemId) {
            try {
                const { data } = await SolicitudArticuloService.show(itemId);

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
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex align-center">
                <h2 class="text-h5">{{ titulo }}</h2>

                <v-btn
                    class="ml-2"
                    color="success"
                    density="compact"
                    prepend-icon="mdi-plus"
                    title="Registrar"
                    @click="() => mostrarDialogoFormulario()"
                >
                    Registrar
                </v-btn>

                <v-btn
                    class="ml-2"
                    color="primary"
                    density="compact"
                    icon="mdi-reload"
                    :loading="cargandoItems"
                    :disabled="cargandoItems"
                    title="Recargar"
                    @click="obtenerSolicitudesArticulos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorSolicitudes
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerSolicitudesArticulos"
                @mostrar-item="mostrarItem"
            />
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="1200">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioSolicitud
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerSolicitudesArticulos"
                        @cancelar-guardado="cancelarGuardado"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="mostradoDialogoMostrarItem" width="1200">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ nombreItem }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <v-row>
                        <v-col
                            v-for="(dato, indice) in listadoDatos"
                            :key="indice"
                            cols="12"
                            lg="3"
                        >
                            <v-list lines="two">
                                <v-list-item
                                    :title="dato.titulo"
                                    :subtitle="dato.subtitulo"
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12">
                            <v-list lines="two">
                                <v-list-item
                                    title="Observación"
                                    :subtitle="itemSeleccionado.observacion"
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12">
                            <TablaDatosDetallesSolicitudes
                                :detalles-transacciones="
                                    itemSeleccionado.detalles_transacciones
                                "
                                :editable="false"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-btn
                        color="secondary"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cerrar"
                        @click="cerrarDialogoMostrarItem"
                    >
                        Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <DialogoConfirmacion
            v-model="mostradoDialogoConfirmacion"
            :mensaje="mensajeDialogoConfirmacion"
            :realizando-accion="realizandoAccion"
            @aceptar="funcionDialogoConfirmacion"
            @cancelar="cancelarAccion"
        />
    </v-row>
</template>
