<script>
import ActivoFijoService from "./../../../services/activos-fijos";
import CategoriaService from "./../../../services/categorias";
import { useToast } from "vue-toastification";
import { aplanarCategorias } from "./../../../utils/funciones";
import formularioMixin from "../../../mixins/formulario.mixin";

export default {
    name: "FormularioActivoFijo",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "categoria_id" in valor &&
                    "codigo" in valor &&
                    "nombre" in valor &&
                    "descripcion" in valor
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
            metodoStore: ActivoFijoService.store,
            metodoUpdate: ActivoFijoService.update,
            categoriasPadresConHijasAplanadas: [],
            cargandoCategoriasPadresConHijas: false,
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
            reglasValidacionDescripcion: [
                (valor) =>
                    !valor ||
                    valor.length <= 65535 ||
                    "La descripción debe tener menos de 65535 caracteres",
            ],
        };
    },
    created() {
        this.obtenerCategoriasPadresConHijas();
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
    },
};
</script>

<template>
    <v-form
        v-model="formularioValido"
        :loading="guardandoItem"
        @submit.prevent="guardarItem"
    >
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

        <v-textarea
            v-model="formulario.descripcion"
            class="mb-2"
            label="Descripción"
            name="descripcion"
            density="compact"
            :rules="reglasValidacionDescripcion"
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
