<script>
import ArticuloService from "./../../services/articulos";
import { useToast } from "vue-toastification";
import FormularioArticulo from "./components/FormularioArticulo.vue";
import TablaDatosServidorArticulos from "./components/TablaDatosServidorArticulos.vue";
import vistaMixin from "../../mixins/vista.mixin";

export default {
    name: "ArticulosVista",
    components: {
        FormularioArticulo,
        TablaDatosServidorArticulos,
    },
    mixins: [vistaMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Artículo",
            pagina: 1,
            itemsPorPagina: parseInt(
                localStorage.getItem(`itemsPorPagina-${this.$route.name}`) ||
                    10,
            ),
            busqueda: null,
            totalItems: 0,
            categoria_id: null,
        };
    },
    methods: {
        async obtenerArticulos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda =
                !!payload && "search" in payload
                    ? payload?.search
                    : this.busqueda;
            this.categoria_id =
                !!payload && "categoria_id" in payload
                    ? payload?.categoria_id
                    : this.categoria_id;

            try {
                const { data } = await ArticuloService.index({
                    params: {
                        orden_direccion: "desc",
                        con_eliminados: true,
                        pagina: this.pagina,
                        items_por_pagina: this.itemsPorPagina,
                        busqueda: this.busqueda,
                        categoria_id: this.categoria_id,
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
            if (!["eliminar", "desactivar", "activar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "eliminar") {
                    await ArticuloService.destroy(this.itemSeleccionado.id);
                } else if (accion === "desactivar") {
                    await ArticuloService.softDestroy(this.itemSeleccionado.id);
                } else if (accion === "activar") {
                    await ArticuloService.restore(this.itemSeleccionado.id);
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerArticulos();
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
                categoria_id: null,
                unidad_id: null,
                ubicacion_id: null,
                codigo: null,
                nombre: null,
                cantidad: null,
                articulos_lotes: [],
            };
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex align-center">
                <h2 class="text-h5">Artículos</h2>

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
                    @click="obtenerArticulos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorArticulos
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerArticulos"
            />
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="440">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioArticulo
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerArticulos"
                        @cancelar-guardado="cancelarGuardado"
                    />
                </v-card-text>
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
