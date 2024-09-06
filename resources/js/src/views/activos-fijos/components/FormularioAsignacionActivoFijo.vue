<script>
import AsignacionActivoFijoService from "./../../../services/asignaciones-activos-fijos";
import { useToast } from "vue-toastification";
import formularioMixin from "../../../mixins/formulario.mixin";
import UsuarioService from "./../../../services/usuarios";
import UbicacionService from "./../../../services/ubicaciones";
import { useDate } from "vuetify";

export default {
    name: "FormularioAsignacionActivoFijo",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "activo_fijo_id" in valor &&
                    "asignado_a_id" in valor &&
                    "ubicacion_id" in valor &&
                    "fecha_asignacion" in valor &&
                    "observacion_asignacion" in valor
                );
            },
        },
    },
    setup() {
        const toast = useToast();
        const date = useDate();

        return { toast, date };
    },
    data() {
        return {
            metodoStore: AsignacionActivoFijoService.store,
            metodoUpdate: () => {},
            asignados: [],
            cargandoAsignados: false,
            ubicaciones: [],
            cargandoUbicaciones: false,
            menu: false,
            fecha: null,
            reglasValidacionAsignadoA: [
                (valor) => !!valor || "El asignado a es requerido",
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
            ],
            reglasValidacionUbicacionId: [
                (valor) => !!valor || "La ubicación es requerida",
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
            ],
            reglasValidacionFechaAsignacion: [
                (valor) => !!valor || "La fecha de asignación es requerida",
            ],
            reglasValidacionObservacionAsignacion: [
                (valor) =>
                    !!valor || "La observación de asignación es requerida",
                (valor) =>
                    valor.length <= 1000 ||
                    "La observación de asignación debe tener menos de 1000 caracteres",
            ],
        };
    },
    created() {
        this.obtenerAsignados();
        this.obtenerUbicaciones();
    },
    methods: {
        formatearFecha(fecha) {
            if (!fecha) {
                return null;
            }

            const fechaFormateada = this.date.format(fecha, "keyboardDate");
            const [dia, mes, anio] = fechaFormateada.split("/");

            return `${anio}-${mes}-${dia}`;
        },
        async obtenerAsignados() {
            this.cargandoAsignados = true;

            try {
                const { data } = await UsuarioService.index({
                    params: { orden_direccion: "asc" },
                });

                this.asignados = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoAsignados = false;
            }
        },
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
                <v-autocomplete
                    v-model="formulario.asignado_a_id"
                    class="mb-2"
                    :items="asignados"
                    item-value="id"
                    item-title="nombre"
                    label="Asignado a"
                    name="asignado_a_id"
                    density="compact"
                    clear-on-select
                    clearable
                    :rules="reglasValidacionAsignadoA"
                />

                <v-autocomplete
                    v-model="formulario.ubicacion_id"
                    class="mb-2"
                    :items="ubicaciones"
                    item-value="id"
                    item-title="nombre"
                    label="Ubicación"
                    name="ubicacion_id"
                    density="compact"
                    clear-on-select
                    clearable
                    :rules="reglasValidacionUbicacionId"
                />

                <v-menu
                    ref="menu"
                    v-model="menu"
                    v-model:return-value="formulario.fecha_asignacion"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template #activator="{ props }">
                        <v-text-field
                            v-model="formulario.fecha_asignacion"
                            class="mb-2"
                            label="Fecha de Asignación"
                            prepend-inner-icon="mdi-calendar"
                            readonly
                            density="compact"
                            :rules="reglasValidacionFechaAsignacion"
                            v-bind="props"
                            clearable
                        />
                    </template>

                    <v-date-picker
                        v-model="fecha"
                        hide-header
                        no-title
                        scrollable
                        show-adjacent-months
                        @update:model-value="
                            ($event) => {
                                formulario.fecha_asignacion =
                                    formatearFecha($event);
                                menu = false;
                            }
                        "
                    >
                        <template #actions>
                            <v-spacer />

                            <v-btn text color="primary" @click="menu = false">
                                Cerrar
                            </v-btn>
                        </template>
                    </v-date-picker>
                </v-menu>

                <v-textarea
                    v-model="formulario.observacion_asignacion"
                    class="mb-2"
                    label="Observación de Asignación"
                    name="observacion_asignacion"
                    density="compact"
                    :rules="reglasValidacionObservacionAsignacion"
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
