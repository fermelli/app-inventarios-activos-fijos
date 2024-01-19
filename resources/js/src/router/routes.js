import { LAYOUTS } from "../utils/constantes";

export default [
    {
        path: "/",
        name: "inicio",
        component: () => import("../views/principal/InicioVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
        },
    },
    {
        path: "/sobre-nosotros",
        name: "sobre-nosotros",
        component: () => import("../views/principal/SobreNosotrosVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
        },
    },
    {
        path: "/contacto-desarrollador",
        name: "contacto-desarrollador",
        component: () =>
            import("../views/principal/ContactoDesarrolladorVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
        },
    },
    {
        path: "/autenticacion/login",
        name: "login",
        component: () => import("../views/autenticacion/LoginVista.vue"),
        meta: {
            layout: LAYOUTS.blank,
            requiresAuth: false,
        },
    },
    {
        path: "/:pathMatch(.*)*",
        name: "no-encontrado",
        component: () => import("../views/NoEncontradoVista.vue"),
        meta: {
            layout: LAYOUTS.blank,
            requiresAuth: false,
        },
    },
];
