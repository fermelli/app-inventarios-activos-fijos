import FormularioImportar from "../components/FormularioImportar.vue";

export default {
    components: {
        FormularioImportar,
    },
    data() {
        return {
            mostradoDialogoFormularioImportar: false,
            datosFormularioImportar: this.crearDatosFormularioImportar(),
            descargandoFormatoEjemplo: false,
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
        async descargarFormatoImportacion() {
            this.descargandoFormatoEjemplo = true;

            try {
                const { data } = await this.metodoFormatoImportacion();
                const blob = new Blob([data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const link = document.createElement("a");

                link.href = window.URL.createObjectURL(blob);
                link.download = `${this.tituloArchivoEjemploImportacion}.xlsx`;
                link.click();
            } catch (error) {
                console.log(error);

                const mensaje =
                    error?.response?.data?.message ||
                    error.message ||
                    error ||
                    "Error al descargar el formato de ejemplo";

                this.toast.error(mensaje);
            } finally {
                this.descargandoFormatoEjemplo = false;
            }
        },
    },
};
