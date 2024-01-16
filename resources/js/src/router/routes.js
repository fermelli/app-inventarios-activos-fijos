export default [
    {
        path: "/",
        name: "inicio",
        component: () => import("../views/principal/InicioVista.vue"),
    },
    {
        path: "/sobre-nosotros",
        name: "sobre-nosotros",
        component: () => import("../views/principal/SobreNosotrosVista.vue"),
    },
    {
        path: "/contacto-desarrollador",
        name: "contacto-desarrollador",
        component: () =>
            import("../views/principal/ContactoDesarrolladorVista.vue"),
    },
];
