export default {
    props: {
        items: {
            type: Array,
            required: true,
        },
        totalItems: {
            type: Number,
            required: true,
        },
        cargandoItems: {
            type: Boolean,
            required: true,
        },
        exportandoItems: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            q: null,
            retardo: null,
            busqueda: null,
            paginaActual: 1,
            itemsPorPagina:
                Number(
                    localStorage.getItem(`itemsPorPagina-${this.$route.name}`),
                ) || 10,
            itemsPorPaginaOpciones: [
                { value: 10, title: "10" },
                { value: 15, title: "15" },
                { value: 30, title: "30" },
                { value: 50, title: "50" },
            ],
        };
    },
    methods: {
        actualizarItemsPorPagina(itemsPorPagina) {
            localStorage.setItem(
                `itemsPorPagina-${this.$route.name}`,
                itemsPorPagina,
            );

            this.itemsPorPagina = itemsPorPagina;
        },
        buscar() {
            clearTimeout(this.retardo);

            this.retardo = setTimeout(() => {
                this.busqueda = this.q;
            }, 500);
        },
    },
};
