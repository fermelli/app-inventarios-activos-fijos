<script>
import BarraAplicacion from "@/components/BarraAplicacion.vue";
import MenuNavegacion from "@/components/MenuNavegacion.vue";

export default {
    name: "App",
    components: { BarraAplicacion, MenuNavegacion },
    data() {
        return {
            abiertoNavegacion: false,
        };
    },
};
</script>

<template>
    <v-app>
        <v-layout>
            <BarraAplicacion
                @abrir-cerrar-menu-navegacion="
                    abiertoNavegacion = !abiertoNavegacion
                "
            />

            <MenuNavegacion :abierto-navegacion="abiertoNavegacion" />

            <v-main>
                <v-container fluid>
                    <router-view />
                </v-container>
            </v-main>
        </v-layout>
    </v-app>
</template>
