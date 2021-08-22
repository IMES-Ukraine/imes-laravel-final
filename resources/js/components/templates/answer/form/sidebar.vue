<template>
    <router-link :to="{path:'/'}">
        <router-button>
            < проекты
        </router-button>
    </router-link>
</template>

<script>
import RouterButton from "../../../fragmets/router-button";
export default {
name: "project-form-sidebar",
    components: {RouterButton}
}
</script>

<style scoped>

</style>
