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
};
</script>

<template>
    <v-table density="compact" height="400">
        <thead>
            <tr>
                <th class="text-left" style="width: 5%">#</th>
                <th
                    class="text-left"
                    :style="{ width: editable ? '65%' : '70%' }"
                >
                    Artículo
                </th>
                <th class="text-left" style="width: 15%">Unidad</th>
                <th class="text-left" style="width: 15%">Cantidad</th>
                <th v-if="editable" class="text-center" style="width: 5%" />
            </tr>
        </thead>

        <tbody>
            <tr v-if="detallesTransacciones.length === 0">
                <td colspan="5" class="text-center">No hay detalles</td>
            </tr>

            <tr
                v-for="(detalle, indice) in detallesTransacciones"
                :key="indice"
            >
                <td>{{ indice + 1 }}</td>

                <td>{{ detalle.articulo.nombre }}</td>

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
