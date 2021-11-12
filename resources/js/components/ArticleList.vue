<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="articles">
            <div class="articles_list" v-if="hasArticles()">
                <article-list-card
                    v-for="(article) in articleList"
                    v-bind:key="article.id"
                    :id="article.id"
                    :title="article.title"
                    :views="article.views"
                    :callbacks="article.callbacks"
                />
            </div>
            <!--<v-preloader v-else />-->
        </div>

    </v-content>
</template>
<script>
import VContent from "./templates/Content"
import ProjectListSidebar from "./templates/project/list/sidebar"
import {ARTICLE} from "../api/endpoints"
import ArticleListCard from "./templates/article/list/card"
import VPreloader from "./fragmets/preloader"
import ArticleSidebar from "./templates/article/sidebar"

export default {
    name: 'ArticleList',
    components: {ArticleSidebar, VPreloader, ArticleListCard, ProjectListSidebar, VContent},
    data() {
        return {
            articleList: []//this.$store.state.articles,
        }
    },
    computed: {
    },
    methods: {
        hasArticles () {
            return !!Object.keys(this.articleList).length;
        },
        async loadArticles () {
            this.$get(ARTICLE).then( response => {

                if (response.data && response.data.data.length > 0) {
                    if (response.data) {
                        this.articleList = response.data.data
                    }
                    //this.$store.state.articles = response.data.data
                }
            })
        },
    },
    mounted() {
        this.loadArticles()
    }
}
</script>
