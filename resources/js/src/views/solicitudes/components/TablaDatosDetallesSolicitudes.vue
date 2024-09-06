<script>
import { useDisplay } from "vuetify";

export default {
    name: "TablaDatosDetallesSolicitudes",
    props: {
        detallesTransacciones: {
            type: Array,
            required: true,
        },
        editable: {
            type: Boolean,
            default: true,
        },
        mostradoStock: {
            type: Boolean,
            default: true,
        },
    },
    emits: ["quitarDetalleTransaccion"],
    setup() {
        const display = useDisplay();

        return { display };
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
        estilosColumnaNombreArticulo() {
            return this.mostradoStock
                ? { width: this.editable ? "50%" : "55%" }
                : { width: this.editable ? "65%" : "70%" };
        },
        headers() {
            const campoStock = this.mostradoStock
                ? [{ title: "Stock", value: "articulo.cantidad" }]
                : [];
            const campoAcciones = this.editable
                ? [{ title: "Acciones", value: "acciones" }]
                : [];

            return [
                { title: "#", value: "nro" },
                { title: "Artículo", value: "articulo.nombre" },
                ...campoStock,
                { title: "Unidad", value: "articulo.unidad.nombre" },
                { title: "Cantidad", value: "cantidad", align: "end" },
                ...campoAcciones,
            ];
        },
    },
    methods: {
        reglaCantidadMaxima(indice) {
            return (valor) =>
                !valor ||
                valor <=
                    Number(
                        this.detallesTransacciones[indice].articulo.cantidad,
                    ) ||
                "La cantidad no debe ser mayor al stock";
        },
        claseFilaDetalleTransaccion(detalle) {
            return {
                "text-red":
                    (detalle.cantidad <= 0 ||
                        detalle.cantidad > Number(detalle.articulo.cantidad)) &&
                    this.mostradoStock,
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

        <template #[`item.articulo.cantidad`]="{ value }">
            <div class="text-right" style="min-width: 175px">
                {{ value }}
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
                    :rules="[...reglasCantidad, reglaCantidadMaxima(index)]"
                />

                <div v-else class="text-right">
                    {{ value }}
                </div>
            </div>
        </template>

        <template #[`item.articulo.unidad.nombre`]="{ value }">
            <div style="min-width: 175px">
                {{ value }}
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
