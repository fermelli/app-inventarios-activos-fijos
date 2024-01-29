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
                return;
            }

            this.guardandoItem = true;

            try {
                if (this.formulario.id) {
                    await this.metodoUpdate(
                        this.formulario.id,
                        this.formulario,
                    );
                } else {
                    await this.metodoStore(this.formulario);
                }

                this.toast.success(`${this.nombreItem} guardada exitosamente`);
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
