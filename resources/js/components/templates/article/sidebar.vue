<template>
    <route-buttons-sidebar :routes="sidebarButtons"/>
</template>

<script>
import RouterButton from "../../fragmets/router-button";
import RouteButtonsSidebar from "../../fragmets/ul-buttons-sidebar";
export default {
    name: "article-sidebar",
    components: {RouteButtonsSidebar, RouterButton},
    data() {
        return {
            articleList: this.$store.state.articles,
            sidebarButtons: [
                //{ name: '< Проекти', path: '/' },
                { name: 'Створити запис', path: '/article/new' },
                { name: 'Контент план', path: '/content-plan/new' },
            ],
        }
    },
}
</script>

<style scoped>

</style>
