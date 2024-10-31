import { ROLES } from "../utils/constantes";

export default [
    {
        to: { name: "inicio" },
        icono: "mdi-view-dashboard",
        texto: "Dashboard",
        rolesAutorizados: [ROLES.administrador],
    },
    // {
    //     to: null,
    //     icono: "mdi-store",
    //     texto: "Inventario",
    //     rolesAutorizados: [ROLES.administrador],
    //     rutasHijas: [
    //         {
    //             to: { name: "unidades" },
    //             icono: "mdi-ruler",
    //             texto: "Unidades",
    //             rolesAutorizados: [ROLES.administrador],
    //         },
    //         {
    //             to: { name: "articulos" },
    //             icono: "mdi-package-variant-closed",
    //             texto: "Artículos",
    //             rolesAutorizados: [ROLES.administrador],
    //         },
    //     ],
    // },
    // {
    //     to: null,
    //     icono: "mdi-store-check",
    //     texto: "Operaciones de Inventario",
    //     rolesAutorizados: [ROLES.administrador, ROLES.personal],
    //     rutasHijas: [
    //         {
    //             to: { name: "entradas" },
    //             icono: "mdi-arrow-right",
    //             texto: "Entradas",
    //             rolesAutorizados: [ROLES.administrador],
    //         },
    //         {
    //             to: { name: "solicitudes" },
    //             icono: "mdi-file-document-multiple-outline",
    //             texto: "Solicitudes",
    //             rolesAutorizados: [ROLES.administrador],
    //         },
    //         {
    //             to: { name: "solicitudes-usuario" },
    //             icono: "mdi-file-document-outline",
    //             texto: "Mis Solicitudes",
    //             rolesAutorizados: [ROLES.personal],
    //         },
    //         {
    //             to: { name: "salidas" },
    //             icono: "mdi-arrow-left",
    //             texto: "Salidas",
    //             rolesAutorizados: [ROLES.administrador],
    //         },
    //         {
    //             to: { name: "salidas-usuario" },
    //             icono: "mdi-clipboard-arrow-left-outline",
    //             texto: "Mis Salidas",
    //             rolesAutorizados: [ROLES.personal],
    //         },
    //     ],
    // },
    {
        to: { name: "activos-fijos" },
        icono: "mdi-archive-arrow-down",
        texto: "Activos Fijos",
        rolesAutorizados: [ROLES.administrador],
    },
    {
        to: null,
        icono: "mdi-office-building-cog",
        texto: "Organización",
        rolesAutorizados: [ROLES.administrador],
        rutasHijas: [
            {
                to: { name: "categorias" },
                icono: "mdi-tag",
                texto: "Categorías",
                rolesAutorizados: [ROLES.administrador],
            },
            {
                to: { name: "ubicaciones" },
                icono: "mdi-library-shelves",
                texto: "Ubicaciones",
                rolesAutorizados: [ROLES.administrador],
            },
            {
                to: { name: "instituciones" },
                icono: "mdi-domain",
                texto: "Instituciones",
                rolesAutorizados: [ROLES.administrador],
            },
        ],
    },
    {
        to: { name: "usuarios" },
        icono: "mdi-account-group",
        texto: "Usuarios",
        rolesAutorizados: [ROLES.administrador],
    },
    {
        to: { name: "sobre-nosotros" },
        icono: "mdi-information",
        texto: "Sobre Nosotros",
        rolesAutorizados: [ROLES.administrador, ROLES.personal],
    },
    {
        to: { name: "contacto-desarrollador" },
        icono: "mdi-account",
        texto: "Contacto Desarrollador",
        rolesAutorizados: [ROLES.administrador, ROLES.personal],
    },
];
