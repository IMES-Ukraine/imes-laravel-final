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
                    @update="UpdateList"
                />
                <div class="clearfix btn-group col-md-2 offset-md-5">
                    <button type="button" class="btn btn-sm btn-outline-secondary" v-if="page != 1" @click="page--"> << </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" v-for="pageNumber in pages.slice(page-1, page+5)" @click="page = pageNumber"> {{pageNumber}} </button>
                    <button type="button" @click="page++" v-if="page < pages.length" class="btn btn-sm btn-outline-secondary"> >> </button>
                </div>
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
            articleList: [],
            page: 1,
            perPage: 9,
            pages: [],
        }
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
                }
            })
        },
        setPages () {
            let numberOfPages = Math.ceil(this.articleList.length / this.perPage);
            for (let index = 1; index <= numberOfPages; index++) {
                this.pages.push(index);
            }
        },
        UpdateList (id) {
            this.$delete(ARTICLE_DESTROY + id).then()

            for (const [index, value] of Object.entries(this.articleList)) {
                if (value.id == id) {
                    this.articleList.splice(index, 1)
                }
            }
        }
    },
    mounted() {
        this.loadArticles()
    },
    watch: {
        posts () {
            this.setPages();
        }
    }
}
</script>
