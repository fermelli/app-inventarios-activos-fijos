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
