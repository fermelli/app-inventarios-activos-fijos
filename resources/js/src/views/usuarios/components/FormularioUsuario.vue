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
            metodoStore: UsuarioService.store,
            metodoUpdate: UsuarioService.update,
            ubicaciones: [],
            cargandoUbicaciones: false,
            reglasValidacionDependenciaId: [
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
            ],
            reglasValidacion: {
                requerido: (valor) => !!valor || "Campo requerido.",
                correoElectronico: (valor) => {
                    let regex =
                        /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
                    return regex.test(valor) || "Correo electrónico inválido.";
                },
            },
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
                    density="compact"
                    required
                    clearable
                    :rules="[reglasValidacion.requerido]"
                />

                <v-text-field
                    v-model="formulario.correo_electronico"
                    class="mb-2"
                    label="Correo Electrónico"
                    name="correo_electronico"
                    type="email"
                    density="compact"
                    required
                    clearable
                    :rules="[
                        reglasValidacion.requerido,
                        reglasValidacion.correoElectronico,
                    ]"
                />

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
                    :rules="[reglasValidacion.requerido]"
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
