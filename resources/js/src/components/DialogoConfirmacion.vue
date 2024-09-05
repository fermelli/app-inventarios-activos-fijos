<script>
export default {
    name: "DialogoConfirmacion",
    props: {
        modelValue: {
            type: Boolean,
            required: true,
        },
        mensaje: {
            type: [String, null],
            required: true,
        },
        realizandoAccion: {
            type: Boolean,
            required: true,
        },
    },
    emits: ["aceptar", "cancelar"],
};
</script>

<template>
    <v-dialog
        :model-value="modelValue"
        width="440"
        persistent
        scrollable
        @update:model-value="value = $event"
    >
        <v-card>
            <v-card-title>
                <span class="text-h6">Confirmación de acción</span>
            </v-card-title>

            <v-card-text class="pa-4">
                <!-- eslint-disable-next-line vue/no-v-html -->
                <p v-html="mensaje" />
            </v-card-text>

            <v-card-actions>
                <div
                    class="d-flex flex-wrap justify-space-between align-center"
                >
                    <v-btn
                        class="ma-1"
                        color="primary"
                        density="compact"
                        prepend-icon="mdi-check"
                        title="Aceptar"
                        :loading="realizandoAccion"
                        :disabled="realizandoAccion"
                        @click="$emit('aceptar')"
                    >
                        Aceptar
                    </v-btn>

                    <v-btn
                        class="ma-1"
                        color="blue-grey"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cancelar"
                        :disabled="realizandoAccion"
                        @click="$emit('cancelar')"
                    >
                        Cancelar
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
