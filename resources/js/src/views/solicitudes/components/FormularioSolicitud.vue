<script>
import SolicitudArticuloService from "./../../../services/solicitudes-articulos";
import { useToast } from "vue-toastification";
import formularioMixin from "../../../mixins/formulario.mixin";
import TablaDatosServidorArticulos from "../../articulos/components/TablaDatosServidorArticulos.vue";
import vistaMixin from "../../../mixins/vista.mixin";
import TablaDatosDetallesSolicitudes from "./TablaDatosDetallesSolicitudes.vue";
import articulosMixin from "../../../mixins/articulos.mixin";
import UsuarioService from "../../../services/usuarios";

export default {
    name: "FormularioSolicitud",
    components: {
        TablaDatosServidorArticulos,
        TablaDatosDetallesSolicitudes,
    },
    mixins: [formularioMixin, vistaMixin, articulosMixin],
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
            metodoStore:
                this.tipo === "todas"
                    ? SolicitudArticuloService.storeConSolicitante
                    : SolicitudArticuloService.store,
            metodoUpdate: SolicitudArticuloService.update,
            mostradoDialogoTablaArticulos: false,
            usuarios: [],
            cargandoUsuarios: false,
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
            reglasValidacionSolicitanteId: [
                (valor) => !!valor || "El solicitante es requerido",
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
            ],
        };
    },
    created() {
        if (this.tipo === "todas") {
            this.obtenerUsuarios();
        }
    },
    methods: {
        crearDatosItem() {
            return {};
        },
        seleccionarItem(item) {
            if (item.cantidad <= 0) {
                this.toast.error("El artículo no tiene stock");

                return;
            }

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
        async obtenerUsuarios() {
            this.cargandoUsuarios = true;

            try {
                const { data } = await UsuarioService.index({
                    params: { orden_direccion: "asc" },
                });

                this.usuarios = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoUsuarios = false;
            }
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
        <v-card>
            <v-card-title>
                <div
                    class="d-flex flex-wrap justify-space-between align-center"
                >
                    <span class="text-h6">{{ titulo }}</span>

                    <v-btn
                        class="my-1"
                        color="blue-grey"
                        density="compact"
                        variant="text"
                        icon="mdi-close"
                        title="Cerrar"
                        @click="emitCancelarGuardado"
                    />
                </div>
            </v-card-title>

            <v-card-text class="pa-4 pb-0">
                <v-row>
                    <v-col cols="12" lg="7" class="py-0">
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

                    <v-col
                        v-if="tipo === 'todas'"
                        cols="12"
                        lg="5"
                        class="py-0"
                    >
                        <v-autocomplete
                            v-model="formulario.solicitante_id"
                            class="mb-2"
                            :items="usuarios"
                            item-value="id"
                            item-title="nombre"
                            label="Solcitante"
                            name="solicitante_id"
                            density="compact"
                            :rules="reglasValidacionSolicitanteId"
                            clear-on-select
                            clearable
                        >
                            <template #item="{ props, item }">
                                <v-list-item
                                    v-bind="props"
                                    :title="item.raw.nombre"
                                    :subtitle="
                                        item.raw?.dependencia?.nombre || '-'
                                    "
                                />
                            </template>
                        </v-autocomplete>
                    </v-col>

                    <v-col cols="12" class="pt-0 pb-2">
                        <v-btn
                            class="mb-4"
                            color="info"
                            density="compact"
                            prepend-inner-icon="mdi-magnify"
                            title="Buscar Artículo"
                            type="button"
                            @click="mostradoDialogoTablaArticulos = true"
                        >
                            Buscar Artículo
                        </v-btn>
                    </v-col>
                </v-row>

                <TablaDatosDetallesSolicitudes
                    style="padding: 1rem 0 !important"
                    :detalles-transacciones="formulario.detalles_transacciones"
                    :editable="true"
                    @quitar-detalle-transaccion="
                        (indice) =>
                            formulario.detalles_transacciones.splice(indice, 1)
                    "
                />
            </v-card-text>

            <v-card-actions>
                <div
                    class="d-flex flex-wrap justify-space-between align-center"
                >
                    <v-btn
                        class="ma-1"
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
                        class="ma-1"
                        color="blue-grey"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cancelar"
                        @click="emitCancelarGuardado"
                    >
                        Cancelar
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>

        <v-dialog
            v-model="mostradoDialogoTablaArticulos"
            width="1200"
            persistent
            scrollable
        >
            <v-card>
                <v-card-title>
                    <div
                        class="d-flex flex-wrap justify-space-between align-center"
                    >
                        <span class="text-h6">Seleccionar Artículo</span>

                        <v-btn
                            class="my-1"
                            color="blue-grey"
                            density="compact"
                            variant="text"
                            icon="mdi-close"
                            title="Cerrar"
                            @click="mostradoDialogoTablaArticulos = false"
                        />
                    </div>
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
                        @exportar-pdf="exportarArticulosPdf"
                        @exportar-excel="exportarArticulosExcel"
                    />
                </v-card-text>

                <v-card-actions>
                    <div
                        class="d-flex flex-wrap justify-space-between align-center"
                    >
                        <v-btn
                            class="ma-1"
                            color="blue-grey"
                            density="compact"
                            prepend-icon="mdi-close"
                            title="Cerrar"
                            @click="mostradoDialogoTablaArticulos = false"
                        >
                            Cerrar
                        </v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <DialogoReportePdf
            v-model="mostradoDialogoReportePdf"
            :pdf-src="pdfSrc"
            @cerrar="mostradoDialogoReportePdf = false"
        />
    </v-form>
</template>
