<template>
    <v-content>
        <template slot="sidebar">
            <route-buttons-sidebar :routes="sidebarButtons"/>
        </template>

    </v-content>
</template>

<script>
import VContent from "./templates/Content";
import RouteButtonsSidebar from "./fragmets/ul-buttons-sidebar";
export default {
    name: "ClientsList",
    components: {VContent, RouteButtonsSidebar},
    data() {
        return {
            sidebarButtons: [
                { name: 'Запити', path: '/requests' },
                { name: 'Користувачi', path: '/clients' },
                { name: 'Обмiн балами', path: '/exchange' },
            ],
        }
    }
}
</script>
