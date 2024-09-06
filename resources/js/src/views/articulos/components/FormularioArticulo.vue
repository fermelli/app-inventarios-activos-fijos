<script>
import ArticuloService from "./../../../services/articulos";
import CategoriaService from "./../../../services/categorias";
import UnidadService from "./../../../services/unidades";
import UbicacionService from "./../../../services/ubicaciones";
import { useToast } from "vue-toastification";
import { aplanarCategorias } from "./../../../utils/funciones";
import formularioMixin from "../../../mixins/formulario.mixin";

export default {
    name: "FormularioArticulo",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "categoria_id" in valor &&
                    "unidad_id" in valor &&
                    "ubicacion_id" in valor &&
                    "codigo" in valor &&
                    "nombre" in valor
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
            metodoStore: ArticuloService.store,
            metodoUpdate: ArticuloService.update,
            categoriasPadresConHijasAplanadas: [],
            cargandoCategoriasPadresConHijas: false,
            unidades: [],
            cargandoUnidades: false,
            ubicaciones: [],
            cargandoUbicaciones: false,
            reglasValidacionCodigo: [
                (valor) => !!valor || "El código es requerido",
                (valor) =>
                    (valor && valor.length <= 100) ||
                    "El nombre debe tener menos de 100 caracteres",
            ],
            reglasValidacionNombre: [
                (valor) => !!valor || "El nombre es requerido",
                (valor) =>
                    (valor && valor.length <= 255) ||
                    "El nombre debe tener menos de 255 caracteres",
            ],
            reglasValidacionCategoriaId: [
                (valor) => !!valor || "La categoría es requerida",
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
            ],
            reglasValidacionUnidadId: [
                (valor) => !!valor || "La unidad es requerida",
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
        };
    },
    created() {
        this.obtenerCategoriasPadresConHijas();
        this.obtenerUnidades();
        this.obtenerUbicaciones();
    },
    methods: {
        async obtenerCategoriasPadresConHijas() {
            this.cargandoCategoriasPadresConHijas = true;

            try {
                const { data } = await CategoriaService.indexPadresConHijas({
                    params: { orden_direccion: "asc", con_eliminados: true },
                });
                const categoriasPadresConHijas = data.datos;

                this.categoriasPadresConHijasAplanadas = [];

                aplanarCategorias(
                    categoriasPadresConHijas,
                    this.categoriasPadresConHijasAplanadas,
                );
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoCategoriasPadresConHijas = false;
            }
        },
        async obtenerUnidades() {
            this.cargandoUnidades = true;

            try {
                const { data } = await UnidadService.index({
                    params: { orden_direccion: "asc" },
                });

                this.unidades = data.datos;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoUnidades = false;
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
                <span class="text-h6">{{ titulo }}</span>
            </v-card-title>

            <v-card-text class="pa-4 pb-0">
                <v-text-field
                    v-model="formulario.codigo"
                    class="mb-2"
                    label="Código SIGMA"
                    name="codigo"
                    type="text"
                    density="compact"
                    :rules="reglasValidacionCodigo"
                    required
                    clearable
                />

                <v-text-field
                    v-model="formulario.nombre"
                    class="mb-2"
                    label="Nombre"
                    name="nombre"
                    type="text"
                    density="compact"
                    :rules="reglasValidacionNombre"
                    required
                    clearable
                />

                <v-autocomplete
                    v-model="formulario.categoria_id"
                    class="mb-2"
                    :items="categoriasPadresConHijasAplanadas"
                    item-value="id"
                    item-title="nombre_mostrado"
                    label="Categoría"
                    name="categoria_id"
                    density="compact"
                    clear-on-select
                    clearable
                    :rules="reglasValidacionCategoriaId"
                >
                    <template #selection="{ item }">
                        {{ item.raw.nombre }}
                    </template>

                    <template #item="{ item, props }">
                        <v-list-item
                            v-bind="props"
                            :disabled="item.raw.id == formulario.id"
                        />
                    </template>
                </v-autocomplete>

                <v-autocomplete
                    v-model="formulario.unidad_id"
                    class="mb-2"
                    :items="unidades"
                    item-value="id"
                    item-title="nombre"
                    label="Unidad"
                    name="unidad_id"
                    density="compact"
                    clear-on-select
                    clearable
                    :rules="reglasValidacionUnidadId"
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
