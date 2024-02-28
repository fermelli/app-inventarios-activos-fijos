<script>
import ArticuloService from "./../../../services/articulos";
import { useToast } from "vue-toastification";
import formularioMixin from "../../../mixins/formulario.mixin";

export default {
    name: "FormularioImportarArticulos",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return "archivos" in valor;
            },
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
                const response = await ArticuloService.importar(formData);
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
            class="mb-2"
            label="Archivo (XLSX)"
            name="archivo"
            accept=".xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            prepend-icon="mdi-microsoft-excel"
            density="compact"
            :rules="reglasValidacionArchivo"
            required
            clearable
        />

        <v-btn
            color="primary"
            density="compact"
            prepend-icon="mdi-file-upload"
            title="Subir Archivo"
            type="submit"
            :loading="guardandoItem"
            :disabled="guardandoItem"
        >
            Subir Archivo
        </v-btn>

        <v-btn
            class="ml-2"
            color="blue-grey"
            density="compact"
            prepend-icon="mdi-close"
            title="Cancelar"
            :disabled="guardandoItem"
            @click="emitCancelarGuardado"
        >
            Cancelar
        </v-btn>
    </v-form>
</template>
