<script>
import { useDisplay } from "vuetify";

export default {
    name: "TablaAsignacionesActivosFijos",
    props: {
        asignacionesActivosFijos: {
            type: Array,
            required: true,
        },
    },
    setup() {
        const display = useDisplay();

        return { display };
    },
    data() {
        return {
            headers: [
                { title: "#", value: "nro" },
                { title: "Asignado a", value: "asignado_a.nombre" },
                { title: "Ubicación", value: "ubicacion.nombre" },
                { title: "Asignado por", value: "usuario.nombre" },
                { title: "Fecha Asignación", value: "fecha_asignacion" },
                { title: "Obs. Asignación", value: "observacion_asignacion" },
                { title: "Devuelto a", value: "devuelto_a.nombre" },
                { title: "Fecha Devolución", value: "fecha_devolucion" },
                { title: "Obs. Devolución", value: "observacion_devolucion" },
            ],
        };
    },
};
</script>

<template>
    <v-data-table
        density="compact"
        :headers="headers"
        :items="asignacionesActivosFijos"
        :hide-default-header="display.mobile.value"
        hide-default-footer
        :mobile="null"
    >
        <template #[`item.nro`]="{ index }">
            {{ index + 1 }}
        </template>

        <template #[`item.devuelto_a.nombre`]="{ value }">
            {{ value || "-" }}
        </template>

        <template #[`item.fecha_devolucion`]="{ value }">
            {{ value || "-" }}
        </template>

        <template #[`item.observacion_devolucion`]="{ value }">
            {{ value || "-" }}
        </template>
    </v-data-table>
</template>
