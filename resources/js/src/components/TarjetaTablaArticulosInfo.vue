<script>
export default {
    name: "TarjetaTablaArticulosInfo",
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (value) => {
                return (
                    "titulo" in value &&
                    "icono" in value &&
                    "colorIcono" in value &&
                    "items" in value &&
                    "cabeceras" in value &&
                    "cargando" in value
                );
            },
        },
    },
};
</script>

<template>
    <v-card elevation="4">
        <v-card-text>
            <h2 class="text-h6 text-center mb-4">
                <v-icon :color="datos.colorIcono">
                    {{ datos.icono }}
                </v-icon>
                {{ datos.titulo }}
            </h2>

            <v-data-table-virtual
                :headers="datos.cabeceras"
                :items="datos.items"
                item-value="codigo"
                density="compact"
                :show-current-page="false"
                :loading="datos.cargando"
                height="400"
            >
                <template #[`item.index`]="{ index }">
                    {{ index + 1 }}
                </template>
            </v-data-table-virtual>
        </v-card-text>
    </v-card>
</template>
