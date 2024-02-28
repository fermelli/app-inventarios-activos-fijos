<script>
import ActivoFijoService from "./../../services/activos-fijos";
import { useToast } from "vue-toastification";
import TablaDatosServidorActivosFijos from "./components/TablaDatosServidorActivosFijos.vue";
import FormularioActivoFijo from "./components/FormularioActivoFijo.vue";
import vistaMixin from "../../mixins/vista.mixin";
import FormularioAsignacionActivoFijo from "./components/FormularioAsignacionActivoFijo.vue";
import FormularioDevolucionActivoFijo from "./components/FormularioDevolucionActivoFijo.vue";
import TablaAsignacionesActivosFijos from "./components/TablaAsignacionesActivosFijos.vue";
import { ESTADOS_ACTIVOS_FIJOS } from "../../utils/constantes";
import FormularioDarBajaActivoFijo from "./components/FormularioDarBajaActivoFijo.vue";
import dialogoFormularioImportarMixin from "../../mixins/dialogo-formulario-importar.mixin";

export default {
    name: "ActivosFijosVista",
    components: {
        TablaDatosServidorActivosFijos,
        FormularioActivoFijo,
        FormularioAsignacionActivoFijo,
        FormularioDevolucionActivoFijo,
        TablaAsignacionesActivosFijos,
        FormularioDarBajaActivoFijo,
    },
    mixins: [vistaMixin, dialogoFormularioImportarMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Activo Fijo",
            pagina: 1,
            itemsPorPagina: parseInt(
                localStorage.getItem(`itemsPorPagina-${this.$route.name}`) ||
                    10,
            ),
            totalItems: 0,
            busqueda: null,
            categoria_id: null,
            asignacionActivoFijo: this.crearAsignacionActivoFijo(),
            mostradoDialogoFormularioAsignacion: false,
            devolucionActivoFijo: this.crearDevolucionActivoFijo(),
            mostradoDialogoFormularioDevolucion: false,
            mostradoDialogoMostrarItem: false,
            mostradoDialogoFormularioDarBaja: false,
            metodoImportar: ActivoFijoService.importar,
            metodoFormatoImportacion: ActivoFijoService.formatoImportacion,
            tituloArchivoEjemploImportacion:
                "Formato de Importación de Activos Fijos",
        };
    },
    computed: {
        listadoDatos() {
            if (!this.itemSeleccionado) {
                return [];
            }

            return [
                {
                    titulo: "Código SIGMA",
                    subtitulo: this.itemSeleccionado.codigo,
                },
                {
                    titulo: "Activo Fijo",
                    subtitulo: this.itemSeleccionado.nombre,
                },
                {
                    titulo: "Estado",
                    subtitulo: this.itemSeleccionado.estado_activo_fijo,
                },
                {
                    titulo: "Está asignado",
                    subtitulo: this.itemSeleccionado
                        .asignacion_activo_fijo_actual
                        ? "Sí"
                        : "No",
                },
                {
                    titulo: "Categoría",
                    subtitulo: this.itemSeleccionado.categoria?.nombre,
                },
                {
                    titulo: "Institución",
                    subtitulo: this.itemSeleccionado.institucion?.nombre,
                },
                {
                    titulo: "Fecha de Baja",
                    subtitulo: this.itemSeleccionado.fecha_baja || "-",
                },
            ];
        },
        botonDarBajaDeshabilitado() {
            return (
                !this.itemSeleccionado ||
                this.itemSeleccionado.estado_activo_fijo ===
                    ESTADOS_ACTIVOS_FIJOS.de_baja ||
                !!this.itemSeleccionado.asignacion_activo_fijo_actual
            );
        },
    },
    methods: {
        async accionItem(accion) {
            if (!["eliminar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                await ActivoFijoService.destroy(this.itemSeleccionado.id);

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerActivosFijos();
                this.cancelarAccion();
            } catch (error) {
                console.log(error);
            } finally {
                this.realizandoAccion = false;
            }
        },
        crearDatosItem() {
            return {
                id: null,
                categoria_id: null,
                categoria: null,
                institucion_id: null,
                institucion: null,
                codigo: null,
                nombre: null,
                descripcion: null,
                asignaciones_activos_fijos: [],
                fecha_baja: null,
                razon_baja: null,
            };
        },
        async obtenerActivosFijos(payload) {
            this.cargandoItems = true;
            this.pagina = payload?.page || this.pagina;
            this.itemsPorPagina = payload?.itemsPerPage || this.itemsPorPagina;
            this.busqueda =
                !!payload && "search" in payload
                    ? payload?.search
                    : this.busqueda;
            this.categoria_id =
                !!payload && "categoria_id" in payload
                    ? payload?.categoria_id
                    : this.categoria_id;

            try {
                const { data } = await ActivoFijoService.index({
                    params: {
                        orden_direccion: "desc",
                        con_eliminados: true,
                        pagina: this.pagina,
                        items_por_pagina: this.itemsPorPagina,
                        busqueda: this.busqueda,
                        categoria_id: this.categoria_id,
                    },
                });

                this.items = data.datos;
                this.totalItems = data.metadatos.total || 0;
            } catch (error) {
                console.log(error);
            } finally {
                this.cargandoItems = false;
            }
        },
        crearAsignacionActivoFijo() {
            return {
                activo_fijo_id: null,
                asignado_a_id: null,
                ubicacion_id: null,
                fecha_asignacion: null,
                observacion_asignacion: null,
            };
        },
        mostrarDialogoFormularioAsignacion(item) {
            this.asignacionActivoFijo.activo_fijo_id = item.id;
            this.mostradoDialogoFormularioAsignacion = true;
        },
        cancelarGuardadoAsignacionActivoFijo() {
            this.mostradoDialogoFormularioAsignacion = false;
            this.asignacionActivoFijo = this.crearAsignacionActivoFijo();
        },
        crearDevolucionActivoFijo() {
            return {
                id: null,
                fecha_devolucion: null,
                observacion_devolucion: null,
            };
        },
        mostrarDialogoFormularioDevolucion(item) {
            this.devolucionActivoFijo.id =
                item.asignacion_activo_fijo_actual?.id;
            this.mostradoDialogoFormularioDevolucion = true;
        },
        cancelarGuardadoDevolucionActivoFijo() {
            this.mostradoDialogoFormularioDevolucion = false;
            this.devolucionActivoFijo = this.crearDevolucionActivoFijo();
        },
        async mostrarItem(itemId) {
            try {
                const { data } = await ActivoFijoService.show(itemId);

                this.itemSeleccionado = data.datos;
                this.mostradoDialogoMostrarItem = true;
            } catch (error) {
                console.log(error);
            }
        },
        cerrarDialogoMostrarItem() {
            this.mostradoDialogoMostrarItem = false;
            this.itemSeleccionado = this.crearDatosItem();
        },
        mostrarFormularioDarBajaActivoFijo(item) {
            this.datosItem = item;
            this.mostradoDialogoFormularioDarBaja = true;
        },
        cancelarGuardadoDarBajaActivoFijo() {
            this.mostradoDialogoFormularioDarBaja = false;
            this.datosItem = this.crearDatosItem();
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex align-center">
                <h2 class="text-h5">Activos Fijos</h2>

                <v-btn
                    class="ml-2"
                    color="success"
                    density="compact"
                    prepend-icon="mdi-plus"
                    title="Registrar"
                    @click="() => mostrarDialogoFormulario()"
                >
                    Registrar
                </v-btn>

                <v-btn
                    class="ml-2"
                    color="primary"
                    density="compact"
                    prepend-icon="mdi-file-import-outline"
                    title="Importar"
                    @click="() => (mostradoDialogoFormularioImportar = true)"
                >
                    Importar
                </v-btn>

                <v-btn
                    class="ml-2"
                    color="primary"
                    density="compact"
                    icon="mdi-reload"
                    :loading="cargandoItems"
                    :disabled="cargandoItems"
                    title="Recargar"
                    @click="obtenerActivosFijos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorActivosFijos
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerActivosFijos"
                @mostrar-item="mostrarItem"
                @mostrar-formulario-asignacion="
                    mostrarDialogoFormularioAsignacion
                "
                @mostrar-formulario-devolucion="
                    mostrarDialogoFormularioDevolucion
                "
                @mostrar-formulario-dar-baja="
                    mostrarFormularioDarBajaActivoFijo
                "
            />
        </v-col>

        <v-dialog v-model="mostradoDialogoFormulario" persistent width="800">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ tituloDialogoFormulario }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioActivoFijo
                        :datos="datosItem"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerActivosFijos"
                        @cancelar-guardado="cancelarGuardado"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioAsignacion"
            persistent
            width="800"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h6">
                        {{ `Asignación ${nombreItem}` }}
                    </span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioAsignacionActivoFijo
                        :datos="asignacionActivoFijo"
                        :nombre-item="`Asignación ${nombreItem}`"
                        @actualizar-listado="obtenerActivosFijos"
                        @cancelar-guardado="
                            cancelarGuardadoAsignacionActivoFijo
                        "
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioDevolucion"
            persistent
            width="800"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h6">
                        {{ `Devolución ${nombreItem}` }}
                    </span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioDevolucionActivoFijo
                        :datos="devolucionActivoFijo"
                        :nombre-item="`Devolución ${nombreItem}`"
                        @actualizar-listado="obtenerActivosFijos"
                        @cancelar-guardado="
                            cancelarGuardadoDevolucionActivoFijo
                        "
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="mostradoDialogoMostrarItem" width="100%">
            <v-card>
                <v-card-title>
                    <span class="text-h6">{{ nombreItem }}</span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <v-row>
                        <v-col
                            v-for="(dato, indice) in listadoDatos"
                            :key="indice"
                            class="py-0"
                            cols="12"
                            lg="3"
                        >
                            <v-list lines="two">
                                <v-list-item
                                    class="py-0"
                                    :title="dato.titulo"
                                    :subtitle="dato.subtitulo"
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12" lg="6" class="py-0">
                            <v-list lines="three">
                                <v-list-item
                                    class="py-0"
                                    title="Descripción"
                                    :subtitle="
                                        itemSeleccionado?.descripcion || '-'
                                    "
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12" lg="6" class="py-0">
                            <v-list lines="three">
                                <v-list-item
                                    class="py-0"
                                    title="Descripción"
                                    :subtitle="
                                        itemSeleccionado?.razon_baja || '-'
                                    "
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12">
                            <TablaAsignacionesActivosFijos
                                :asignaciones-activos-fijos="
                                    itemSeleccionado?.asignaciones_activos_fijos
                                "
                            />
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-btn
                        color="blue-grey"
                        density="compact"
                        prepend-icon="mdi-close"
                        title="Cerrar"
                        @click="mostradoDialogoMostrarItem = false"
                    >
                        Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioDarBaja"
            persistent
            width="800"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h6">
                        {{ `Dar Baja ${nombreItem}` }}
                    </span>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioDarBajaActivoFijo
                        :datos="datosItem"
                        :nombre-item="`Dar Baja ${nombreItem}`"
                        @actualizar-listado="obtenerActivosFijos"
                        @cancelar-guardado="cancelarGuardadoDarBajaActivoFijo"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioImportar"
            persistent
            width="560"
        >
            <v-card>
                <v-card-title>
                    <div class="d-flex justify-space-between align-center">
                        <span class="text-h6">Importar Activos Fijos</span>

                        <v-btn
                            class="ml-2"
                            color="primary"
                            variant="text"
                            density="compact"
                            prepend-icon="mdi-microsoft-excel"
                            :loading="descargandoFormatoEjemplo"
                            :disabled="descargandoFormatoEjemplo"
                            title="Descargar Formato de Importación"
                            @click="descargarFormatoImportacion"
                        >
                            Formato de Ejemplo
                        </v-btn>
                    </div>
                </v-card-title>

                <v-card-text class="pa-4">
                    <FormularioImportar
                        :datos="datosFormularioImportar"
                        :metodo-importar="metodoImportar"
                        :nombre-item="nombreItem"
                        @actualizar-listado="obtenerActivosFijos"
                        @cancelar-guardado="cancelarGuardadoImportar"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <DialogoConfirmacion
            v-model="mostradoDialogoConfirmacion"
            :mensaje="mensajeDialogoConfirmacion"
            :realizando-accion="realizandoAccion"
            @aceptar="funcionDialogoConfirmacion"
            @cancelar="cancelarAccion"
        />
    </v-row>
</template>
