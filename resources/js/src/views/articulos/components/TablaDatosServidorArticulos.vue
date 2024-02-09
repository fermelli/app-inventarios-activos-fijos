<script>
import tablaDatosServidorMixin from "../../../mixins/tabla-datos-servidor.mixin";

export default {
    name: "TablaDatosServidorArticulos",
    mixins: [tablaDatosServidorMixin],
    props: {
        soloSeleccionItems: {
            type: Boolean,
            default: false,
        },
    },
    emits: [
        "mostrarFormulario",
        "mostrarConfirmacion",
        "cargarItems",
        "seleccionarItem",
    ],
    data() {
        return {
            headers: [
                { title: "#", key: "nro", sortable: false, filterable: false },
                { title: "Código", key: "codigo" },
                { title: "Nombre", key: "nombre" },
                { title: "Categoría", key: "categoria.nombre" },
                { title: "Unidad", key: "unidad.nombre" },
                { title: "Ubicación", key: "ubicacion.nombre" },
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
                v-if="soloSeleccionItems"
                color="primary"
                density="compact"
                icon="mdi-plus"
                title="Seleccionar"
                @click="$emit('seleccionarItem', item)"
            />

            <template v-else>
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
                            `${item.nombre}`,
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
        </template>
    </v-data-table-server>
</template>
