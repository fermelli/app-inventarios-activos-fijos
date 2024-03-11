import axios from "axios";
import store from "@/store";
import { useToast } from "vue-toastification";
import ListaErroresValidacion from "@/components/ListaErroresValidacion.vue";
import router from "@/router";
import { ROLES } from "../utils/constantes";

const service = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    timeout: 10000,
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

service.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        const toast = useToast();

        if (error.code === "ERR_BAD_RESPONSE") {
            console.log(error.response);
            if (
                error.response &&
                error.response.data &&
                error.response.data.mensaje
            ) {
                toast.error(error.response.data.mensaje);
            } else {
                toast.error("Error de respuesta del servidor.");
            }
        } else if (error.code == "ECONNABORTED") {
            toast.error("Tiempo de espera agotado.");
        } else if (
            error.response &&
            [401, 419].includes(error.response.status)
        ) {
            if (store.getters["autenticacion/usuarioAutenticado"]) {
                store.dispatch("autenticacion/logout");
            }

            toast.error(
                "Su sesión ha expirado. Por favor, inicie sesión nuevamente.",
            );
        } else if (error.response && error.response.status === 422) {
            const { data } = error.response;

            if (data.errores && Object.keys(data.errores).length > 0) {
                const errores = Object.values(data.errores).flat();

                toast.error({
                    component: ListaErroresValidacion,
                    props: {
                        errores,
                    },
                });
            } else {
                toast.error(data.message);
            }
        } else if (
            error.response &&
            (error.response.status === 403 ||
                error.response.status === 400 ||
                error.response.status === 404)
        ) {
            toast.error(error.response.data.mensaje);

            if (error.response.status === 403) {
                const usuarioAutenticado =
                    store.getters["autenticacion/usuarioAutenticado"];

                if (usuarioAutenticado?.rol === ROLES.administrador) {
                    router.push({ name: "inicio" });
                } else if (usuarioAutenticado?.rol === ROLES.personal) {
                    router.push({ name: "solicitudes-usuario" });
                } else {
                    router.push({ name: "no-autorizado" });
                }
            }
        } else {
            toast.error("Se produjo un error inesperado");
        }

        return Promise.reject(error);
    },
);

export default service;
