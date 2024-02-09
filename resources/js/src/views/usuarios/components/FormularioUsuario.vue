<script>
import formularioMixin from "../../../mixins/formulario.mixin";
import UsuarioService from "./../../../services/usuarios";
import { useToast } from "vue-toastification";

export default {
    name: "FormularioUsuario",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "nombre" in valor &&
                    "correo_electronico" in valor &&
                    "rol" in valor
                );
            },
        },
    },
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            metodoUpdate: UsuarioService.cambiarRol,
            reglasValidacionRol: [(valor) => !!valor || "El rol es requerido"],
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
        <v-select
            v-model="formulario.rol"
            class="mb-2"
            label="Rol"
            name="rol"
            density="compact"
            :items="[
                { title: 'Administrador', value: 'administrador' },
                { title: 'Personal', value: 'personal' },
            ]"
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
