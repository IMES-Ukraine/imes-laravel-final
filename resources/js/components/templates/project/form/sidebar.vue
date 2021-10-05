<template>
    <div>
        <router-button :href="'/'">
            < проекти
        </router-button>
        <div id="tag-result" v-if="tag">
            <div># {{tag}}</div>
        </div>
    </div>
</template>

<script>
import RouterButton from "../../../fragmets/router-button";
export default {
name: "project-form-sidebar",
    components: {RouterButton},
    props: {
        tag: {
            type: String,
            require: false,
            default: ''
        },
    }
}
</script>

<style scoped>

</style>
