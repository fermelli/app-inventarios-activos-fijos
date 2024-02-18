export default {
    props: {
        nombreItem: {
            type: String,
            required: true,
        },
    },
    emits: ["actualizarListado", "cancelarGuardado"],
    data() {
        return {
            formulario: this.datos,
            formularioValido: false,
            guardandoItem: false,
            metodoStore: () =>
                Promise.reject("No se ha definido el método store"),
            metodoUpdate: () =>
                Promise.reject("No se ha definido el método update"),
        };
    },
    methods: {
        async guardarItem() {
            if (!this.formularioValido) {
                this.toast.error("Corrija los errores en el formulario");

                return;
            }

            this.guardandoItem = true;

            try {
                let response = null;

                if (this.formulario.id) {
                    response = await this.metodoUpdate(
                        this.formulario.id,
                        this.formulario,
                    );
                } else {
                    response = await this.metodoStore(this.formulario);
                }

                const data = response.data;
                const mensaje =
                    data?.mensaje || `${this.nombreItem} guardada exitosamente`;

                this.toast.success(mensaje);
                this.emitActualizarListado();
                this.emitCancelarGuardado();
            } catch (error) {
                console.log(error);
            } finally {
                this.guardandoItem = false;
            }
        },
        emitActualizarListado() {
            this.$emit("actualizarListado");
        },
        emitCancelarGuardado() {
            this.$emit("cancelarGuardado");
        },
    },
};
