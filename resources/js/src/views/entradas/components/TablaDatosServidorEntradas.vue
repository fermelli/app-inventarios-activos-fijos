<script>
import tablaDatosServidorMixin from "../../../mixins/tabla-datos-servidor.mixin";
import { COLORES_ESTADOS_ENTRADAS } from "../../../utils/constantes";

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
                { title: "Estado", key: "estado_entrada" },
                { title: "Institución", key: "institucion.nombre" },
                { title: "Registrado por", key: "usuario.nombre" },
                {
                    title: "Acciones",
                    key: "acciones",
                    sortable: false,
                    filterable: false,
                },
            ],
            coloresEstadosEntradas: COLORES_ESTADOS_ENTRADAS,
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

        <template #[`item.estado_entrada`]="{ item }">
            <v-chip
                :color="coloresEstadosEntradas[item.estado_entrada] || 'grey'"
                class="text-capitalize"
                size="small"
            >
                {{ item.estado_entrada }}
            </v-chip>
        </template>

        <template #[`item.acciones`]="{ item }">
            <v-btn
                class="ma-1"
                color="info"
                density="compact"
                icon="mdi-eye"
                title="Ver"
                @click="$emit('mostrarItem', item.id)"
            />
        </template>
    </v-data-table-server>
</template>
