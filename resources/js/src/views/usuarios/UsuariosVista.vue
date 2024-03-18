<script>
import UsuarioService from "./../../services/usuarios";
import { useToast } from "vue-toastification";
import FormularioUsuario from "./components/FormularioUsuario.vue";
import TablaDatosUsuarios from "./components/TablaDatosUsuarios.vue";
import vistaMixin from "../../mixins/vista.mixin";

export default {
    name: "UsuariosVista",
    components: {
        FormularioUsuario,
        TablaDatosUsuarios,
    },
    mixins: [vistaMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Usuario",
        };
    },
    created() {
        this.obtenerUnidades();
    },
    methods: {
        async obtenerUnidades() {
            this.cargandoItems = true;

            try {
                const { data } = await UsuarioService.index({
                    params: { orden_direccion: "desc" },
                });

                this.items = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoItems = false;
            }
        },
        async accionItem(accion) {
            if (!["eliminar", "desactivar", "activar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "eliminar") {
                    await UsuarioService.destroy(this.itemSeleccionado.id);
                } else if (accion === "desactivar") {
                    await UsuarioService.softDestroy(this.itemSeleccionado.id);
                } else if (accion === "activar") {
                    await UsuarioService.restore(this.itemSeleccionado.id);
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerUnidades();
                this.cancelarAccion();
            } catch (error) {
                console.log(error);
            } finally {
                this.realizandoAccion = false;
            }
        },
        crearDatosItem() {
            return {
                id: null,
                nombre: null,
                correo_electronico: null,
                rol: null,
                dependencia_id: null,
                dependencia: null,
            };
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex flex-wrap align-center">
                <h2 class="text-h5">Usuarios</h2>

                <v-btn
                    class="ml-2"
                    color="primary"
                    density="compact"
                    icon="mdi-reload"
                    :loading="cargandoItems"
                    :disabled="cargandoItems"
                    title="Recargar"
                    @click="obtenerUnidades"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosUsuarios
                :items="items"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
            />
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="440">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioUsuario
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerUnidades"
                        @cancelar-guardado="cancelarGuardado"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <DialogoConfirmacion
            v-model="mostradoDialogoConfirmacion"
            :mensaje="mensajeDialogoConfirmacion"
            :realizando-accion="realizandoAccion"
            @aceptar="funcionDialogoConfirmacion"
            @cancelar="cancelarAccion"
        />
    </v-row>
</template>
