import { ROLES } from "../../utils/constantes";

const rutasMenuNavegacionStore = {
    namespaced: true,
    state() {
        return {
            rutas: [
                {
                    to: { name: "inicio" },
                    icono: "mdi-home",
                    texto: "Inicio",
                    rolesAutorizados: [ROLES.administrador, ROLES.personal],
                },
                {
                    to: { name: "dashboard" },
                    icono: "mdi-view-dashboard",
                    texto: "Dashboard",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "categorias" },
                    icono: "mdi-tag",
                    texto: "Categorías",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "unidades" },
                    icono: "mdi-ruler",
                    texto: "Unidades",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "ubicaciones" },
                    icono: "mdi-library-shelves",
                    texto: "Ubicaciones",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "articulos" },
                    icono: "mdi-package-variant-closed",
                    texto: "Artículos",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "instituciones" },
                    icono: "mdi-domain",
                    texto: "Instituciones",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "usuarios" },
                    icono: "mdi-account-group",
                    texto: "Usuarios",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "entradas" },
                    icono: "mdi-arrow-right",
                    texto: "Entradas",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "solicitudes" },
                    icono: "mdi-file-document-multiple-outline",
                    texto: "Solicitudes",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "solicitudes-usuario" },
                    icono: "mdi-file-document-outline",
                    texto: "Mis Solicitudes",
                    rolesAutorizados: [ROLES.administrador, ROLES.personal],
                },
                {
                    to: { name: "salidas" },
                    icono: "mdi-arrow-left",
                    texto: "Salidas",
                    rolesAutorizados: [ROLES.administrador],
                },
                {
                    to: { name: "salidas-usuario" },
                    icono: "mdi-clipboard-arrow-left-outline",
                    texto: "Mis Salidas",
                    rolesAutorizados: [ROLES.administrador, ROLES.personal],
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
            ],
        };
    },
    getters: {
        rutasMenuNavegacion(state) {
            return state.rutas;
        },
        rutasPermitidasPorRol(state, getters, rootState, rootGetters) {
            const rolUsuarioAutenticado =
                rootGetters["autenticacion/usuarioAutenticado"]?.rol;

            if (!rolUsuarioAutenticado) {
                return [];
            }

            return state.rutas.filter((ruta) => {
                return ruta.rolesAutorizados.includes(rolUsuarioAutenticado);
            });
        },
    },
};

export default rutasMenuNavegacionStore;
