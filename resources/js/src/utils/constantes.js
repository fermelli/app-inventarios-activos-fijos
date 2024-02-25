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
    en_mantenimiento: "warning",
    de_baja: "error",
};
