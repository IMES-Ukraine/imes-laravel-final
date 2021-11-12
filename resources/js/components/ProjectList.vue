<template>
    <v-content>

        <template v-slot:sidebar>
            <project-list-sidebar/>
        </template>

        <div class="articles">
            <div class="articles_list" v-if="hasProjects()">
                <project-list-card
                    v-for="(project) in projectList"
                    v-bind:key="project.id"
                    :id="project.id"
                    :title="project.options.title"
                    :options="project.options"
                    :items="project.items"
                    :tag="(project.tags)?'#'+project.tags.project_tags.slug:''"
                />
            </div>
            <!--<v-preloader v-else />-->
        </div>

    </v-content>
</template>
<script>
    import VContent from "./templates/Content";
    import ProjectListSidebar from "./templates/project/list/sidebar";
    import {PROJECT} from "../api/endpoints"
    import ProjectListCard from "./templates/project/list/card";
    import VPreloader from "./fragmets/preloader";
    export default {
        name: 'ProjectList',
        components: {VPreloader, ProjectListCard, ProjectListSidebar, VContent},
        data() {
            return {
                projectList: this.$store.state.projects,
            }
        },
        computed: {
        },
        methods: {
            hasProjects () {
                return !!Object.keys(this.projectList).length;
            },
            async loadProjects () {

                if (this.hasProjects()) {
                    return
                }

                this.$get(PROJECT).then( response => {

                    if (response.data) {

                        this.projectList = response.data
                    }
                })
            },
        },
        mounted() {
            this.loadProjects()
        }
    }
</script>
<style scoped>
  .input-file-label strong {
      font-size: 1.5em;
  }
  .smaller-text__article {
      font-size: 0.8rem;
  }
</style>
