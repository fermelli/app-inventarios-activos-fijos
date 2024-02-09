<script>
import tablaDatosServidorMixin from "../../../mixins/tabla-datos-servidor.mixin";

export default {
    name: "TablaDatosServidorEntradas",
    mixins: [tablaDatosServidorMixin],
    emits: [
        "mostrarFormulario",
        "mostrarConfirmacion",
        "cargarItems",
        "mostrarItem",
    ],
    data() {
        return {
            headers: [
                { title: "#", key: "nro", sortable: false, filterable: false },
                { title: "Fecha", key: "fecha" },
                { title: "Comprobante", key: "comprobante" },
                { title: "N° Comprobante", key: "numero_comprobante" },
                { title: "Institución", key: "institucion.nombre" },
                { title: "Registrado por", key: "usuario.nombre" },
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
    <v-data-table-server
        :headers="headers"
        :items="items"
        :search="busqueda"
        item-key="id"
        :items-length="totalItems"
        no-data-text="No hay ítems disponibles"
        items-per-page-text="Ítems por página"
        loading-text="Cargando ítems..."
        :items-per-page="itemsPorPagina"
        page-text="{0}-{1} de {2}"
        :items-per-page-options="itemsPorPaginaOpciones"
        density="compact"
        :loading="cargandoItems"
        @update:items-per-page="actualizarItemsPorPagina"
        @update:options="
            ({ page, itemsPerPage, search }) =>
                $emit('cargarItems', { page, itemsPerPage, search })
        "
    >
        <template #top>
            <v-text-field
                v-model="q"
                class="mb-2"
                density="compact"
                label="Buscar"
                name="buscar"
                type="text"
                prepend-icon="mdi-magnify"
                clearable
                @update:model-value="buscar"
            />
        </template>

        <template #[`item.nro`]="{ index }">
            {{ index + 1 + itemsPorPagina * (paginaActual - 1) }}
        </template>

        <template #[`item.acciones`]="{ item }">
            <v-btn
                color="green"
                density="compact"
                icon="mdi-eye"
                title="Ver"
                @click="$emit('mostrarItem', item)"
            />

            <v-btn
                v-if="!item.eliminado_en"
                class="ml-2"
                color="error"
                density="compact"
                icon="mdi-cancel"
                title="Desactivar"
                @click="
                    $emit(
                        'mostrarConfirmacion',
                        item,
                        'desactivar',
                        `de ${item.comprobante} N° ${item.numero_comprobante}`,
                    )
                "
            />

            <v-btn
                v-else
                class="ml-2"
                color="success"
                density="compact"
                icon="mdi-check"
                title="Activar"
                @click="
                    $emit(
                        'mostrarConfirmacion',
                        item,
                        'activar',
                        `${item.nombre}`,
                    )
                "
            />
        </template>
    </v-data-table-server>
</template>
