import axios from "axios";
import store from "@/store";
import { useToast } from "vue-toastification";

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
            toast.error("Error de respuesta del servidor.");
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
        } else {
            toast.error("Se produjo un error inesperado");
        }

        return Promise.reject(error);
    },
);

export default service;
