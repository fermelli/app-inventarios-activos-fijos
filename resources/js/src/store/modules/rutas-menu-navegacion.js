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
                ruta.rolesAutorizados.includes(rolUsuarioAutenticado);

                if ("rutasHijas" in ruta) {
                    ruta.rutasHijas = ruta.rutasHijas.filter((rutaHija) =>
                        rutaHija.rolesAutorizados.includes(
                            rolUsuarioAutenticado,
                        ),
                    );
                }

                return ruta;
            });
        },
    },
};

export default rutasMenuNavegacionStore;
