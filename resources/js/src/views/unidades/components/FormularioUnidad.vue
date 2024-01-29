<script>
import formularioMixin from "../../../mixins/formulario.mixin";
import UnidadService from "./../../../services/unidades";
import { useToast } from "vue-toastification";

export default {
    name: "FormularioUnidad",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return "id" in valor && "nombre" in valor;
            },
        },
    },
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            metodoStore: UnidadService.store,
            metodoUpdate: UnidadService.update,
            reglasValidacionNombre: [
                (valor) => !!valor || "El nombre es requerido",
                (valor) =>
                    (valor && valor.length <= 100) ||
                    "El nombre debe tener menos de 100 caracteres",
            ],
        };
    },
};
</script>

<template>
    <v-form
        v-model="formularioValido"
        :loading="guardandoItem"
        @submit.prevent="guardarItem"
    >
        <v-text-field
            v-model="formulario.nombre"
            class="mb-2"
            label="Nombre"
            name="nombre"
            type="text"
            density="compact"
            :rules="reglasValidacionNombre"
            required
            clearable
        />

        <v-btn
            color="primary"
            density="compact"
            prepend-icon="mdi-content-save"
            title="Guardar"
            type="submit"
            :disabled="guardandoItem"
        >
            Guardar
        </v-btn>

        <v-btn
            class="ml-2"
            color="blue-grey"
            density="compact"
            prepend-icon="mdi-close"
            title="Cancelar"
            @click="emitCancelarGuardado"
        >
            Cancelar
        </v-btn>
    </v-form>
</template>
