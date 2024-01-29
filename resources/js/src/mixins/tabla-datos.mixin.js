export default {
    props: {
        items: {
            type: Array,
            required: true,
        },
        cargandoItems: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            busqueda: null,
            itemsPorPaginaOpciones: [
                { value: 10, title: "10" },
                { value: 15, title: "15" },
                { value: 30, title: "30" },
                { value: 50, title: "50" },
                { value: -1, title: "Todos" },
            ],
            itemsPorPagina:
                Number(
                    localStorage.getItem(`itemsPorPagina-${this.$route.name}`),
                ) || 10,
            paginaActual: 1,
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
    },
};
