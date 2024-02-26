<script>
export default {
    name: "TablaAsignacionesActivosFijos",
    props: {
        asignacionesActivosFijos: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <v-table density="compact" height="300">
        <thead>
            <tr>
                <th class="text-left">#</th>
                <th class="text-left">Asignado a</th>
                <th class="text-left">Ubicación</th>
                <th class="text-left">Asignado por</th>
                <th class="text-left">Fecha Asignación</th>
                <th class="text-left">Obs. Asignación</th>
                <th class="text-left">Devuelto a</th>
                <th class="text-left">Fecha Devolución</th>
                <th class="text-left">Obs. Devolución</th>
            </tr>
        </thead>

        <tbody>
            <tr v-if="asignacionesActivosFijos?.length === 0">
                <td colspan="9" class="text-center">
                    No hay asignaciones registradas
                </td>
            </tr>

            <tr
                v-for="(asignacion, indice) in asignacionesActivosFijos"
                :key="indice"
            >
                <td>{{ indice + 1 }}</td>
                <td>
                    {{ asignacion.asignado_a?.nombre }}
                </td>
                <td>
                    {{ asignacion.ubicacion?.nombre }}
                </td>
                <td>
                    {{ asignacion.usuario?.nombre }}
                </td>
                <td>
                    {{ asignacion.fecha_asignacion }}
                </td>
                <td>
                    {{ asignacion.observacion_asignacion }}
                </td>
                <td>
                    {{ asignacion.devuelto_a?.nombre || "-" }}
                </td>
                <td>
                    {{ asignacion.fecha_devolucion || "-" }}
                </td>
                <td>
                    {{ asignacion.observacion_devolucion || "-" }}
                </td>
            </tr>
        </tbody>
    </v-table>
</template>
