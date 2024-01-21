<script>
import { mapGetters } from "vuex";

export default {
    name: "MenuNavegacion",
    props: {
        abiertoNavegacion: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            abierto: this.abiertoNavegacion,
        };
    },
    computed: {
        ...mapGetters("rutasMenuNavegacion", ["rutasPermitidasPorRol"]),
    },
    watch: {
        abiertoNavegacion(valor) {
            this.abierto = valor;
        },
    },
};
</script>

<template>
    <v-navigation-drawer v-model="abierto" location="left">
        <v-list open-strategy="multiple">
            <v-list-subheader>Menú Principal</v-list-subheader>

            <v-divider />

            <v-list-item
                v-for="(ruta, indice) in rutasPermitidasPorRol"
                :key="indice"
                link
                :to="ruta.to"
            >
                <template #prepend>
                    <v-icon :icon="ruta.icono" />
                </template>

                <v-list-item-title>{{ ruta.texto }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
