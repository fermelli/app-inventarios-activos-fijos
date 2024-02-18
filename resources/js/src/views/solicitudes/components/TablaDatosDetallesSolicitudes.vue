<script>
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
    <v-table density="compact" height="400">
        <thead>
            <tr>
                <th class="text-left" style="width: 5%">#</th>
                <th class="text-left" :style="estilosColumnaNombreArticulo">
                    Artículo
                </th>
                <th v-if="mostradoStock" class="text-left" style="width: 15%">
                    Stock
                </th>
                <th class="text-left" style="width: 15%">Unidad</th>
                <th class="text-left" style="width: 15%">Cantidad</th>
                <th v-if="editable" class="text-center" style="width: 5%" />
            </tr>
        </thead>

        <tbody>
            <tr v-if="detallesTransacciones.length === 0">
                <td :colspan="mostradoStock ? 6 : 5" class="text-center">
                    No hay detalles
                </td>
            </tr>

            <tr
                v-for="(detalle, indice) in detallesTransacciones"
                :key="indice"
                :class="claseFilaDetalleTransaccion(detalle)"
            >
                <td>{{ indice + 1 }}</td>

                <td>{{ detalle.articulo.nombre }}</td>

                <td v-if="mostradoStock" class="text-right">
                    {{ detalle.articulo.cantidad }}
                </td>

                <td>{{ detalle.articulo.unidad.nombre }}</td>

                <template v-if="editable">
                    <td>
                        <v-text-field
                            v-model="detalle.cantidad"
                            variant="outlined"
                            label="Cantidad"
                            :name="`cantidad${indice}`"
                            type="number"
                            min="1"
                            step="0.01"
                            density="compact"
                            single-line
                            hide-details
                            clearable
                            required
                            :rules="[
                                ...reglasCantidad,
                                reglaCantidadMaxima(indice),
                            ]"
                        />
                    </td>

                    <td>
                        <v-btn
                            color="error"
                            density="compact"
                            icon="mdi-close"
                            title="Quitar Artículo"
                            @click="$emit('quitarDetalleTransaccion', indice)"
                        />
                    </td>
                </template>

                <template v-else>
                    <td class="text-right">{{ detalle.cantidad }}</td>
                </template>
            </tr>
        </tbody>
    </v-table>
</template>
