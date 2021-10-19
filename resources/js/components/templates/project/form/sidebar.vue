<template>
    <div>
        <router-button :href="'/'">
            < проекти
        </router-button>
        <div class="sidebar__tags">
            <a class="sidebar__tags-link" v-for="field in tags">#{{ field.name }}</a>
        </div>
        <div id="tag-result" v-if="tag">
            <div># {{tag}}</div>
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
            default: ''
        },
    },
    mounted() {
        this.$get(TAGS).then( response => { console.log(response.data);
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
