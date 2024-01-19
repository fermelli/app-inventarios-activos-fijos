import axios from "axios";
import store from "@/store";

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
    function (error) {
        if (
            error.response &&
            [401, 419].includes(error.response.status) &&
            store.getters["autenticacion/usuarioAutenticado"]
        ) {
            store.dispatch("auth/logout");
        }
        return Promise.reject(error);
    },
);

export default service;
