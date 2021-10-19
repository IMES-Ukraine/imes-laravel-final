<template>
    <div>
        <router-button :url="'/project/new'">
            Створити проект
        </router-button>
        <div class="sidebar__tags">
            <a class="sidebar__tags-link" v-for="field in tags">{{ field.name }}</a>
        </div>
    </div>
</template>

<script>
import RouterButton from "../../../fragmets/router-button";
import {TAGS} from "../../../../api/endpoints"

export default {
    name: "project-list-sidebar",
    components: {RouterButton},
    data() {
        return {
            tags: []
        }
    },
    mounted() {
        this.$get(TAGS).then( response => { console.log(response.data);
            this.tags = response.data
        })
    }
}
</script>
