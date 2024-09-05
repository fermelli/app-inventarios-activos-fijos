<script>
export default {
    name: "DialogoReportePdf",
    props: {
        modelValue: {
            type: Boolean,
            required: true,
        },
        pdfSrc: {
            type: String,
            default: "",
        },
    },
    emits: ["cerrar"],
};
</script>

<template>
    <v-dialog
        :model-value="modelValue"
        width="1400"
        persistent
        scrollable
        @update:model-value="value = $event"
    >
        <v-card>
            <v-card-text class="pa-4">
                <iframe
                    :src="pdfSrc"
                    width="100%"
                    height="650"
                    type="application/pdf"
                    download="reporte.pdf"
                />
            </v-card-text>

            <v-card-actions>
                <div
                    class="d-flex flex-wrap justify-space-between align-center"
                >
                    <v-btn
                        class="ma-1"
                        color="blue-grey"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cerrar"
                        @click="$emit('cerrar')"
                    >
                        Cerrar
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
