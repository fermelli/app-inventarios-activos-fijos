import rutasMenuNavegacion from "../../router/rutas-menu-navegacion";

const rutasMenuNavegacionStore = {
    namespaced: true,
    state() {
        return {
            rutas: rutasMenuNavegacion,
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
