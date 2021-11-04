<template>
    <div>
        <span @click="resetStorage()">
        <router-button :clickFunction=" 'resetStorage' " :url="'/project/new'">
            Створити проект
        </router-button>
        </span>
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
import ProjectMixin from "../../../../ProjectMixin";

export default {
    name: "project-list-sidebar",
    mixins: [ProjectMixin],
    components: {RouterButton},
    data() {
        return {
            tags: []
        }
    },
    mounted() {
        this.$get(TAGS).then(response => {
            console.log(response.data);
            this.tags = response.data
        })
    },
    methods: {
        resetStorage() {
            this.$store.state.currentStep = 1;
            sessionStorage.project = '';
        }
    }
}
</script>
