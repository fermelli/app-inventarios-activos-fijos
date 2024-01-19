import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import store from "@/store";

const router = new createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    await store.dispatch("autenticacion/obtenerUsuarioAutenticado");

    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    const usuarioAutenticado =
        store.getters["autenticacion/usuarioAutenticado"];

    if (requiresAuth) {
        if (!usuarioAutenticado) {
            next({ name: "login", redirect: to.fullPath });
        } else {
            if (to.name === "login") {
                next({ name: "inicio", redirect: to.fullPath });
            } else {
                next();
            }
        }
    } else {
        if (usuarioAutenticado && to.name === "login") {
            next({ name: "inicio", redirect: to.fullPath });
        } else {
            next();
        }
    }
});

export default router;
