<script>
import { useToast } from "vue-toastification";
import formularioMixin from "../mixins/formulario.mixin";

export default {
    name: "FormularioImportar",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return "archivos" in valor;
            },
        },
        metodoImportar: {
            type: Function,
            required: true,
        },
    },
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            metodoStore: () => {},
            metodoUpdate: () => {},
            reglasValidacionArchivo: [
                (valor) =>
                    (!!valor && valor.length > 0) || "El archivo es requerido",
                (valor) =>
                    !valor ||
                    !valor.length ||
                    valor[0].name.endsWith(".xlsx") ||
                    valor[0].name.endsWith(".xls") ||
                    "El archivo debe ser un XLSX o XLS",
            ],
        };
    },
    methods: {
        async subirArchivo() {
            if (!this.formularioValido) {
                this.toast.error("Corrija los errores en el formulario");

                return;
            }

            this.guardandoItem = true;
            const formData = new FormData();
            formData.append("archivo", this.formulario.archivos[0]);

            try {
                const response = await this.metodoImportar(formData);
                const data = response.data;
                const mensaje =
                    data?.mensaje || "El archivo se ha subido correctamente";

                this.toast.success(mensaje);
                this.emitActualizarListado();
                this.emitCancelarGuardado();
            } catch (error) {
                console.log(error);
            } finally {
                this.guardandoItem = false;
            }
        },
    },
};
</script>

<template>
    <v-form
        v-model="formularioValido"
        :loading="guardandoItem"
        @submit.prevent="subirArchivo"
    >
        <v-file-input
            v-model="formulario.archivos"
            label="Archivo (XLSX)"
            name="archivo"
            accept=".xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            prepend-icon="mdi-microsoft-excel"
            density="compact"
            :rules="reglasValidacionArchivo"
            required
            clearable
        />

        <div class="d-flex flex-wrap justify-space-between align-center">
            <v-btn
                class="ma-1"
                color="primary"
                density="compact"
                prepend-icon="mdi-file-upload"
                title="Subir Archivo"
                type="submit"
                :loading="guardandoItem"
                :disabled="guardandoItem"
            >
                Subir
            </v-btn>

            <v-btn
                class="ma-1"
                color="blue-grey"
                density="compact"
                prepend-icon="mdi-close"
                title="Cancelar"
                :disabled="guardandoItem"
                @click="emitCancelarGuardado"
            >
                Cancelar
            </v-btn>
        </div>
    </v-form>
</template>
