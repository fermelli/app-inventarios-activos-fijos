<script>
import AutenticacionService from "@/services/autenticacion";

export default {
    name: "RegistrarseVista",
    data() {
        return {
            formularioValido: false,
            enviandoFormulario: false,
            passwordMostrado: false,
            formulario: {
                nombre: "",
                correo_electronico: "",
                password: "",
                password_confirmation: "",
            },
            reglasValidacion: {
                requerido: (valor) => !!valor || "Campo requerido.",
                correoElectronico: (valor) => {
                    let regex =
                        /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
                    return regex.test(valor) || "Correo electrónico inválido.";
                },
                password: (valor) =>
                    (valor && valor.length >= 8) ||
                    "El password debe tener al menos 8 caracteres",
                confirmarPassword: (valor) =>
                    valor === this.formulario.password ||
                    "Las contraseñas no coinciden.",
            },
        };
    },
    methods: {
        async registrarUsuario() {
            if (!this.formularioValido) {
                return;
            }

            this.enviandoFormulario = true;

            try {
                await AutenticacionService.register(this.formulario);

                this.$router.push({ name: "login" });
            } catch (error) {
                console.log(error);
            } finally {
                this.enviandoFormulario = false;
            }
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <v-card class="mx-auto my-4" max-width="440">
                <v-card-title>
                    <span class="text-h6">Registrarse</span>
                </v-card-title>

                <v-card-text>
                    <v-form
                        v-model="formularioValido"
                        autocomplete="off"
                        :disabled="enviandoFormulario"
                        @submit.prevent="registrarUsuario"
                    >
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

                        <v-text-field
                            v-model="formulario.password"
                            class="mb-2"
                            label="Password"
                            name="password"
                            density="compact"
                            :append-icon="
                                passwordMostrado ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="passwordMostrado ? 'text' : 'password'"
                            :rules="[
                                reglasValidacion.requerido,
                                reglasValidacion.password,
                            ]"
                            @click:append="passwordMostrado = !passwordMostrado"
                        />

                        <v-text-field
                            v-model="formulario.password_confirmation"
                            class="mb-2"
                            label="Confirmar Password"
                            name="password_confirmation"
                            :append-icon="
                                passwordMostrado ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="passwordMostrado ? 'text' : 'password'"
                            :rules="[
                                reglasValidacion.requerido,
                                reglasValidacion.confirmarPassword,
                            ]"
                            @click:append="passwordMostrado = !passwordMostrado"
                        />

                        <v-btn
                            color="primary"
                            density="compact"
                            prepend-icon="mdi-account-plus"
                            type="submit"
                            :loading="enviandoFormulario"
                        >
                            Registrarse
                        </v-btn>

                        <v-btn
                            color="primary"
                            density="compact"
                            variant="text"
                            size="small"
                            :to="{ name: 'login' }"
                        >
                            Iniciar sesión
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>
