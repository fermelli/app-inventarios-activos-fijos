<script>
export default {
    name: "App",
};
</script>

<template>
    <v-app>
        <h1>Hola desde Vue</h1>

        <p>
            <router-link to="/">Inicio</router-link> |
            <router-link to="/sobre-nosotros">Sobre Nosotros</router-link> |
            <router-link to="/contacto-desarrollador">
                Contacto Desarrollador
            </router-link>
        </p>

        <router-view />
    </v-app>
</template>
