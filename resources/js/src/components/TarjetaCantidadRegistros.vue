<script>
export default {
    name: "TarjetaCantidadRegistros",
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (value) => {
                return (
                    "cantidad" in value &&
                    "titulo" in value &&
                    "colorIcono" in value &&
                    "icono" in value
                );
            },
        },
    },
};
</script>

<template>
    <v-card elevation="4">
        <v-card-text>
            <v-row>
                <v-col cols="auto">
                    <div class="h-100 d-flex justify-center align-center">
                        <span class="text-h2">
                            {{ datos.cantidad }}
                        </span>
                    </div>
                </v-col>

                <v-col>
                    <div class="text-h6 text-center">{{ datos.titulo }}</div>

                    <v-icon
                        class="d-block mx-auto"
                        :color="datos.colorIcono"
                        :icon="datos.icono"
                        size="64"
                    />
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
