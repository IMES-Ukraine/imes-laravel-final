<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="article-edit card">

            <v-close :to="{name:'createContent'}"/>

            <div class="card-body">

                <!-- article-title -->
                <article-input-title v-bind:type.sync="type" :v="$v"/>
                <!-- end-article-title -->

                <!-- article-cover -->
                <article-input-cover
                    v-bind:file.sync="images"
                    :file-key="'cover'"

                />
                <!-- end-article-cover -->

                <!-- article-content -->
                <article-input-text
                    :v="$v"
                    :title="'Текст статьи'"
                    :error="'Контент обязателен'"
                    v-bind:text.sync="$v.text.$model"
                />
                <!-- end-article-content -->

                <!-- article-insert -->
                <article-form-insert
                    :v="$v"
                    v-bind:insert.sync="insert"
                    v-bind:textInsert.sync="textInsert"
                />
                <!-- end-article-insert -->

                <!-- article-select-category -->
                <article-form-select
                    :name="'categories'"
                    :label="'Категории'"
                    v-bind:value.sync="category"
                />
                <!-- end-article-select-category -->

                <!-- article-select-headings -->
                <article-form-select
                    :name="'headings'"
                    :label="'Рубрики'"
                    v-bind:value.sync="headings"
                />
                <!-- end-article-select-headings -->

                <!-- article-select-authors -->
                <article-form-select
                    :name="'authors'"
                    :label="'Автор'"
                    v-bind:value.sync="author"
                />
                <!-- end-article-select-authors -->

                <!-- article-form-button -->
                <article-form-button
                    :label="'Кнопка'"
                    :id="'is-article-button'"
                    :name="'is-article-button'"
                    v-bind:value.sync="button"
                />
                <!-- end-article-form-button -->

                <!-- article-form-recommend-article -->
                <fragment-form-text :title="'Реком. статьи'">
                    <multiselect
                        v-model="chosenRecommended"
                        tag-placeholder="Add this as new tag"
                        placeholder="Search or add a tag"
                        label="title"
                        track-by="id"
                        :options="recommended"
                        :multiple="true"
                        :taggable="true"
                        @tag="addTag"
                    />
                </fragment-form-text>
                <!-- end-article-form-recommend-article -->

                <!-- article-form-author -->
                <fragment-form-text :title="'Автор'">
                    <multiselect
                        v-model="user_id"
                        tag-placeholder="Выбрать автора"
                        placeholder="Выбрать автора"
                        label="name"
                        track-by="id"
                        :options="authors"
                    />
                </fragment-form-text>
                <!-- end-article-form-author -->

                <!-- article-form-direct-link -->
                <article-form-button
                    :label="'Прямая ссылка'"
                    :id="'is-article-link'"
                    :name="'is-article-link'"
                    v-bind:value.sync="link"
                />
                <!-- end-article-form-direct-link -->

                <!-- publish button -->
                <v-button @click="submitForm"/>
                <!-- end publish button -->
            </div>
        </div>
    </v-content>
</template>
<script>
import {required} from 'vuelidate/lib/validators'
import VContent from "./templates/Content"
import VSidebar from "./templates/Sidebar"
import ArticleInputTitle from "./templates/article/form/title"
import Cover from "./fragmets/cover"
import ArticleInputText from "./templates/article/form/text"
import VClose from "./templates/Close"
import ArticleFormInsert from "./templates/article/form/insert"
import ArticleFormSelect from "./templates/article/form/select"
import VButton from "./templates/inputs/button"
import ArticleFormButton from "./templates/article/form/button"
import Multiselect from "vue-multiselect";
import {ARTICLE, USER} from "../api/endpoints";
import FragmentFormText from "./fragmets/text";
import ArticleSidebar from "./templates/article/sidebar";

export default {
    name: 'ArticleForm',
    components: {
        ArticleSidebar,
        FragmentFormText,
        ArticleFormButton,
        VButton,
        ArticleFormSelect,
        ArticleFormInsert,
        VClose,
        ArticleInputText,
        ArticleInputCover: Cover,
        ArticleInputTitle,
        VSidebar,
        VContent,
        Multiselect
    },
    data() {
        return {
            ...this.$store.state.articles[0]
        }
    },
    computed: {
        //
    },
    validations: {
        title: {
            required
        },
        articleType: {
            required
        },
        text: {
            required
        },
    },
    methods: {
        addTag (newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.recommended.push(tag)
            this.chosenRecommended.push(tag)
        },
        selectAuthor (user_id) {
            this.user_id = [user_id]
        },
        submitForm() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                //this.$store.dispatch('submitArticle', this.$data)
            } else {
                this.$store.dispatch('submitArticle', this.$data).then(() => {
                    this.$router.push({name:'createContent'})
                })
            }
        }
    },
    mounted() {
        this.$get(ARTICLE + '?count=12&type=1').then( response => {
            this.recommended = response.data.data
        })
        this.$get(USER + '?count=12').then( response => {
            this.authors = response.data
        })
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .multiselect__tag, .multiselect__option--highlight {
        background: #05b7ff;
    }
</style>
