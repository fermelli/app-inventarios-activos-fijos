import FormularioImportar from "../components/FormularioImportar.vue";

export default {
    components: {
        FormularioImportar,
    },
    data() {
        return {
            mostradoDialogoFormularioImportar: false,
            datosFormularioImportar: this.crearDatosFormularioImportar(),
            metodoImportar: () =>
                Promise.reject("No se ha definido el método importar"),
            metodoFormatoImportacion: () =>
                Promise.reject(
                    "No se ha definido el método formatoImportacion",
                ),
            tituloArchivoEjemploImportacion:
                "Formato de Ejemplo de Importación",
        };
    },
    methods: {
        crearDatosFormularioImportar() {
            return {
                archivos: [],
            };
        },
        cancelarGuardadoImportar() {
            this.mostradoDialogoFormularioImportar = false;
            this.datosFormularioImportar = this.crearDatosFormularioImportar();
        },
    },
};
