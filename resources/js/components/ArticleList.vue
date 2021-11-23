<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="articles">
            <div class="articles_list">
                <article-list-card
                    v-for="(article) in articles.data"
                    v-bind:key="article.id"
                    :id="article.id"
                    :title="article.title"
                    :views="article.views"
                    :callbacks="article.callbacks"
                    @update="UpdateList"
                />
            </div>
            <div class="articles_pagination center">
                <pagination :data="articles" @pagination-change-page="getResults"></pagination>
            </div>
            <!--<v-preloader v-else />-->
        </div>

    </v-content>
</template>
<script>
import VContent from "./templates/Content"
import ProjectListSidebar from "./templates/project/list/sidebar"
import {ARTICLE, ARTICLE_DESTROY} from "../api/endpoints"
import ArticleListCard from "./templates/article/list/card"
import VPreloader from "./fragmets/preloader"
import ArticleSidebar from "./templates/article/sidebar"

export default {
    name: 'ArticleList',
    components: {ArticleSidebar, VPreloader, ArticleListCard, ProjectListSidebar, VContent},
    data() {
        return {
            articles: {}
        }
    },
    methods: {
        hasArticles () {
            return !!Object.keys(this.posts).length;
        },
        getResults(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }

            this.$get(ARTICLE + '?page=' + page)
                .then(response => {
                    this.articles = response.data;
                });
        },
        UpdateList (id) {
            this.$delete(ARTICLE_DESTROY + id).then()
            this.getResults();
        }
    },
    created () {
        this.getResults();
    },
}
</script>
