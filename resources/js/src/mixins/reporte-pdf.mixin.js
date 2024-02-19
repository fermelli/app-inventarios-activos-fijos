import DialogoReportePdf from "../components/DialogoReportePdf.vue";

export default {
    components: {
        DialogoReportePdf,
    },
    data() {
        return {
            mostradoDialogoReportePdf: false,
            pdfSrc: "",
            metodoServicioObtenerReportePdf: async () =>
                new Promise((resolve, reject) => {
                    reject(
                        "No se ha definido el servicio para obtener el reporte PDF",
                    );
                }),
        };
    },
    methods: {
        async mostrarReportePdf() {
            try {
                const { data } = await this.metodoServicioObtenerReportePdf(
                    this.itemSeleccionado.id,
                );
                const datos = data?.datos;
                const mensaje = data?.mensaje;
                const { pdf } = datos;

                this.toast.success(mensaje || "Reporte PDF generado");
                this.pdfSrc = `data:application/pdf;base64,${pdf}`;
                this.mostradoDialogoReportePdf = true;
            } catch (error) {
                console.log(error);
            }
        },
    },
};
