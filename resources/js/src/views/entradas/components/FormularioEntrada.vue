<script>
import EntradaArticuloService from "./../../../services/entradas-articulos";
import InstitucionService from "./../../../services/instituciones";
import { useToast } from "vue-toastification";
import formularioMixin from "../../../mixins/formulario.mixin";
import TablaDatosServidorArticulos from "../../articulos/components/TablaDatosServidorArticulos.vue";
import vistaMixin from "../../../mixins/vista.mixin";
import ArticuloService from "./../../../services/articulos";
import { useDate } from "vuetify";
import TablaDatosDetallesEntradas from "./TablaDatosDetallesEntradas.vue";

export default {
    name: "FormularioEntradas",
    components: {
        TablaDatosServidorArticulos,
        TablaDatosDetallesEntradas,
    },
    mixins: [formularioMixin, vistaMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "institucion_id" in valor &&
                    "fecha" in valor &&
                    "comprobante" in valor &&
                    "numero_comprobante" in valor &&
                    "observacion" in valor &&
                    "detalles_transacciones" in valor
                );
            },
        },
    },
    setup() {
        const toast = useToast();
        const date = useDate();

        return { toast, date };
    },
    data() {
        return {
            metodoStore: EntradaArticuloService.store,
            metodoUpdate: EntradaArticuloService.update,
            instituciones: [],
            cargandoInsituciones: false,
            menu: false,
            mostradoDialogoTablaArticulos: false,
            pagina: 1,
            itemsPorPagina: parseInt(
                localStorage.getItem(`itemsPorPagina-${this.$route.name}`) ||
                    10,
            ),
            busqueda: null,
            totalItems: 0,
            fecha: null,
            reglasValidacionFecha: [
                (valor) => !!valor || "La fecha es requerida",
            ],
            reglasValidacionInstitucionId: [
                (valor) => !!valor || "La institución es requerida",
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
            ],
            reglasValidacionComprobante: [
                (valor) => !!valor || "El comprobante es requerido",
                (valor) =>
                    ["factura", "recibo", "nota", "boleta", "otro"].includes(
                        valor,
                    ) || "El comprobante no es válido",
            ],
            reglasValidacionNumeroComprobante: [
                (valor) => !!valor || "El nombre es requerido",
                (valor) =>
                    (valor && valor.length <= 100) ||
                    "El nombre debe tener menos de 100 caracteres",
            ],
            reglasValidacionObservacion: [
                (valor) =>
                    !valor ||
                    valor.length <= 255 ||
                    "La observación debe tener menos de 255 caracteres",
            ],
        };
    },
    created() {
        this.obtenerInstituciones();
    },
    methods: {
        async obtenerInstituciones() {
            this.cargandoInsituciones = true;

            try {
                const { data } = await InstitucionService.index({
                    params: { orden_direccion: "asc" },
                });

                this.instituciones = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoInsituciones = false;
            }
        },
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
                    articulo_lote: {
                        lote: null,
                        fecha_vencimiento: null,
                        fecha_vencimiento_sin_formato: null,
                        menu: false,
                    },
                });
            }

            this.toast.success("Artículo agregado");
        },
        formatearFecha(fecha) {
            if (!fecha) {
                return null;
            }

            const fechaFormateada = this.date.format(fecha, "keyboardDate");
            const [dia, mes, anio] = fechaFormateada.split("/");

            return `${anio}-${mes}-${dia}`;
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
            <v-col cols="12" lg="4" class="py-0">
                <v-menu
                    ref="menu"
                    v-model="menu"
                    v-model:return-value="formulario.fecha"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template #activator="{ props }">
                        <v-text-field
                            v-model="formulario.fecha"
                            class="mb-2"
                            label="Fecha"
                            prepend-icon="mdi-calendar"
                            readonly
                            density="compact"
                            :rules="reglasValidacionFecha"
                            v-bind="props"
                        />
                    </template>

                    <v-date-picker
                        v-model="fecha"
                        hide-header
                        no-title
                        scrollable
                        show-adjacent-months
                        @update:model-value="
                            formulario.fecha = formatearFecha(fecha)
                        "
                    >
                        <template #actions>
                            <v-spacer />

                            <v-btn text color="primary" @click="menu = false">
                                Cerrar
                            </v-btn>
                        </template>
                    </v-date-picker>
                </v-menu>
            </v-col>

            <v-col cols="12" lg="8" class="py-0">
                <v-autocomplete
                    v-model="formulario.institucion_id"
                    class="mb-2"
                    :items="instituciones"
                    item-value="id"
                    item-title="nombre"
                    label="Institución"
                    name="institucion_id"
                    density="compact"
                    clear-on-select
                    clearable
                    :rules="reglasValidacionInstitucionId"
                />
            </v-col>

            <v-col cols="12" lg="4" class="py-0">
                <v-select
                    v-model="formulario.comprobante"
                    class="mb-2"
                    label="Comprobante"
                    name="comprobante"
                    density="compact"
                    :items="[
                        { title: 'Factura', value: 'factura' },
                        { title: 'Recibo', value: 'recibo' },
                        { title: 'Nota', value: 'nota' },
                        { title: 'Boleta', value: 'boleta' },
                        { title: 'Otro', value: 'otro' },
                    ]"
                    :rules="reglasValidacionComprobante"
                    required
                    clearable
                />
            </v-col>

            <v-col cols="12" lg="4" class="py-0">
                <v-text-field
                    v-model="formulario.numero_comprobante"
                    class="mb-2"
                    label="N° Comprobante"
                    name="numero_comprobante"
                    type="text"
                    density="compact"
                    :rules="reglasValidacionNumeroComprobante"
                    required
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

            <v-col cols="12">
                <TablaDatosDetallesEntradas
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
