<script>
import ArticuloService from "./../../services/articulos";
import { useToast } from "vue-toastification";
import FormularioArticulo from "./components/FormularioArticulo.vue";
import TablaDatosServidorArticulos from "./components/TablaDatosServidorArticulos.vue";
import vistaMixin from "../../mixins/vista.mixin";
import articulosMixin from "../../mixins/articulos.mixin";
import dialogoFormularioImportarMixin from "../../mixins/dialogo-formulario-importar.mixin";

export default {
    name: "ArticulosVista",
    components: {
        FormularioArticulo,
        TablaDatosServidorArticulos,
    },
    mixins: [vistaMixin, articulosMixin, dialogoFormularioImportarMixin],
    setup() {
        const toast = useToast();

        return { toast };
    },
    data() {
        return {
            nombreItem: "Artículo",
            pagina: 1,
            itemsPorPagina: parseInt(
                localStorage.getItem(`itemsPorPagina-${this.$route.name}`) ||
                    10,
            ),
            totalItems: 0,
            busqueda: null,
            metodoImportar: ArticuloService.importar,
            metodoFormatoImportacion: ArticuloService.formatoImportacion,
            tituloArchivoEjemploImportacion:
                "Formato de Importación de Artículos",
        };
    },
    methods: {
        async accionItem(accion) {
            if (!["eliminar", "desactivar", "activar"].includes(accion)) {
                return;
            }

            this.realizandoAccion = true;

            try {
                if (accion === "eliminar") {
                    await ArticuloService.destroy(this.itemSeleccionado.id);
                } else if (accion === "desactivar") {
                    await ArticuloService.softDestroy(this.itemSeleccionado.id);
                } else if (accion === "activar") {
                    await ArticuloService.restore(this.itemSeleccionado.id);
                }

                this.mostrarNotificacionAccionRealizada(accion);
                this.obtenerArticulos();
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
                unidad_id: null,
                ubicacion_id: null,
                codigo: null,
                nombre: null,
                cantidad: null,
                articulos_lotes: [],
            };
        },
    },
};
</script>

<template>
    <v-row>
        <v-col cols="12">
            <div class="d-flex flex-wrap align-center">
                <h2 class="text-h5">Artículos</h2>

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
                    @click="obtenerArticulos"
                />
            </div>
        </v-col>

        <v-col cols="12">
            <TablaDatosServidorArticulos
                :items="items"
                :total-items="totalItems"
                :cargando-items="cargandoItems"
                :exportando-items="exportandoArticulos"
                @mostrar-formulario="mostrarDialogoFormulario"
                @mostrar-confirmacion="mostrarDialogoConfirmacion"
                @cargar-items="obtenerArticulos"
                @exportar-pdf="exportarArticulosPdf"
                @exportar-excel="() => exportarArticulosExcel('Artículos')"
                @exportar-pdf-sin-paginacion="exportarArticulosPdfSinPaginacion"
            />
        </v-col>

        <v-dialog
            v-model="mostradoDialogoFormulario"
            width="440"
            persistent
            scrollable
        >
            <FormularioArticulo
                :datos="datosItem"
                :nombre-item="nombreItem"
                @actualizar-listado="obtenerArticulos"
                @cancelar-guardado="cancelarGuardado"
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
                nombre-item="Importación Artículos"
                @actualizar-listado="obtenerArticulos"
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
    </v-row>
</template>
