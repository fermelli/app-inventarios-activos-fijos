<script>
import tablaDatosMixin from "../../../mixins/tabla-datos.mixin";

export default {
    name: "TablaDatosUsuarios",
    mixins: [tablaDatosMixin],
    emits: ["mostrarFormulario", "mostrarConfirmacion"],
    data() {
        return {
            headers: [
                { title: "#", key: "nro", sortable: false, filterable: false },
                { title: "Nombre", key: "nombre" },
                { title: "Correo electrónico", key: "correo_electronico" },
                { title: "Rol", key: "rol" },
                { title: "Dependencia", key: "dependencia.nombre" },
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

        <template #[`item.dependencia.nombre`]="{ item }">
            {{ item?.dependencia?.nombre || "-" }}
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
                @click="
                    $emit(
                        'mostrarConfirmacion',
                        item,
                        'eliminar',
                        `${item.nombre} (${item.correo_electronico})`,
                    )
                "
            />

            <v-btn
                v-if="!item.eliminado_en"
                class="ma-1"
                color="error"
                density="compact"
                icon="mdi-cancel"
                title="Desactivar"
                @click="
                    $emit(
                        'mostrarConfirmacion',
                        item,
                        'desactivar',
                        `${item.nombre} (${item.correo_electronico})`,
                    )
                "
            />

            <v-btn
                v-else
                class="ma-1"
                color="success"
                density="compact"
                icon="mdi-check"
                title="Activar"
                @click="
                    $emit(
                        'mostrarConfirmacion',
                        item,
                        'activar',
                        `${item.nombre} (${item.correo_electronico})`,
                    )
                "
            />
        </template>
    </v-data-table>
</template>
