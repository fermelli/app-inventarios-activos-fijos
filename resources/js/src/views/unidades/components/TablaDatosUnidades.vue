<script>
import tablaDatosMixin from "../../../mixins/tabla-datos.mixin";

export default {
    name: "TablaDatosUnidades",
    mixins: [tablaDatosMixin],
    emits: ["mostrarFormulario", "mostrarConfirmacion"],
    data() {
        return {
            headers: [
                { title: "#", key: "nro", sortable: false, filterable: false },
                { title: "Nombre", key: "nombre" },
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
        no-data-text="No hay ítems disponibles"
        items-per-page-text="Ítems por página"
        loading-text="Cargando ítems..."
        :items-per-page="itemsPorPagina"
        page-text="{0}-{1} de {2}"
        :items-per-page-options="itemsPorPaginaOpciones"
        density="compact"
        :loading="cargandoItems"
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
