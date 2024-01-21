import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import store from "@/store";

const router = new createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    await store.dispatch("autenticacion/obtenerUsuarioAutenticado");

    const requiresAuth = to.matched.some((record) => record.meta?.requiresAuth);
    const usuarioAutenticado =
        store.getters["autenticacion/usuarioAutenticado"];
    const rutaLogin = { name: "login", redirect: to.fullPath };
    const rutaInicio = { name: "inicio", redirect: to.fullPath };
    const rutaNoAutorizado = { name: "no-autorizado", redirect: to.fullPath };

    if (requiresAuth) {
        if (!usuarioAutenticado) {
            next(rutaLogin);
        } else {
            if (to.name === "login") {
                next(rutaInicio);
            } else {
                if (to.meta.rolesAutorizados.includes(usuarioAutenticado.rol)) {
                    next();
                } else {
                    next(rutaNoAutorizado);
                }
            }
        }
    } else {
        if (usuarioAutenticado && to.name === "login") {
            next(rutaInicio);
        } else {
            next();
        }
    }
});

export default router;
