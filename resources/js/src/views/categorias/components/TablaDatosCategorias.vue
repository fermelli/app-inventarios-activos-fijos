<script>
export default {
    name: "TablaDatosCategorias",
    props: {
        categorias: {
            type: Array,
            required: true,
        },
        cargandoCategorias: {
            type: Boolean,
            required: true,
        },
    },
    emits: ["mostrarFormulario", "mostrarConfirmacion"],
    data() {
        return {
            busqueda: null,
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
            itemsPorPaginaOpciones: [
                { value: 10, title: "10" },
                { value: 15, title: "15" },
                { value: 30, title: "30" },
                { value: 50, title: "50" },
                { value: -1, title: "Todos" },
            ],
            itemsPorPagina:
                Number(
                    localStorage.getItem(`itemsPorPagina-${this.$route.name}`),
                ) || 10,
            paginaActual: 1,
        };
    },
    methods: {
        actualizarItemsPorPagina(itemsPorPagina) {
            localStorage.setItem(
                `itemsPorPagina-${this.$route.name}`,
                itemsPorPagina,
            );

            this.itemsPorPagina = itemsPorPagina;
        },
    },
};
</script>

<template>
    <v-data-table
        :headers="headers"
        :items="categorias"
        :search="busqueda"
        item-key="id"
        no-data-text="No hay ítems disponibles"
        items-per-page-text="Ítems por página"
        loading-text="Cargando ítems..."
        :items-per-page="itemsPorPagina"
        page-text="{0}-{1} de {2}"
        :items-per-page-options="itemsPorPaginaOpciones"
        density="compact"
        :loading="cargandoCategorias"
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
                prepend-icon="mdi-magnify"
                clearable
            />
        </template>

        <template #[`item.nro`]="{ index }">
            {{ index + 1 + itemsPorPagina * (paginaActual - 1) }}
        </template>

        <template #[`item.acciones`]="{ item }">
            <v-btn
                color="primary"
                density="compact"
                icon="mdi-pencil"
                title="Editar"
                @click="$emit('mostrarFormulario', item)"
            />

            <v-btn
                class="ml-2"
                color="error"
                density="compact"
                icon="mdi-trash-can"
                title="Eliminar"
                :disabled="item.categorias_hijas.length > 0"
                @click="$emit('mostrarConfirmacion', item, 'eliminar')"
            />

            <v-btn
                v-if="!item.eliminado_en"
                class="ml-2"
                color="error"
                density="compact"
                icon="mdi-cancel"
                title="Desactivar"
                @click="$emit('mostrarConfirmacion', item, 'desactivar')"
            />

            <v-btn
                v-else
                class="ml-2"
                color="success"
                density="compact"
                icon="mdi-check"
                title="Activar"
                @click="$emit('mostrarConfirmacion', item, 'activar')"
            />
        </template>
    </v-data-table>
</template>
