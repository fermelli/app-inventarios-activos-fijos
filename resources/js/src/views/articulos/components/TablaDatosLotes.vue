<script>
export default {
    name: "TablaDatosLotes",
    props: {
        lotes: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <v-table density="compact">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha Vencimiento</th>
                <th>Cantidad</th>
            </tr>
        </thead>

        <tbody>
            <tr v-if="!lotes.length">
                <td colspan="3" class="text-center">
                    No hay lotes registrados o no tienen existencias.
                </td>
            </tr>

            <tr v-for="(lote, indice) in lotes" :key="lote.id">
                <td>{{ indice + 1 }}</td>
                <td>{{ lote.fecha_vencimiento || "-" }}</td>
                <td class="text-right">{{ lote.cantidad }}</td>
            </tr>

            <tr>
                <td colspan="2" class="text-right">
                    <strong>Total</strong>
                </td>

                <td class="text-right">
                    <strong>
                        {{
                            lotes
                                .reduce(
                                    (total, lote) =>
                                        total + Number(lote.cantidad),
                                    0,
                                )
                                .toFixed(2)
                        }}
                    </strong>
                </td>
            </tr>
        </tbody>
    </v-table>
</template>
