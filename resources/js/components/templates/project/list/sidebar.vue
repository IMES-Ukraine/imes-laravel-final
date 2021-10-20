<template>
    <div>
        <router-button :url="'/project/new'">
            Створити проект
        </router-button>
        <div class="sidebar__tags">
            <div class="sidebar__tags-item" v-for="field in tags">
                <a class="sidebar__tags-link" :href="'#'+field.slug">{{ field.name }}</a>
            </div>
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
