<script>
import { useDate } from "vuetify";

export default {
    name: "TablaDatosDetallesEntradas",
    props: {
        detallesTransacciones: {
            type: Array,
            required: true,
        },
        editable: {
            type: Boolean,
            default: true,
        },
    },
    emits: ["quitarDetalleTransaccion"],
    setup() {
        const date = useDate();

        return { date };
    },
    data() {
        return {
            reglasCantidad: [
                (valor) => !!valor || "La cantidad es requerida",
                (valor) =>
                    !valor || valor > 0 || "La cantidad debe ser mayor a cero",
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
        claseCeldaCantidadLote(detalle) {
            const condicion =
                Number(detalle.cantidad) <= 0 ||
                Number(detalle.cantidad) >
                    Number(detalle.articulo_lote.cantidad);

            return {
                "text-red": condicion,
                "text-success": !condicion,
            };
        },
    },
};
</script>

<template>
    <v-table density="compact" height="400">
        <thead>
            <tr>
                <th class="text-left" style="width: 5%">#</th>
                <th
                    class="text-left"
                    :style="{ width: editable ? '40%' : '30%' }"
                >
                    Artículo
                </th>
                <th class="text-left" style="width: 20%">Unidad</th>
                <th v-if="!editable" class="text-left" style="width: 15%">
                    Cant. Ingresada
                </th>
                <th class="text-left" style="width: 20%">Fecha Vencimiento</th>
                <th class="text-left" style="width: 15%">Cantidad</th>
                <th v-if="editable" class="text-center" style="width: 5%" />
            </tr>
        </thead>

        <tbody>
            <tr v-if="detallesTransacciones.length === 0">
                <td :colspan="editable ? 6 : 7" class="text-center">
                    No hay detalles
                </td>
            </tr>

            <tr
                v-for="(detalle, indice) in detallesTransacciones"
                :key="indice"
                :class="{
                    'text-red': Number(detalle.cantidad) <= 0,
                }"
            >
                <td>{{ indice + 1 }}</td>

                <td>{{ detalle.articulo.nombre }}</td>

                <td>{{ detalle.articulo.unidad.nombre }}</td>

                <template v-if="editable">
                    <td>
                        <v-menu
                            :ref="`menu${indice}`"
                            v-model="detalle.articulo_lote.menu"
                            v-model:return-value="
                                detalle.articulo_lote.fecha_vencimiento
                            "
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template #activator="{ props }">
                                <v-text-field
                                    v-model="
                                        detalle.articulo_lote.fecha_vencimiento
                                    "
                                    variant="outlined"
                                    label="Fecha Vencimiento"
                                    :name="`fecha_vencimiento${indice}`"
                                    readonly
                                    density="compact"
                                    single-line
                                    hide-details
                                    v-bind="props"
                                />
                            </template>

                            <v-date-picker
                                v-model="
                                    detalle.articulo_lote
                                        .fecha_vencimiento_sin_formato
                                "
                                hide-header
                                no-title
                                scrollable
                                show-adjacent-months
                                @update:model-value="
                                    detalle.articulo_lote.fecha_vencimiento =
                                        formatearFecha(
                                            detalle.articulo_lote
                                                .fecha_vencimiento_sin_formato,
                                        )
                                "
                            >
                                <template #actions>
                                    <v-spacer />

                                    <v-btn
                                        text
                                        color="primary"
                                        @click="
                                            detalle.articulo_lote.menu = false
                                        "
                                    >
                                        Cerrar
                                    </v-btn>
                                </template>
                            </v-date-picker>
                        </v-menu>
                    </td>

                    <td>
                        <v-text-field
                            v-model="detalle.cantidad"
                            variant="outlined"
                            label="Cantidad"
                            :name="`cantidad${indice}`"
                            type="number"
                            min="1"
                            step="0.01"
                            density="compact"
                            single-line
                            hide-details
                            clearable
                            required
                            :rules="reglasCantidad"
                        />
                    </td>

                    <td>
                        <v-btn
                            color="error"
                            density="compact"
                            icon="mdi-close"
                            title="Quitar Artículo"
                            @click="$emit('quitarDetalleTransaccion', indice)"
                        />
                    </td>
                </template>

                <template v-else>
                    <td
                        class="text-right"
                        :class="claseCeldaCantidadLote(detalle)"
                    >
                        {{ detalle.articulo_lote.cantidad || "-" }}
                    </td>

                    <td>
                        {{ detalle.articulo_lote.fecha_vencimiento || "-" }}
                    </td>

                    <td class="text-right">{{ detalle.cantidad }}</td>
                </template>
            </tr>
        </tbody>
    </v-table>
</template>
