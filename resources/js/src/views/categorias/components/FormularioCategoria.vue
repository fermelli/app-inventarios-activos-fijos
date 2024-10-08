<script>
import CategoriaService from "./../../../services/categorias";
import { useToast } from "vue-toastification";
import { aplanarCategorias } from "./../../../utils/funciones";
import formularioMixin from "../../../mixins/formulario.mixin";

export default {
    name: "FormularioCategoria",
    mixins: [formularioMixin],
    props: {
        datos: {
            type: Object,
            required: true,
            validator: (valor) => {
                return (
                    "id" in valor &&
                    "nombre" in valor &&
                    "categoria_padre_id" in valor
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
            metodoStore: CategoriaService.store,
            metodoUpdate: CategoriaService.update,
            categoriasPadresConHijasAplanadas: [],
            cargandoCategoriasPadresConHijas: false,
            reglasValidacionNombre: [
                (valor) => !!valor || "El nombre es requerido",
                (valor) =>
                    (valor && valor.length <= 100) ||
                    "El nombre debe tener menos de 100 caracteres",
            ],
            reglasValidacionCategoriaPadreId: [
                (valor) =>
                    !valor ||
                    Number.isInteger(Number(valor)) ||
                    "Debe ser un número",
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
                    v-model="formulario.categoria_padre_id"
                    class="mb-2"
                    :items="categoriasPadresConHijasAplanadas"
                    item-value="id"
                    item-title="nombre_mostrado"
                    label="Categoría Padre"
                    name="categoria_padre_id"
                    density="compact"
                    clear-on-select
                    clearable
                    :rules="reglasValidacionCategoriaPadreId"
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
