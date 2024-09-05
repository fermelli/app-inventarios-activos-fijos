<script>
import tablaDatosServidorMixin from "../../../mixins/tabla-datos-servidor.mixin";
import { aplanarCategorias } from "../../../utils/funciones";
import CategoriaService from "../../../services/categorias";
import {
    COLORES_ESTADOS_ACTIVOS_FIJOS,
    ESTADOS_ACTIVOS_FIJOS,
} from "../../../utils/constantes";
import { useDisplay } from "vuetify";

export default {
    name: "TablaDatosServidorActivosFijos",
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
        "mostrarItem",
        "mostrarFormularioAsignacion",
        "mostrarFormularioDevolucion",
        "mostrarFormularioDarBaja",
    ],
    setup() {
        const display = useDisplay();

        return { display };
    },
    data() {
        return {
            headers: [
                { title: "#", key: "nro", sortable: false, filterable: false },
                { title: "Código SIGMA", key: "codigo" },
                { title: "Nombre", key: "nombre" },
                { title: "Estado", key: "estado_activo_fijo" },
                {
                    title: "Está asignado",
                    key: "asignacion_activo_fijo_actual",
                },
                { title: "Categoría", key: "categoria.nombre" },
                { title: "Institución", key: "institucion.nombre" },
                { title: "Descripción", key: "descripcion" },
                {
                    title: "Acciones",
                    key: "acciones",
                    sortable: false,
                    filterable: false,
                    width: "250px",
                },
            ],
            categoriasPadresConHijasAplanadas: [],
            categoria_id: null,
            estadosActivosFijos: ESTADOS_ACTIVOS_FIJOS,
            coloresEstadosActivosFijos: COLORES_ESTADOS_ACTIVOS_FIJOS,
        };
    },
    created() {
        this.obtenerCategoriasPadresConHijas();
    },
    methods: {
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
        deshabilitadoBotonDarBaja(item) {
            return (
                !!item.asignacion_activo_fijo_actual ||
                item.estado_activo_fijo === this.estadosActivosFijos.de_baja
            );
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
                <v-col cols="12" md="7" lg="8" xl="9">
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
            </v-row>
        </template>

        <template #[`item.nro`]="{ index }">
            {{ index + 1 + itemsPorPagina * (paginaActual - 1) }}
        </template>

        <template #[`item.estado_activo_fijo`]="{ item }">
            <v-chip
                class="text-capitalize"
                :color="coloresEstadosActivosFijos[item.estado_activo_fijo]"
                size="small"
            >
                {{ item.estado_activo_fijo }}
            </v-chip>
        </template>

        <template #[`item.asignacion_activo_fijo_actual`]="{ item }">
            <v-chip
                class="text-capitalize"
                :color="
                    !!item.asignacion_activo_fijo_actual ? 'success' : 'error'
                "
                size="small"
            >
                {{ item.asignacion_activo_fijo_actual ? "Sí" : "No" }}
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
                    color="info"
                    density="compact"
                    icon="mdi-eye"
                    title="Ver"
                    @click="$emit('mostrarItem', item.id)"
                />

                <v-btn
                    class="ma-1"
                    color="error"
                    density="compact"
                    icon="mdi-cancel"
                    title="Dar de baja"
                    :disabled="deshabilitadoBotonDarBaja(item)"
                    @click="$emit('mostrarFormularioDarBaja', item)"
                />

                <v-btn
                    v-if="!item.asignacion_activo_fijo_actual"
                    class="ma-1"
                    color="success"
                    density="compact"
                    icon="mdi-account-plus"
                    title="Asignar"
                    :disabled="
                        item.estado_activo_fijo !== estadosActivosFijos.activo
                    "
                    @click="$emit('mostrarFormularioAsignacion', item)"
                />

                <v-btn
                    v-else
                    class="ma-1"
                    color="error"
                    density="compact"
                    icon="mdi-account-minus"
                    title="Devolución"
                    @click="$emit('mostrarFormularioDevolucion', item)"
                />

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
</template>
