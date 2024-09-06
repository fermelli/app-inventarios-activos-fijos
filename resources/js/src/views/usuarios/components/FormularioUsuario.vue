<script>
import formularioMixin from "../../../mixins/formulario.mixin";
import UsuarioService from "./../../../services/usuarios";
import { useToast } from "vue-toastification";
import UbicacionService from "./../../../services/ubicaciones";

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
                    "rol" in valor &&
                    "dependencia_id" in valor &&
                    "dependencia" in valor
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
            metodoUpdate: UsuarioService.update,
            ubicaciones: [],
            cargandoUbicaciones: false,
            reglasValidacionRol: [(valor) => !!valor || "El rol es requerido"],
            reglasValidacionDependenciaId: [
                (valor) => !!valor || "La dependencia es requerida",
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
            ],
        };
    },
    created() {
        this.obtenerUbicaciones();
    },
    methods: {
        async obtenerUbicaciones() {
            this.cargandoUbicaciones = true;

            try {
                const { data } = await UbicacionService.index({
                    params: { orden_direccion: "asc" },
                });

                this.ubicaciones = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoUbicaciones = false;
            }
        },
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
                <span class="text-h6">{{ titulo }}</span>
            </v-card-title>

            <v-card-text class="pa-4 pb-0">
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

                <v-autocomplete
                    v-model="formulario.dependencia_id"
                    class="mb-2"
                    :items="ubicaciones"
                    item-value="id"
                    item-title="nombre"
                    label="Dependencia"
                    name="dependencia_id"
                    density="compact"
                    clear-on-select
                    clearable
                    :rules="reglasValidacionDependenciaId"
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
