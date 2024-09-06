import DialogoConfirmacion from "../components/DialogoConfirmacion.vue";

export default {
    components: {
        DialogoConfirmacion,
    },
    data() {
        return {
            items: [],
            cargandoItems: false,
            itemSeleccionado: null,
            datosItem: this.crearDatosItem(),
            mostradoDialogoFormulario: false,
            mensajeDialogoConfirmacion: "",
            mostradoDialogoConfirmacion: false,
            funcionDialogoConfirmacion: () => {},
            realizandoAccion: false,
        };
    },
    methods: {
        mostrarDialogoFormulario(item) {
            this.datosItem = item
                ? {
                      ...item,
                  }
                : this.crearDatosItem();
            this.mostradoDialogoFormulario = true;
        },
        cancelarGuardado() {
            this.mostradoDialogoFormulario = false;
            this.datosItem = this.crearDatosItem();
        },
        mostrarDialogoConfirmacion(item, accion, identificadorItem) {
            switch (accion) {
                case "eliminar":
                    this.mensajeDialogoConfirmacion = `
                        ¿Está seguro de eliminar la ${this.nombreItem}
                        <strong>${identificadorItem}</strong>? Esta acción no se puede deshacer.
                    `;
                    break;
                case "desactivar":
                    this.mensajeDialogoConfirmacion = `
                        ¿Está seguro de desactivar la ${this.nombreItem}
                        <strong>${identificadorItem}</strong>?
                    `;
                    break;
                case "activar":
                    this.mensajeDialogoConfirmacion = `
                        ¿Está seguro de activar la ${this.nombreItem}
                        <strong>${identificadorItem}</strong>?
                    `;
                    break;
                default:
                    break;
            }
            this.funcionDialogoConfirmacion = () => this.accionItem(accion);
            this.itemSeleccionado = item;
            this.mostradoDialogoConfirmacion = true;
        },
        mostrarNotificacionAccionRealizada(accion) {
            const accionRealizada =
                accion === "eliminar"
                    ? "eliminada"
                    : accion === "desactivar"
                      ? "desactivada"
                      : "activada";

            this.toast.success(
                `${this.nombreItem} ${accionRealizada} exitosamente`,
            );
        },
        cancelarAccion() {
            this.mostradoDialogoConfirmacion = false;
            this.mensajeDialogoConfirmacion = null;
            this.funcionDialogoConfirmacion = () => {};
        },
    },
};
