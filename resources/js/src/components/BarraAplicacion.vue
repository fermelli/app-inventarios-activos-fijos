<script>
export default {
    name: "BarraAplicacion",
    emits: ["abrirCerrarMenuNavegacion"],
    methods: {
        abrirCerrarMenuNavegacion() {
            this.$emit("abrirCerrarMenuNavegacion");
        },
    },
};
</script>

<template>
    <v-app-bar color="primary">
        <v-app-bar-nav-icon @click="abrirCerrarMenuNavegacion" />

        <v-app-bar-title>CASEGURAL</v-app-bar-title>

        <v-spacer />

        <v-menu location="bottom">
            <template #activator="{ props }">
                <v-btn v-bind="props" icon>
                    <v-avatar size="45" color="primary">
                        <v-icon icon="mdi-account-circle" />
                    </v-avatar>
                </v-btn>
            </template>

            <v-list>
                <v-list-item>
                    <v-list-item-title>
                        omartaboada1990@hotmail.com
                    </v-list-item-title>
                </v-list-item>

                <v-divider />

                <v-list-item link>
                    <v-list-item-title> Perfil de usuario </v-list-item-title>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-title>Cerrar sesión</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-app-bar>
</template>
