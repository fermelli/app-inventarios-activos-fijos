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
    },
};
</script>

<template>
    <v-table density="compact" height="400">
        <thead>
            <tr>
                <th class="text-left" style="width: 5%">#</th>
                <th
                    class="text-left"
                    :style="{ width: editable ? '50%' : '55%' }"
                >
                    Artículo
                </th>
                <th class="text-left" style="width: 15%">Stock</th>
                <th class="text-left" style="width: 15%">Unidad</th>
                <th class="text-left" style="width: 15%">Cantidad</th>
                <th v-if="editable" class="text-center" style="width: 5%" />
            </tr>
        </thead>

        <tbody>
            <tr v-if="detallesTransacciones.length === 0">
                <td colspan="6" class="text-center">No hay detalles</td>
            </tr>

            <tr
                v-for="(detalle, indice) in detallesTransacciones"
                :key="indice"
            >
                <td>{{ indice + 1 }}</td>

                <td>{{ detalle.articulo.nombre }}</td>

                <td class="text-right">{{ detalle.articulo.cantidad }}</td>

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
