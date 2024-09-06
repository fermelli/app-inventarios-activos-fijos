<script>
import InstitucionService from "./../../../services/instituciones";
import { useToast } from "vue-toastification";
import formularioMixin from "../../../mixins/formulario.mixin";

export default {
    name: "FormularioInstitucion",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "nombre" in valor &&
                    "direccion" in valor &&
                    "telefono" in valor
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
            metodoStore: InstitucionService.store,
            metodoUpdate: InstitucionService.update,
            reglasValidacionNombre: [
                (valor) => !!valor || "El nombre es requerido",
                (valor) =>
                    (valor && valor.length <= 100) ||
                    "El nombre debe tener menos de 100 caracteres",
            ],
            reglasValidacionDireccion: [
                (valor) =>
                    !valor ||
                    (valor && valor.length <= 255) ||
                    "La dirección debe tener menos de 255 caracteres",
            ],
            reglasValidacionTelefono: [
                (valor) =>
                    !valor ||
                    (valor && valor.length <= 20) ||
                    "El teléfono debe tener menos de 20 caracteres",
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
        <v-card>
            <v-card-title>
                <div
                    class="d-flex flex-wrap justify-space-between align-center"
                >
                    <span class="text-h6">{{ titulo }}</span>

                    <v-btn
                        class="my-1"
                        color="blue-grey"
                        density="compact"
                        variant="text"
                        icon="mdi-close"
                        title="Cerrar"
                        @click="emitCancelarGuardado"
                    />
                </div>
            </v-card-title>

            <v-card-text class="pa-4 pb-0">
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

                <v-text-field
                    v-model="formulario.direccion"
                    class="mb-2"
                    label="Dirección"
                    name="direccion"
                    type="text"
                    density="compact"
                    :rules="reglasValidacionDireccion"
                    clearable
                />

                <v-text-field
                    v-model="formulario.telefono"
                    class="mb-2"
                    label="Teléfono"
                    name="telefono"
                    type="text"
                    density="compact"
                    :rules="reglasValidacionTelefono"
                    clearable
                />
            </v-card-text>

            <v-card-actions>
                <div
                    class="d-flex flex-wrap justify-space-between align-center"
                >
                    <v-btn
                        class="ma-1"
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
                        class="ma-1"
                        color="blue-grey"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cancelar"
                        @click="emitCancelarGuardado"
                    >
                        Cancelar
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-form>
</template>
