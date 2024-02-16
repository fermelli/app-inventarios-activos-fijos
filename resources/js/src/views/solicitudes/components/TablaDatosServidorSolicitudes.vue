<script>
import tablaDatosServidorMixin from "../../../mixins/tabla-datos-servidor.mixin";

export default {
    name: "TablaDatosServidorSolicitudes",
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
                { title: "N° Solicitud", key: "numero_solicitud" },
                { title: "Solicitante", key: "solicitante.nombre" },
                { title: "Observación", key: "observacion" },
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
        :items-per-page="itemsPorPagina"
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
                @click="$emit('mostrarItem', item.id)"
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
                        `de N° de Solicitud ${item.numero_solicitud} del Solicitante ${item.solicitante.nombre}`,
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
                        `de N° de Solicitud ${item.numero_solicitud} del Solicitante ${item.solicitante.nombre}`,
                    )
                "
            />
        </template>
    </v-data-table-server>
</template>
