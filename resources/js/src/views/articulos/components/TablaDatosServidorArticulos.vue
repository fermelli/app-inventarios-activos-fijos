<script>
import tablaDatosServidorMixin from "../../../mixins/tabla-datos-servidor.mixin";
import { aplanarCategorias } from "../../../utils/funciones";
import TablaDatosLotes from "./TablaDatosLotes.vue";
import CategoriaService from "../../../services/categorias";

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
                { title: "Código SIGMA", key: "codigo" },
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
            categoriasPadresConHijasAplanadas: [],
            categoria_id: null,
        };
    },
    created() {
        this.obtenerCategoriasPadresConHijas();
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
            <v-row>
                <v-col cols="12" md="7" lg="8" xl="9">
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
                </v-col>

                <v-col cols="12" md="5" lg="4" xl="3">
                    <v-autocomplete
                        v-model="categoria_id"
                        class="mb-2"
                        :items="categoriasPadresConHijasAplanadas"
                        item-value="id"
                        item-title="nombre_mostrado"
                        label="Categoría"
                        name="categoria_id"
                        density="compact"
                        clear-on-select
                        clearable
                        @update:model-value="
                            $emit('cargarItems', { categoria_id })
                        "
                    >
                        <template #selection="{ item }">
                            {{ item.raw.nombre }}
                        </template>

                        <template #item="{ props }">
                            <v-list-item v-bind="props" />
                        </template>
                    </v-autocomplete>
                </v-col>
            </v-row>
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
