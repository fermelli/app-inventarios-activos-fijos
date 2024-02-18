<script>
import EntradaArticuloService from "./../../services/entradas-articulos";
import { useToast } from "vue-toastification";
import FormularioEntrada from "./components/FormularioEntrada.vue";
import TablaDatosServidorEntradas from "./components/TablaDatosServidorEntradas.vue";
import vistaMixin from "../../mixins/vista.mixin";
import TablaDatosDetallesEntradas from "./components/TablaDatosDetallesEntradas.vue";

export default {
    name: "EntradasVista",
    components: {
        FormularioEntrada,
        TablaDatosServidorEntradas,
        TablaDatosDetallesEntradas,
    },
    mixins: [vistaMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Entrada Artículo",
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
        listadoDatos() {
            if (!this.itemSeleccionado) {
                return [];
            }

            return [
                {
                    titulo: "Fecha",
                    subtitulo: this.itemSeleccionado.fecha,
                },
                {
                    titulo: "Comprobante",
                    subtitulo: this.itemSeleccionado.comprobante,
                },
                {
                    titulo: "Número Comprobante",
                    subtitulo: this.itemSeleccionado.numero_comprobante,
                },
                {
                    titulo: "Institución",
                    subtitulo: this.itemSeleccionado.institucion.nombre,
                },
                {
                    titulo: "Registrado por",
                    subtitulo: this.itemSeleccionado.usuario.nombre,
                },
                {
                    titulo: "Observación",
                    subtitulo: this.itemSeleccionado.observacion || "-",
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
        async obtenerEntradasArticulos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda = payload?.search;

            try {
                const { data } = await EntradaArticuloService.index({
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
                    await EntradaArticuloService.softDestroy(
                        this.itemSeleccionado.id,
                    );
                } else if (accion === "activar") {
                    await EntradaArticuloService.restore(
                        this.itemSeleccionado.id,
                    );
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerEntradasArticulos();
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
                institucion_id: null,
                fecha: null,
                comprobante: null,
                numero_comprobante: null,
                observacion: null,
                detalles_transacciones: [],
            };
        },
        mostrarItem(item) {
            this.itemSeleccionado = item;
            this.mostradoDialogoMostrarItem = true;
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex align-center">
                <h2 class="text-h5">Entrada Artículos</h2>

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
                    @click="obtenerEntradasArticulos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorEntradas
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerEntradasArticulos"
                @mostrar-item="mostrarItem"
            />
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="1200">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioEntrada
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerEntradasArticulos"
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
                            class="py-0"
                            cols="12"
                            lg="3"
                        >
                            <v-list lines="two">
                                <v-list-item
                                    class="py-0"
                                    :title="dato.titulo"
                                    :subtitle="dato.subtitulo"
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12">
                            <TablaDatosDetallesEntradas
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
                        color="blue-grey"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cerrar"
                        @click="mostradoDialogoMostrarItem = false"
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
