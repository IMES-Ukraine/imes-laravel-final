<template>
    <div>
        <router-button :href="'/'">
            < проекти
        </router-button>
        <div class="sidebar__tags">
            <div class="sidebar__tags-item" v-for="field in tags">
                <a class="sidebar__tags-link" :href="'/#'+field.slug">{{ field.name }}</a>
            </div>
            <div class="sidebar__tags-item" v-if="tag">
                <div># {{tag}}</div>
            </div>
        </div>
    </div>
</template>

<script>
import RouterButton from "../../../fragmets/router-button"
import {TAGS} from "../../../../api/endpoints"

export default {
name: "project-form-sidebar",
    components: {RouterButton},
    data() {
        return {
            tags: []
        }
    },
    props: {
        tag: {
            type: String,
            require: false,
            default: null
        },
    },
    mounted() {
        this.$get(TAGS).then( response => {
            this.tags = response.data
        })
    }
}
</script>

<style scoped>
    #tags-result {
        padding: 30px;
    }
    #tag-result {
        padding: 0 30px;
    }
</style>
