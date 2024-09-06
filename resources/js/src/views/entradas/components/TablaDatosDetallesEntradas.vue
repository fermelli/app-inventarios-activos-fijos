<script>
import { useDate, useDisplay } from "vuetify";

export default {
    name: "TablaDatosDetallesEntradas",
    props: {
        detallesTransacciones: {
            type: Array,
            required: true,
        },
        editable: {
            type: Boolean,
            default: true,
        },
    },
    emits: ["quitarDetalleTransaccion"],
    setup() {
        const date = useDate();
        const display = useDisplay();

        return { date, display };
    },
    data() {
        return {
            reglasCantidad: [
                (valor) => !!valor || "La cantidad es requerida",
                (valor) =>
                    !valor || valor > 0 || "La cantidad debe ser mayor a cero",
            ],
        };
    },
    computed: {
        headers() {
            const campoCantidadIngresada = !this.editable
                ? [
                      {
                          title: "Cant. Ingresada",
                          value: "articulo_lote.cantidad",
                      },
                  ]
                : [];
            const campoAcciones = this.editable
                ? [{ title: "Acciones", value: "acciones" }]
                : [];

            return [
                { title: "#", value: "nro" },
                { title: "Artículo", value: "articulo.nombre" },
                { title: "Unidad", value: "articulo.unidad.nombre" },
                ...campoCantidadIngresada,
                {
                    title: "F. Venc.",
                    value: "articulo_lote.fecha_vencimiento",
                },
                { title: "Cantidad", value: "cantidad", align: "end" },
                ...campoAcciones,
            ];
        },
    },
    methods: {
        formatearFecha(fecha) {
            if (!fecha) {
                return null;
            }

            const fechaFormateada = this.date.format(fecha, "keyboardDate");
            const [dia, mes, anio] = fechaFormateada.split("/");

            return `${anio}-${mes}-${dia}`;
        },
        claseCeldaCantidadLote(detalle) {
            const condicion =
                Number(detalle.cantidad) <= 0 ||
                Number(detalle.cantidad) >
                    Number(detalle.articulo_lote.cantidad);

            return {
                "text-red": condicion,
                "text-success": !condicion,
            };
        },
    },
};
</script>

<template>
    <v-data-table
        density="compact"
        :headers="headers"
        :items="detallesTransacciones"
        :hide-default-header="display.mobile.value"
        hide-default-footer
        :mobile="null"
    >
        <template #[`item.nro`]="{ index }">
            {{ index + 1 }}
        </template>

        <template #[`item.articulo.nombre`]="{ value }">
            <div style="min-width: 175px">
                {{ value }}
            </div>
        </template>

        <template #[`item.articulo.unidad.nombre`]="{ value }">
            <div style="min-width: 175px">
                {{ value }}
            </div>
        </template>

        <template #[`item.articulo_lote.cantidad`]="{ item, value }">
            <div style="min-width: 175px">
                <span class="text-right" :class="claseCeldaCantidadLote(item)">
                    {{ value || "-" }}
                </span>
            </div>
        </template>

        <template
            #[`item.articulo_lote.fecha_vencimiento`]="{ value, item, index }"
        >
            <div style="min-width: 175px">
                <v-menu
                    v-if="editable"
                    :ref="`menu${index}`"
                    v-model="item.articulo_lote.menu"
                    v-model:return-value="item.articulo_lote.fecha_vencimiento"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                >
                    <template #activator="{ props }">
                        <v-text-field
                            v-model="item.articulo_lote.fecha_vencimiento"
                            class="my-1"
                            variant="outlined"
                            label="Fecha Vencimiento"
                            :name="`fecha_vencimiento${index}`"
                            readonly
                            density="compact"
                            single-line
                            hide-details
                            v-bind="props"
                            clearable
                        />
                    </template>

                    <v-date-picker
                        v-model="
                            item.articulo_lote.fecha_vencimiento_sin_formato
                        "
                        hide-header
                        no-title
                        scrollable
                        show-adjacent-months
                        @update:model-value="
                            ($event) => {
                                item.articulo_lote.fecha_vencimiento =
                                    formatearFecha(
                                        item.articulo_lote
                                            .fecha_vencimiento_sin_formato,
                                    );
                                item.articulo_lote.menu = false;
                            }
                        "
                    >
                        <template #actions>
                            <v-spacer />

                            <v-btn
                                text
                                color="primary"
                                @click="item.articulo_lote.menu = false"
                            >
                                Cerrar
                            </v-btn>
                        </template>
                    </v-date-picker>
                </v-menu>

                <div v-else>
                    {{ value || "-" }}
                </div>
            </div>
        </template>

        <template #[`item.cantidad`]="{ value, item, index }">
            <div style="min-width: 175px">
                <v-text-field
                    v-if="editable"
                    v-model="item.cantidad"
                    class="my-1"
                    variant="outlined"
                    label="Cantidad"
                    :name="`cantidad${index}`"
                    type="number"
                    min="1"
                    step="0.01"
                    density="compact"
                    single-line
                    hide-details
                    clearable
                    required
                    :rules="reglasCantidad"
                />

                <div v-else class="text-right">
                    {{ value }}
                </div>
            </div>
        </template>

        <template #[`item.acciones`]="{ index }">
            <v-btn
                class="my-1"
                color="error"
                density="compact"
                icon="mdi-close"
                title="Quitar Artículo"
                @click="$emit('quitarDetalleTransaccion', index)"
            />
        </template>
    </v-data-table>
</template>
