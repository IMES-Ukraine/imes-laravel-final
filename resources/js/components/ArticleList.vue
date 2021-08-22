<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="col">
            <div class="col-sm-12">
                <div class="row" v-if="hasArticles()">
                    <project-list-card
                        v-for="(article) in articleList"
                        v-bind:key="article.id"
                        :id="article.id"
                        :desc="article.options.title"
                    />
                </div>
                <v-preloader v-else />
            </div>
        </div>

    </v-content>
</template>
<script>
import VContent from "./templates/Content";
import ProjectListSidebar from "./templates/project/list/sidebar";
import {ARTICLE} from "../api/endpoints"
import ProjectListCard from "./templates/project/list/card";
import VPreloader from "./fragmets/preloader";
import ArticleSidebar from "./templates/article/sidebar";
export default {
    name: 'ArticleList',
    components: {ArticleSidebar, VPreloader, ProjectListCard, ProjectListSidebar, VContent},
    data() {
        return {
            articleList: this.$store.state.articles,
        }
    },
    computed: {
    },
    methods: {
        hasArticles () {
            return !!Object.keys(this.articleList).length;
        },
        async loadArticles () {
            if (this.hasArticles()) {
                return
            }

            this.$get(ARTICLE).then( response => {

                if (response.data && response.data.length > 0) {
                    this.articleList = response.data
                }
            })
        },
    },
    mounted() {
        this.loadArticles()
    }
}
</script>
