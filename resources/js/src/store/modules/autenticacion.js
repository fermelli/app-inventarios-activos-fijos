const autenticacionStore = {
    namespaced: true,
    state() {
        return {
            usuario: null,
        };
    },
    mutations: {
        setUsuario(state, usuario) {
            state.usuario = usuario;
        },
    },
    actions: {
        async login({ commit }, credenciales) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    commit("setUsuario", credenciales);

                    resolve(true);
                }, 1500);
            });
        },
    },
};

export default autenticacionStore;
