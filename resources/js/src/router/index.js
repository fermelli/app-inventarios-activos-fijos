import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import store from "@/store";
import { ROLES } from "../utils/constantes";

const router = new createRouter({
    history: createWebHistory(),
    routes,
});

router.afterEach(() => {
    const $appSpinner = document.getElementById("app-loading");

    if ($appSpinner) {
        $appSpinner.style.display = "none";
    }
});

router.beforeEach(async (to, from, next) => {
    const requiresAuth = to.matched.some((record) => record.meta?.requiresAuth);
    const usuarioAutenticado =
        store.getters["autenticacion/usuarioAutenticado"];
    const rutaLogin = { name: "login", redirect: to.fullPath };
    const rutaInicio = { name: "inicio", redirect: to.fullPath };
    const rutaNoAutorizado = { name: "no-autorizado", redirect: to.fullPath };
    const esRutaLogin = to.name === "login";
    const esRutaRegistrarse = to.name === "registrarse";
    const esRutaNoAutorizado = to.name === "no-autorizado"; // TODO: revisar si aplicar esto

    if (requiresAuth && !usuarioAutenticado) {
        await store.dispatch("autenticacion/obtenerUsuarioAutenticado");

        const usuarioAutenticado =
            store.getters["autenticacion/usuarioAutenticado"];

        if (!usuarioAutenticado) {
            next(rutaLogin);
        } else {
            if (esRutaLogin) {
                next(rutaInicio);
            } else {
                !to.meta?.rolesAutorizados?.includes(usuarioAutenticado.rol)
                    ? next(rutaNoAutorizado)
                    : next();
            }
        }
    } else {
        if (esRutaLogin || esRutaNoAutorizado || esRutaRegistrarse) {
            await store.dispatch("autenticacion/obtenerUsuarioAutenticado");

            const usuarioAutenticado =
                store.getters["autenticacion/usuarioAutenticado"];

            if (usuarioAutenticado) {
                if (usuarioAutenticado.rol === ROLES.administrador) {
                    next(rutaInicio);
                } else {
                    next({ name: "solicitudes-usuario" });
                }
            } else {
                next();
            }
        } else {
            next();
        }
    }
});

export default router;
