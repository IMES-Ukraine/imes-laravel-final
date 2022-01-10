<template>
    <v-content>

        <template v-slot:sidebar>
            <project-list-sidebar/>
        </template>

        <div class="articles">
            <div class="articles_list">
                <project-list-card
                    v-for="(project) in projectList.data"
                    v-bind:key="project.id"
                    :id="project.id"
                    :title="project.options.title"
                    :options="project.options"
                    :items="project.items"
                />
            </div>
            <div class="articles_pagination center">
                <pagination :data="projectList" @pagination-change-page="getResults"></pagination>
            </div>
            <!--<v-preloader v-else />-->
        </div>

    </v-content>
</template>
<script>
    import VContent from "./templates/Content";
    import ProjectListSidebar from "./templates/project/list/sidebar";
    import {ADMIN_PROJECT} from "../api/endpoints"
    import ProjectListCard from "./templates/project/list/card";
    import VPreloader from "./fragmets/preloader";
    export default {
        name: 'ProjectList',
        components: {VPreloader, ProjectListCard, ProjectListSidebar, VContent},
        data() {
            return {
                projectList: {}
            }
        },
        computed: {
        },
        methods: {
            hasProjects () {
                return !!Object.keys(this.projectList).length;
            },
            getResults(page) {

                let tag = '';
                if (typeof page === 'undefined') {
                    page = 1;
                }

                /*if (this.$route.params.tag) {
                    tag = '&tag=' + this.$route.params.tag;
                }*/
                if (this.$route.query.tag) {
                    tag = '&tag=' + this.$route.query.tag;
                }


                this.$get(ADMIN_PROJECT + '?page=' + page + tag)
                    .then(response => {
                        this.projectList = response.data;
                    });
            },
        },
        mounted() {
            this.getResults();
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
