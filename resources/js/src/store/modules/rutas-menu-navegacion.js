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
