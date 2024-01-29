<script>
import UnidadService from "./../../../services/unidades";
import { useToast } from "vue-toastification";

export default {
    name: "FormularioUnidad",
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return "id" in valor && "nombre" in valor;
            },
        },
    },
    emits: ["actualizarListado", "cancelarGuardado"],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            formulario: this.datos,
            formularioValido: false,
            guardandoUnidad: false,
            reglasValidacionNombre: [
                (valor) => !!valor || "El nombre es requerido",
                (valor) =>
                    (valor && valor.length <= 100) ||
                    "El nombre debe tener menos de 100 caracteres",
            ],
        };
    },

    methods: {
        async guardarUnidad() {
            if (!this.formularioValido) {
                return;
            }

            this.guardandoUnidad = true;

            try {
                if (this.formulario.id) {
                    await UnidadService.update(
                        this.formulario.id,
                        this.formulario,
                    );
                } else {
                    await UnidadService.store(this.formulario);
                }

                this.toast.success("Unidad guardada exitosamente");
                this.$emit("actualizarListado");
                this.$emit("cancelarGuardado");
            } catch (error) {
                console.log(error);
            } finally {
                this.guardandoUnidad = false;
            }
        },
    },
};
</script>

<template>
    <v-form
        v-model="formularioValido"
        :loading="guardandoUnidad"
        @submit.prevent="guardarUnidad"
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
            :disabled="guardandoUnidad"
        >
            Guardar
        </v-btn>

        <v-btn
            class="ml-2"
            color="blue-grey"
            density="compact"
            prepend-icon="mdi-close"
            title="Cancelar"
            @click="$emit('cancelarGuardado')"
        >
            Cancelar
        </v-btn>
    </v-form>
</template>
