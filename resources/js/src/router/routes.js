import { LAYOUTS, ROLES } from "../utils/constantes";

export default [
    {
        path: "/",
        name: "inicio",
        component: () => import("../views/principal/InicioVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
            rolesAutorizados: [ROLES.administrador, ROLES.personal],
        },
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: () => import("../views/principal/DashboardVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
            rolesAutorizados: [ROLES.administrador],
        },
    },
    {
        path: "/categorias",
        name: "categorias",
        component: () => import("../views/categorias/CategoriasVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
            rolesAutorizados: [ROLES.administrador],
        },
    },
    {
        path: "/unidades",
        name: "unidades",
        component: () => import("../views/unidades/UnidadesVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
            rolesAutorizados: [ROLES.administrador],
        },
    },
    {
        path: "/ubicaciones",
        name: "ubicaciones",
        component: () => import("../views/ubicaciones/UbicacionesVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
            rolesAutorizados: [ROLES.administrador],
        },
    },
    {
        path: "/articulos",
        name: "articulos",
        component: () => import("../views/articulos/ArticulosVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
            rolesAutorizados: [ROLES.administrador],
        },
    },
    {
        path: "/instituciones",
        name: "instituciones",
        component: () =>
            import("../views/instituciones/InstitucionesVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
            rolesAutorizados: [ROLES.administrador],
        },
    },
    {
        path: "/sobre-nosotros",
        name: "sobre-nosotros",
        component: () => import("../views/principal/SobreNosotrosVista.vue"),
        meta: {
            layout: LAYOUTS.app,
            requiresAuth: true,
            rolesAutorizados: [ROLES.administrador, ROLES.personal],
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
            rolesAutorizados: [ROLES.administrador, ROLES.personal],
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
        path: "/autenticacion/registrarse",
        name: "registrarse",
        component: () => import("../views/autenticacion/RegistrarseVista.vue"),
        meta: {
            layout: LAYOUTS.blank,
            requiresAuth: false,
        },
    },
    {
        path: "/no-autorizado",
        name: "no-autorizado",
        component: () => import("../views/NoAutorizadoVista.vue"),
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
