<template>
    <div>
        <router-button :icon="'has-icon-plus'" :isActive="(this.$route.path === '/article/new')?true:false" :url="'/article/new'">
            Створити запис
        </router-button>
        <router-button :icon="'has-icon-plus'" :isActive="(this.$route.path === '/content-plan/new')?true:false" :url="'/content-plan/new'">
            Контент план
        </router-button>
    </div>
</template>

<script>
import RouterButton from "../../fragmets/router-button"

export default {
    name: "article-sidebar",
    components: {RouterButton},
    data() {
        return {
            articleList: this.$store.state.articles
        }
    },
}
</script>

<style scoped>

</style>
