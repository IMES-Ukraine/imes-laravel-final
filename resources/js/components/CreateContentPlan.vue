<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="article-edit card">
            <v-close :to="{name:'articleList'}"/>
        </div>

    </v-content>
</template>

<script>
import ArticleSidebar from "./templates/article/sidebar";
import VContent from "./templates/Content";
import VClose from "./templates/Close";
export default {
    name: "CreateContentPlan",
    components: {VClose, VContent, ArticleSidebar}
}
</script>

<style scoped>

</style>
