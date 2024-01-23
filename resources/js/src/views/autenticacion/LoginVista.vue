<script>
import AutenticacionService from "@/services/autenticacion";

export default {
    name: "LoginVista",
    data() {
        return {
            enviandoFormulario: false,
            passwordMostrado: false,
            formulario: {
                correo_electronico: "omartaboada1990@hotmail.com",
                password: "Password123$",
            },
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
    methods: {
        async loguearUsuario() {
            this.enviandoFormulario = true;

            try {
                await AutenticacionService.login(this.formulario);

                const usuarioAutenticado = await this.$store.dispatch(
                    "autenticacion/obtenerUsuarioAutenticado",
                );

                if (usuarioAutenticado) {
                    this.$router.push({ name: "inicio" });
                }
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
                    <span class="text-h6">Iniciar Sesión</span>
                </v-card-title>

                <v-card-text>
                    <v-form
                        autocomplete="off"
                        :disabled="enviandoFormulario"
                        @submit.prevent="loguearUsuario"
                    >
                        <v-text-field
                            v-model="formulario.correo_electronico"
                            class="mb-2"
                            label="Correo electrónico"
                            name="correo_electronico"
                            type="email"
                            density="compact"
                            :rules="[
                                reglasValidacion.requerido,
                                reglasValidacion.correoElectronico,
                            ]"
                            required
                            clearable
                        />

                        <v-text-field
                            v-model="formulario.password"
                            class="mb-2"
                            label="Password"
                            name="password"
                            :type="passwordMostrado ? 'text' : 'password'"
                            :append-inner-icon="
                                passwordMostrado ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            density="compact"
                            :rules="[reglasValidacion.requerido]"
                            required
                            clearable
                            @click:append-inner="
                                passwordMostrado = !passwordMostrado
                            "
                        />

                        <v-btn
                            color="primary"
                            density="compact"
                            prepend-icon="mdi-lock"
                            type="submit"
                            :loading="enviandoFormulario"
                        >
                            Ingresar
                        </v-btn>

                        <v-divider class="my-4" />

                        <v-responsive class="text-center">
                            <v-btn
                                color="primary"
                                density="compact"
                                variant="text"
                                size="small"
                                :to="{ name: 'inicio' }"
                            >
                                Ir a la página principal
                            </v-btn>
                        </v-responsive>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>
