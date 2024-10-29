import { POSITION } from "vue-toastification";

export const LAYOUTS = {
    app: "app-layout",
    blank: "blank-layout",
};

export const VUE_TOASTIFICATION_OPTIONS = {
    position: POSITION.BOTTOM_RIGHT,
    timeout: 3000,
    container: document.body,
    newestOnTop: true,
    maxToasts: 5,
};

export const ROLES = {
    administrador: "administrador",
    personal: "personal",
};

export const ESTADOS_SOLICITUDES = {
    pendiente: "pendiente",
    aprobada: "aprobada",
    rechazada: "rechazada",
    entregada: "entregada",
    anulada: "anulada",
};

export const COLORES_ESTADOS_SOLICITUDES = {
    pendiente: "warning",
    aprobada: "success",
    rechazada: "error",
    entregada: "primary",
    anulada: "secondary",
};

export const ESTADOS_ENTRADAS = {
    valida: "valida",
    anulada: "anulada",
};

export const COLORES_ESTADOS_ENTRADAS = {
    valida: "success",
    anulada: "error",
};

export const ESTADOS_ACTIVOS_FIJOS = {
    activo: "activo",
    en_mantenimiento: "en mantenimiento",
    de_baja: "de baja",
};

export const COLORES_ESTADOS_ACTIVOS_FIJOS = {
    activo: "success",
    "en mantenimiento": "warning",
    "de baja": "error",
};

export const CODIGO_ERRORES = {
    ERR_FR_TOO_MANY_REDIRECTS: "ERR_FR_TOO_MANY_REDIRECTS",
    ERR_BAD_OPTION_VALUE: "ERR_BAD_OPTION_VALUE",
    ERR_BAD_OPTION: "ERR_BAD_OPTION",
    ERR_NETWORK: "ERR_NETWORK",
    ERR_DEPRECATED: "ERR_DEPRECATED",
    ERR_BAD_RESPONSE: "ERR_BAD_RESPONSE",
    ERR_BAD_REQUEST: "ERR_BAD_REQUEST",
    ERR_NOT_SUPPORT: "ERR_NOT_SUPPORT",
    ERR_INVALID_URL: "ERR_INVALID_URL",
    ERR_CANCELED: "ERR_CANCELED",
    ECONNABORTED: "ECONNABORTED",
    ETIMEDOUT: "ETIMEDOUT",
};

export const CODIGOS_ESTADO_HTTP_ADICIONALES = {
    PageExpired: 419,
};
