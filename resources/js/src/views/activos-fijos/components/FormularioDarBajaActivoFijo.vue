<script>
import ActivoFijoService from "./../../../services/activos-fijos";
import { useToast } from "vue-toastification";
import formularioMixin from "../../../mixins/formulario.mixin";
import { useDate } from "vuetify";

export default {
    name: "FormularioDarBajaActivoFijo",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "fecha_baja" in valor &&
                    "razon_baja" in valor
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
            metodoUpdate: ActivoFijoService.darBaja,
            menu: false,
            fecha: null,
            reglasValidacionFechaBaja: [
                (valor) => !!valor || "La fecha de baja es requerida",
            ],
            reglasValidacionRazonBaja: [
                (valor) => !!valor || "La razón de baja es requerida",
                (valor) =>
                    valor.length <= 1000 ||
                    "La razón de baja debe tener menos de 1000 caracteres",
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
        <v-menu
            ref="menu"
            v-model="menu"
            v-model:return-value="formulario.fecha_baja"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="auto"
        >
            <template #activator="{ props }">
                <v-text-field
                    v-model="formulario.fecha_baja"
                    class="mb-2"
                    label="Fecha de Baja"
                    prepend-icon="mdi-calendar"
                    readonly
                    density="compact"
                    :rules="reglasValidacionFechaBaja"
                    v-bind="props"
                />
            </template>

            <v-date-picker
                v-model="fecha"
                hide-header
                no-title
                scrollable
                show-adjacent-months
                @update:model-value="
                    formulario.fecha_baja = formatearFecha(fecha)
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
            v-model="formulario.razon_baja"
            class="mb-2"
            label="Razón de Baja"
            name="razon_baja"
            density="compact"
            :rules="reglasValidacionRazonBaja"
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
