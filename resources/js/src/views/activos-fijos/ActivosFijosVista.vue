<script>
import ActivoFijoService from "./../../services/activos-fijos";
import { useToast } from "vue-toastification";
import TablaDatosServidorActivosFijos from "./components/TablaDatosServidorActivosFijos.vue";
import FormularioActivoFijo from "./components/FormularioActivoFijo.vue";
import vistaMixin from "../../mixins/vista.mixin";

export default {
    name: "ActivosFijosVista",
    components: {
        TablaDatosServidorActivosFijos,
        FormularioActivoFijo,
    },
    mixins: [vistaMixin],
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
        };
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
                institucion_id: null,
                codigo: null,
                nombre: null,
                descripcion: null,
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

        <DialogoConfirmacion
            v-model="mostradoDialogoConfirmacion"
            :mensaje="mensajeDialogoConfirmacion"
            :realizando-accion="realizandoAccion"
            @aceptar="funcionDialogoConfirmacion"
            @cancelar="cancelarAccion"
        />
    </v-row>
</template>
