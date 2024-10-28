<script>
import tablaDatosServidorMixin from "../../../mixins/tabla-datos-servidor.mixin";
import { aplanarCategorias } from "../../../utils/funciones";
import TablaDatosLotes from "./TablaDatosLotes.vue";
import CategoriaService from "../../../services/categorias";
import { useDisplay } from "vuetify";

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
        "exportarPdf",
        "exportarExcel",
        "exportarPdfSinPaginacion",
    ],
    setup() {
        const display = useDisplay();

        return { display };
    },
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
        :mobile="null"
        :hide-default-header="display.mobile.value"
        @update:items-per-page="actualizarItemsPorPagina"
        @update:options="
            ({ page, itemsPerPage, search }) =>
                $emit('cargarItems', { page, itemsPerPage, search })
        "
    >
        <template #top>
            <v-row>
                <v-col cols="12" md="6" lg="7" xl="8">
                    <v-text-field
                        v-model="q"
                        class="mb-2"
                        density="compact"
                        label="Buscar"
                        name="buscar"
                        type="text"
                        prepend-inner-icon="mdi-magnify"
                        clearable
                        hide-details
                        @update:model-value="buscar"
                    />
                </v-col>

                <v-col cols="12" md="4" lg="3" xl="2">
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
                        hide-details
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

                <v-col cols="12" md="2" lg="2" xl="2">
                    <v-menu>
                        <template #activator="{ props }">
                            <v-btn
                                v-bind="props"
                                class="mb-8 mb-md-0"
                                color="primary"
                                density="compact"
                                prepend-icon="mdi-file-import-outline"
                                title="Exportar"
                                :disabled="exportandoItems"
                                :loading="exportandoItems"
                            >
                                Exportar
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item
                                density="compact"
                                @click="$emit('exportarPdf')"
                            >
                                <v-list-item-title> a PDF </v-list-item-title>
                            </v-list-item>

                            <v-list-item
                                density="compact"
                                @click="$emit('exportarPdfSinPaginacion')"
                            >
                                <v-list-item-title>
                                    a PDF (sin paginación)
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item
                                density="compact"
                                @click="$emit('exportarExcel')"
                            >
                                <v-list-item-title> a Excel </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
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
                size="small"
                append-icon="mdi-open-in-new"
                @click="mostrarDialogoLotes(item)"
            >
                {{ item.cantidad }}
            </v-chip>

            <v-chip v-else color="error" size="small">
                {{ item.cantidad }}
            </v-chip>
        </template>

        <template #[`item.acciones`]="{ item }">
            <v-btn
                v-if="soloSeleccionItems"
                class="ma-1"
                color="primary"
                density="compact"
                icon="mdi-plus"
                title="Seleccionar"
                @click="$emit('seleccionarItem', item)"
            />

            <template v-else>
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
                <div
                    class="d-flex flex-wrap justify-space-between align-center"
                >
                    <v-btn
                        class="ma-1"
                        color="primary"
                        density="compact"
                        prepend-icon="mdi-close"
                        @click="cerrarDialogoLotes"
                    >
                        Cerrar
                    </v-btn>
                </div>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
