<script>
import formularioMixin from "../../../mixins/formulario.mixin";
import ActivosFijosService from "../../../services/activos-fijos";
import exportarArticulosMixin from "../../../mixins/exportar-articulos.mixin";
import { useToast } from "vue-toastification";

export default {
    name: "FormularioGeneracionQr",
    mixins: [formularioMixin, exportarArticulosMixin],
    props: {
        datos: {
            type: [Object, null],
            required: true,
            validator: (valor) => {
                if (valor === null) {
                    return true;
                }

                return "id" in valor;
            },
        },
        paramsMultiple: {
            type: Object,
            default: () => ({}),
        },
        multiple: {
            type: Boolean,
            default: false,
        },
    },
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            maximoFilas: 10,
            maximoColumnas: 4,
            params: {
                fila: 1,
                columna: 1,
                cantidad: 1,
            },
            metodoServicioGenerarEtiquetaPdf:
                ActivosFijosService.generarEtiquetaPdf,
            metodoServicioGenerarEtiquetasPdf:
                ActivosFijosService.generarEtiquetasPdf,
        };
    },
    computed: {
        numeroCeldaSeleccionada() {
            return Number(
                (this.params.fila - 1) * this.maximoColumnas +
                    this.params.columna,
            );
        },
        cantidadMaxima() {
            return (
                Number(this.maximoFilas * this.maximoColumnas) -
                this.numeroCeldaSeleccionada +
                1
            );
        },
    },
    methods: {
        validarFormulario() {
            if (this.multiple) {
                this.generarEtiquetasArticulosPdf({
                    ...this.params,
                    ...this.paramsMultiple,
                });
            } else {
                if (
                    Number(this.params.cantidad) < 1 ||
                    Number(this.params.cantidad) > this.cantidadMaxima
                ) {
                    this.toast.error("Corrija los errores en el formulario");

                    return;
                }

                this.generarEtiquetaArticuloPdf(this.datos.id, this.params);
            }
        },
    },
};
</script>

<template>
    <v-form
        ref="formulario"
        v-model="formularioValido"
        @submit.prevent="validarFormulario"
    >
        <v-card>
            <v-card-title>
                <div
                    class="d-flex flex-wrap justify-space-between align-center"
                >
                    <span class="text-h6"> Generación de Código QR </span>

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

            <v-card-text>
                <div class="pb-4">
                    <div class="d-flex ma-2">
                        <span class="text-caption mx-auto">
                            Selecciona la posición desde donde se imprimirá el
                            código QR
                        </span>
                    </div>

                    <div class="hoja">
                        <div
                            v-for="f in maximoFilas"
                            :key="f"
                            class="d-flex justify-space-between"
                        >
                            <div
                                v-for="c in maximoColumnas"
                                :key="c"
                                class="qr"
                                :class="{
                                    'qr--seleccionado':
                                        numeroCeldaSeleccionada <=
                                            (f - 1) * maximoColumnas + c &&
                                        numeroCeldaSeleccionada +
                                            Number(params.cantidad) >
                                            (f - 1) * maximoColumnas + c,
                                }"
                                @click="
                                    params.fila = f;
                                    params.columna = c;
                                "
                            >
                                <span>
                                    {{ (f - 1) * maximoColumnas + c }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="text-caption mx-2">Fila</div>

                    <v-slider
                        v-model="params.fila"
                        show-ticks="always"
                        :min="1"
                        :max="maximoFilas"
                        :step="1"
                        thumb-label
                        required
                    />

                    <div class="text-caption mx-2">Columna</div>

                    <v-slider
                        v-model="params.columna"
                        show-ticks="always"
                        :min="1"
                        :max="maximoColumnas"
                        :step="1"
                        thumb-label
                        required
                    />

                    <v-text-field
                        v-if="!multiple"
                        v-model="params.cantidad"
                        label="Cantidad"
                        name="cantidad"
                        type="number"
                        :min="1"
                        :max="cantidadMaxima"
                        density="compact"
                        :rules="[
                            (v) =>
                                (Number(v) >= 1 &&
                                    Number(v) <= cantidadMaxima) ||
                                `La cantidad debe ser mayor o igual a 1 y menor o igual a ${cantidadMaxima}`,
                        ]"
                        required
                        clearable
                    />
                </div>
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
                        title="Generar Código QR"
                        type="submit"
                        :disabled="exportandoArticulos"
                        :loading="exportandoArticulos"
                    >
                        Generar
                    </v-btn>

                    <v-btn
                        class="ma-1"
                        color="blue-grey"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cancelar"
                        :disabled="exportandoArticulos"
                        @click="emitCancelarGuardado"
                    >
                        Cancelar
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-form>

    <DialogoReportePdf
        v-model="mostradoDialogoReportePdf"
        :pdf-src="pdfSrc"
        @cerrar="mostradoDialogoReportePdf = false"
    />
</template>

<style scoped>
.hoja {
    max-width: 250px;
    aspect-ratio: 1/1.414;
    margin: 0 auto;
    padding: 1rem 0;
    background-color: #fff;
}

.qr {
    border: 1px solid #e0e0e0;
    width: 100%;
    aspect-ratio: 1/0.5;
    color: #8a8a8a;
    font-size: 0.8rem;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.qr--seleccionado {
    background-color: #8a8a8a;
    color: #fff;
}
</style>
