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
import exportarArticulosMixin from "../../mixins/exportar-articulos.mixin";
import ActivosFijosService from "./../../services/activos-fijos";
import FormularioGeneracionQr from "./components/FormularioGeneracionQr.vue";

export default {
    name: "ActivosFijosVista",
    components: {
        TablaDatosServidorActivosFijos,
        FormularioActivoFijo,
        FormularioAsignacionActivoFijo,
        FormularioDevolucionActivoFijo,
        TablaAsignacionesActivosFijos,
        FormularioDarBajaActivoFijo,
        FormularioGeneracionQr,
    },
    mixins: [
        vistaMixin,
        dialogoFormularioImportarMixin,
        exportarArticulosMixin,
    ],
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
            metodoServicioObtenerExportarExcel: ActivosFijosService.exportar,
            metodoServicioObtenerReportePdf: ActivosFijosService.showReportePdf,
            mostradoDialogoFormularioGeneracionQr: false,
        };
    },
    computed: {
        listadoDatos() {
            if (!this.itemSeleccionado) {
                return [];
            }

            return [
                {
                    titulo: "Código",
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
        mostrarFormularioGeneracionQR(item) {
            this.datosItem = item;
            this.mostradoDialogoFormularioGeneracionQr = true;
        },
        cancelarGuardadoGeneracionQr() {
            this.mostradoDialogoFormularioGeneracionQr = false;
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex flex-wrap align-center">
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
                @exportar-pdf="exportarArticulosPdf"
                @exportar-excel="() => exportarArticulosExcel('Activos Fijos')"
                @exportar-pdf-sin-paginacion="exportarArticulosPdfSinPaginacion"
                @mostrar-formulario-generacion-qr="
                    mostrarFormularioGeneracionQR
                "
            />
        </v-col>

        <v-dialog
            v-model="mostradoDialogoFormulario"
            width="800"
            persistent
            scrollable
        >
            <FormularioActivoFijo
                :datos="datosItem"
                :nombre-item="nombreItem"
                @actualizar-listado="obtenerActivosFijos"
                @cancelar-guardado="cancelarGuardado"
            />
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioAsignacion"
            width="800"
            persistent
            scrollable
        >
            <FormularioAsignacionActivoFijo
                :datos="asignacionActivoFijo"
                :nombre-item="`Asignación ${nombreItem}`"
                @actualizar-listado="obtenerActivosFijos"
                @cancelar-guardado="cancelarGuardadoAsignacionActivoFijo"
            />
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioDevolucion"
            width="800"
            persistent
            scrollable
        >
            <FormularioDevolucionActivoFijo
                :datos="devolucionActivoFijo"
                :nombre-item="`Devolución ${nombreItem}`"
                @actualizar-listado="obtenerActivosFijos"
                @cancelar-guardado="cancelarGuardadoDevolucionActivoFijo"
            />
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoMostrarItem"
            width="100%"
            persistent
            scrollable
        >
            <v-card>
                <v-card-title>
                    <div
                        class="d-flex flex-wrap justify-space-between align-center"
                    >
                        <span class="text-h6">{{ nombreItem }}</span>

                        <v-btn
                            class="my-1"
                            color="blue-grey"
                            density="compact"
                            variant="text"
                            icon="mdi-close"
                            title="Cerrar"
                            @click="mostradoDialogoMostrarItem = false"
                        />
                    </div>
                </v-card-title>

                <v-card-text class="pa-4">
                    <v-row dense>
                        <v-col
                            v-for="(dato, indice) in listadoDatos"
                            :key="indice"
                            class="py-0"
                            cols="12"
                            lg="3"
                        >
                            <v-list lines="two" class="py-0" density="compact">
                                <v-list-item
                                    class="py-0"
                                    density="compact"
                                    :title="dato.titulo"
                                    :subtitle="dato.subtitulo"
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12" lg="6" class="py-0">
                            <v-list
                                lines="three"
                                class="py-0"
                                density="compact"
                            >
                                <v-list-item
                                    class="py-0"
                                    density="compact"
                                    title="Descripción"
                                    :subtitle="
                                        itemSeleccionado?.descripcion || '-'
                                    "
                                />
                            </v-list>
                        </v-col>

                        <v-col cols="12" lg="6" class="py-0">
                            <v-list
                                lines="three"
                                class="py-0"
                                density="compact"
                            >
                                <v-list-item
                                    class="py-0"
                                    density="compact"
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
                    <div
                        class="d-flex flex-wrap justify-space-between align-center"
                    >
                        <v-btn
                            class="ma-1"
                            color="blue-grey"
                            density="compact"
                            prepend-icon="mdi-close"
                            title="Cerrar"
                            @click="mostradoDialogoMostrarItem = false"
                        >
                            Cerrar
                        </v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioDarBaja"
            width="800"
            persistent
            scrollable
        >
            <FormularioDarBajaActivoFijo
                :datos="datosItem"
                :nombre-item="`Dar Baja ${nombreItem}`"
                @actualizar-listado="obtenerActivosFijos"
                @cancelar-guardado="cancelarGuardadoDarBajaActivoFijo"
            />
        </v-dialog>

        <v-dialog
            v-model="mostradoDialogoFormularioImportar"
            width="560"
            persistent
            scrollable
        >
            <FormularioImportar
                :datos="datosFormularioImportar"
                :metodo-importar="metodoImportar"
                :metodo-formato-importacion="metodoFormatoImportacion"
                :titulo-archivo-ejemplo-importacion="
                    tituloArchivoEjemploImportacion
                "
                nombre-item="Importación Activos Fijos"
                @actualizar-listado="obtenerActivosFijos"
                @cancelar-guardado="cancelarGuardadoImportar"
            />
        </v-dialog>

        <DialogoConfirmacion
            v-model="mostradoDialogoConfirmacion"
            :mensaje="mensajeDialogoConfirmacion"
            :realizando-accion="realizandoAccion"
            @aceptar="funcionDialogoConfirmacion"
            @cancelar="cancelarAccion"
        />

        <DialogoReportePdf
            v-model="mostradoDialogoReportePdf"
            :pdf-src="pdfSrc"
            @cerrar="mostradoDialogoReportePdf = false"
        />

        <v-dialog
            v-model="mostradoDialogoFormularioGeneracionQr"
            width="500"
            persistent
            scrollable
        >
            <FormularioGeneracionQr
                :datos="datosItem"
                :nombre-item="`Generación de Código QR ${nombreItem}`"
                @actualizar-listado="obtenerActivosFijos"
                @cancelar-guardado="cancelarGuardadoGeneracionQr"
            />
        </v-dialog>
    </v-row>
</template>
