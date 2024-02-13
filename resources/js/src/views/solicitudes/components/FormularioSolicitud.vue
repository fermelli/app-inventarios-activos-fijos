<script>
import SolicitudArticuloService from "./../../../services/solicitudes-articulos";
import { useToast } from "vue-toastification";
import formularioMixin from "../../../mixins/formulario.mixin";
import TablaDatosServidorArticulos from "../../articulos/components/TablaDatosServidorArticulos.vue";
import vistaMixin from "../../../mixins/vista.mixin";
import ArticuloService from "./../../../services/articulos";
import TablaDatosDetallesSolicitudes from "./TablaDatosDetallesSolicitudes.vue";

export default {
    name: "FormularioSolicitud",
    components: {
        TablaDatosServidorArticulos,
        TablaDatosDetallesSolicitudes,
    },
    mixins: [formularioMixin, vistaMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "numero_solicitud" in valor &&
                    "solicitante_id" in valor &&
                    "solicitante" in valor &&
                    "observacion" in valor &&
                    "detalles_transacciones" in valor
                );
            },
        },
    },
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            metodoStore: SolicitudArticuloService.store,
            metodoUpdate: SolicitudArticuloService.update,
            mostradoDialogoTablaArticulos: false,
            pagina: 1,
            itemsPorPagina: parseInt(
                localStorage.getItem(`itemsPorPagina-${this.$route.name}`) ||
                    10,
            ),
            busqueda: null,
            totalItems: 0,
            reglasValidacionObservacion: [
                (valor) => !!valor || "La observación es requerida",
                (valor) =>
                    !valor ||
                    valor.length <= 255 ||
                    "La observación debe tener menos de 255 caracteres",
            ],
        };
    },
    methods: {
        async obtenerArticulos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda = payload?.search;

            try {
                const { data } = await ArticuloService.index({
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
        crearDatosItem() {
            return {};
        },
        seleccionarItem(item) {
            const indiceItemExistente =
                this.formulario.detalles_transacciones.findIndex(
                    (detalle) => detalle.articulo_id === item.id,
                );

            if (indiceItemExistente !== -1) {
                this.formulario.detalles_transacciones[
                    indiceItemExistente
                ].cantidad += 1;
            } else {
                this.formulario.detalles_transacciones.push({
                    articulo_id: item.id,
                    cantidad: 1,
                    articulo: item,
                });
            }

            this.toast.success("Artículo agregado");
        },
    },
};
</script>

<template>
    <v-form
        v-model="formularioValido"
        :loading="guardandoItem"
        @submit.prevent="guardarItem"
    >
        <v-row>
            <v-col cols="12" lg="8" class="py-0">
                <v-textarea
                    v-model="formulario.observacion"
                    class="mb-2"
                    label="Observación"
                    name="observacion"
                    type="text"
                    rows="3"
                    density="compact"
                    :rules="reglasValidacionObservacion"
                    clearable
                />
            </v-col>

            <v-col cols="12" lg="4" class="py-0">
                <v-btn
                    color="green"
                    density="compact"
                    prepend-icon="mdi-magnify"
                    title="Buscar Artículo"
                    type="button"
                    @click="mostradoDialogoTablaArticulos = true"
                >
                    Buscar Artículo
                </v-btn>
            </v-col>

            <v-col cols="12">
                <TablaDatosDetallesSolicitudes
                    :detalles-transacciones="formulario.detalles_transacciones"
                    :editable="true"
                    @quitar-detalle-transaccion="
                        (indice) =>
                            formulario.detalles_transacciones.splice(indice, 1)
                    "
                />
            </v-col>

            <v-col cols="12">
                <v-btn
                    color="primary"
                    density="compact"
                    prepend-icon="mdi-content-save"
                    title="Guardar"
                    type="submit"
                    :disabled="guardandoItem"
                >
                    Guardar
                </v-btn>

                <v-btn
                    class="ml-2"
                    color="blue-grey"
                    density="compact"
                    prepend-icon="mdi-close"
                    title="Cancelar"
                    @click="emitCancelarGuardado"
                >
                    Cancelar
                </v-btn>
            </v-col>

            <v-dialog v-model="mostradoDialogoTablaArticulos" width="1200">
                <v-card>
                    <v-card-title>
                        <span class="text-h6">Seleccionar Artículo</span>
                    </v-card-title>

                    <v-card-text class="pa-4">
                        <TablaDatosServidorArticulos
                            :items="items"
                            :total-items="totalItems"
                            :cargando-items="cargandoItems"
                            :solo-seleccion-items="true"
                            @mostrar-formulario="mostrarDialogoFormulario"
                            @mostrar-confirmacion="mostrarDialogoConfirmacion"
                            @cargar-items="obtenerArticulos"
                            @seleccionar-item="seleccionarItem"
                        />
                    </v-card-text>

                    <v-card-actions>
                        <v-btn
                            color="blue-grey"
                            density="compact"
                            prepend-icon="mdi-close"
                            title="Cerrar"
                            @click="mostradoDialogoTablaArticulos = false"
                        >
                            Cerrar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </v-form>
</template>
