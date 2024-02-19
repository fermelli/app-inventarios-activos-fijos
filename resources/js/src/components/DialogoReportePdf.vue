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
        persistent
        width="1400"
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
                <v-btn
                    class="ml-2"
                    color="blue-grey"
                    density="compact"
                    prepend-icon="mdi-close"
                    title="Cerrar"
                    @click="$emit('cerrar')"
                >
                    Cerrar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
