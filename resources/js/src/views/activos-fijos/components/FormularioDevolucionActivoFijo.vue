<script>
import AsignacionActivoFijoService from "./../../../services/asignaciones-activos-fijos";
import { useToast } from "vue-toastification";
import formularioMixin from "../../../mixins/formulario.mixin";
import { useDate } from "vuetify";

export default {
    name: "FormularioDevolucionActivoFijo",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "fecha_devolucion" in valor &&
                    "observacion_devolucion" in valor
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
            metodoStore: () => {},
            metodoUpdate: AsignacionActivoFijoService.devolver,
            menu: false,
            fecha: null,
            reglasValidacionFechaDevolucion: [
                (valor) => !!valor || "La fecha de devolución es requerida",
            ],
            reglasValidacionObservacionDevolucion: [
                (valor) =>
                    !!valor || "La observación de devolución es requerida",
                (valor) =>
                    valor.length <= 1000 ||
                    "La observación de devolución debe tener menos de 1000 caracteres",
            ],
        };
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
                <v-menu
                    ref="menu"
                    v-model="menu"
                    v-model:return-value="formulario.fecha_devolucion"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template #activator="{ props }">
                        <v-text-field
                            v-model="formulario.fecha_devolucion"
                            class="mb-2"
                            label="Fecha de Devolución"
                            prepend-inner-icon="mdi-calendar"
                            readonly
                            density="compact"
                            :rules="reglasValidacionFechaDevolucion"
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
                                formulario.fecha_devolucion =
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
                    v-model="formulario.observacion_devolucion"
                    class="mb-2"
                    label="Observación de Devolución"
                    name="observacion_devolucion"
                    density="compact"
                    :rules="reglasValidacionObservacionDevolucion"
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
