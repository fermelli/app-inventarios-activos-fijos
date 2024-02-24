<script>
import tablaDatosServidorMixin from "../../../mixins/tabla-datos-servidor.mixin";
import TablaDatosLotes from "./TablaDatosLotes.vue";

export default {
    name: "TablaDatosServidorArticulos",
    components: {
        TablaDatosLotes,
    },
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
                { title: "Cantidad", key: "cantidad" },
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
            mostradoDialogoLotes: false,
            lotesItemSeleccionado: [],
        };
    },
    methods: {
        mostrarDialogoLotes(item) {
            this.lotesItemSeleccionado = item.articulos_lotes;
            this.mostradoDialogoLotes = true;
        },
        cerrarDialogoLotes() {
            this.mostradoDialogoLotes = false;
            this.lotesItemSeleccionado = [];
        },
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

        <template #[`item.cantidad`]="{ item }">
            <v-chip
                v-if="item.cantidad > 0"
                color="success"
                small
                append-icon="mdi-open-in-new"
                @click="mostrarDialogoLotes(item)"
            >
                {{ item.cantidad }}
            </v-chip>

            <v-chip v-else color="error" small>
                {{ item.cantidad }}
            </v-chip>
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
            </template>
        </template>
    </v-data-table-server>

    <v-dialog v-model="mostradoDialogoLotes" width="500px">
        <v-card>
            <v-card-text>
                <TablaDatosLotes :lotes="lotesItemSeleccionado" />
            </v-card-text>

            <v-card-actions>
                <v-btn color="primary" @click="cerrarDialogoLotes">
                    Cerrar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
