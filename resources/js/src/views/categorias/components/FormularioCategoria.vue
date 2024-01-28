<script>
import CategoriaService from "./../../../services/categorias";
import { useToast } from "vue-toastification";
import { aplanarCategorias } from "./../../../utils/funciones";

export default {
    name: "FormularioCategoria",
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
    emits: ["actualizarListado", "cancelarGuardado"],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            formulario: this.datos,
            formularioValido: false,
            guardandoCategoria: false,
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
        async guardarCategoria() {
            if (!this.formularioValido) {
                return;
            }

            this.guardandoCategoria = true;

            try {
                if (this.formulario.id) {
                    await CategoriaService.update(
                        this.formulario.id,
                        this.formulario,
                    );
                } else {
                    await CategoriaService.store(this.formulario);
                }

                this.toast.success("Categoría guardada exitosamente");
                this.$emit("actualizarListado");
                this.obtenerCategoriasPadresConHijas();
                this.$emit("cancelarGuardado");
            } catch (error) {
                console.log(error);
            } finally {
                this.guardandoCategoria = false;
            }
        },
    },
};
</script>

<template>
    <v-form
        v-model="formularioValido"
        :loading="guardandoCategoria"
        @submit.prevent="guardarCategoria"
    >
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
            no-data-text="No hay ítems disponibles"
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

        <v-btn
            color="primary"
            density="compact"
            prepend-icon="mdi-content-save"
            title="Guardar"
            type="submit"
            :disabled="guardandoCategoria"
        >
            Guardar
        </v-btn>

        <v-btn
            class="ml-2"
            color="blue-grey"
            density="compact"
            prepend-icon="mdi-close"
            title="Cancelar"
            @click="$emit('cancelarGuardado')"
        >
            Cancelar
        </v-btn>
    </v-form>
</template>