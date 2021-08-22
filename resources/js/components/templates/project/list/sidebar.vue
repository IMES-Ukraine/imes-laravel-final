<template>
    <router-link :to="{path:'/project/new'}">
        <router-button>
            Створити проект
        </router-button>
    </router-link>
</template>

<script>
import RouterButton from "../../../fragmets/router-button";
export default {
    name: "project-list-sidebar",
    components: {RouterButton}
}
</script>
