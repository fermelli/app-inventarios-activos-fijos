<script>
import tablaDatosMixin from "../../../mixins/tabla-datos.mixin";
import { useDisplay } from "vuetify";

export default {
    name: "TablaDatosCategorias",
    mixins: [tablaDatosMixin],
    emits: ["mostrarFormulario", "mostrarConfirmacion"],
    setup() {
        const display = useDisplay();

        return { display };
    },
    data() {
        return {
            headers: [
                { title: "#", key: "nro", sortable: false, filterable: false },
                { title: "Nombre", key: "nombre" },
                { title: "Categoría Padre", key: "categoria_padre.nombre" },
                {
                    title: "Nro. de categorias hijas",
                    key: "categorias_hijas.length",
                    filterable: false,
                },
                {
                    title: "Acciones",
                    key: "acciones",
                    sortable: false,
                    filterable: false,
                },
            ],
        };
    },
};
</script>

<template>
    <v-data-table
        :headers="headers"
        :items="items"
        :search="busqueda"
        item-key="id"
        :items-per-page="itemsPorPagina"
        :items-per-page-options="itemsPorPaginaOpciones"
        density="compact"
        :loading="cargandoItems"
        :mobile="null"
        :hide-default-header="display.mobile.value"
        @update:items-per-page="actualizarItemsPorPagina"
        @update:page="paginaActual = $event"
    >
        <template #top>
            <v-text-field
                v-model="busqueda"
                class="mb-2"
                density="compact"
                label="Buscar"
                name="buscar"
                type="text"
                prepend-inner-icon="mdi-magnify"
                clearable
                hide-details
            />
        </template>

        <template #[`item.nro`]="{ index }">
            {{ index + 1 + itemsPorPagina * (paginaActual - 1) }}
        </template>

        <template #[`item.acciones`]="{ item }">
            <v-btn
                class="ma-1"
                color="primary"
                density="compact"
                icon="mdi-pencil"
                title="Editar"
                @click="$emit('mostrarFormulario', item)"
            />

            <v-btn
                class="ma-1"
                color="error"
                density="compact"
                icon="mdi-trash-can"
                title="Eliminar"
                :disabled="item.categorias_hijas.length > 0"
                @click="
                    $emit(
                        'mostrarConfirmacion',
                        item,
                        'eliminar',
                        `${item.nombre}`,
                    )
                "
            />
        </template>
    </v-data-table>
</template>
