<template>
    <v-content>

        <template v-slot:sidebar>
            <project-list-sidebar/>
        </template>

        <!--<div class="col">
            <div class="col-sm-12">
                <div class="row" v-if="hasProjects()">
                    <project-list-card
                        v-for="(project) in projectList"
                        v-bind:key="project.id"
                        :id="project.id"
                        :desc="project.options.title"
                    />
                </div>
                <v-preloader v-else />
            </div>
        </div>-->
        <div class="articles">
            <div class="articles_list" v-if="hasProjects()">
                <project-list-card
                    v-for="(project) in projectList"
                    v-bind:key="project.id"
                    :id="project.id"
                    :desc="project.options.title"
                />

                <!--<div class="articles_list__block">
                    <div class="articles_list__block-content">
                        <div class="articles_list__block-img">
                            <img src="img/articles-logo-2.png" alt="">
                        </div>
                        <ul>
                            <li>Маркетинговоеисследование</li>
                        </ul>
                    </div>
                </div>
                <div class="articles_list__block">
                    <div class="articles_list__block-content">
                        <div class="articles_list__block-img">
                            <img src="img/articles-logo-3.png" alt="">
                        </div>
                        <ul>
                            <li>Проект повышения квалификации</li>
                        </ul>
                    </div>
                    <div class="articles_list__block-status">
                        <p class="done"><span>Проект завершен</span></p>
                    </div>
                </div>
                <div class="articles_list__block">
                    <div class="articles_list__block-content">
                        <div class="articles_list__block-img">
                            <img src="img/articles-logo-4.png" alt="">
                        </div>
                        <ul>
                            <li>Обучение</li>
                        </ul>
                    </div>
                </div>
                <div class="articles_list__block">
                    <div class="articles_list__block-content">
                        <div class="articles_list__block-img">
                            <img src="img/articles-logo-5.png" alt="">
                        </div>
                        <ul>
                            <li>Знания</li>
                            <li>Симптомы</li>
                        </ul>
                    </div>
                </div>
                <div class="articles_list__block">
                    <div class="articles_list__block-content">
                        <div class="articles_list__block-img">
                            <img src="img/articles-logo-5.png" alt="">
                        </div>
                        <ul>
                            <li>Исследование рынка</li>
                        </ul>
                    </div>
                </div>-->
            </div>
            <v-preloader v-else />
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
