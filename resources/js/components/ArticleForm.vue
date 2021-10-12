<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="article-edit card">

            <v-close :to="{name:'createContent'}"/>

            <div class="card-body">

                <!-- article-title -->
                <article-input-title
                    v-bind:type.sync="type"
                    :v="$v"
                    :articleType="articleType"
                    @update="articleTypeStore"
                />
                <!-- end-article-title -->

                <!-- article-cover -->
                <article-input-cover
                    v-bind:file.sync="images"
                    :file-key="'cover'"
                />
                <!-- end-article-cover -->

                <!-- article-cover -->
                <article-multiple
                    v-bind:files.sync="multiples"
                    :title="'Галерея'"
                    :images="multiples"
                    @update="multiplesStore"
                />

                <!-- article-content -->
                <article-input-text
                    :v="$v"
                    :title="'Текст статтi'"
                    :error="'Контент заповнити обов\'язково'"
                    v-bind:text.sync="$v.text.$model"
                />
                <!-- end-article-content -->

                <!-- article-form-author -->
                <fragment-form-text :title="'Автор'">
                    <multiselect
                        v-model="user_id"
                        tag-placeholder="Обрати автора"
                        placeholder="Обрати автора"
                        label="name"
                        track-by="id"
                        :options="authors"
                    />
                    <input type="text" id="new_user" v-model="new_user" />
                    <button type="button" class="btn btn-outline-primary" @click="AddNewUser">Добавить автора</button>
                </fragment-form-text>
                <!-- end-article-form-author -->

                <!-- article-insert -->
                <article-form-insert
                    :v="$v"
                    v-bind:insert.sync="insert"
                    v-bind:textInsert.sync="textInsert"
                    @insert="insertStore"
                />
                <!-- end-article-insert -->

                <!-- article-select-category -->
                <!--<article-form-select
                    :name="'categories'"
                    :label="'Категориi'"
                    v-bind:value.sync="category"
                />-->
                <!-- end-article-select-category -->

                <!-- article-select-headings -->
                <!--<article-form-select
                    :name="'headings'"
                    :label="'Рубрики'"
                    v-bind:value.sync="headings"
                />-->
                <!-- end-article-select-headings -->

                <!-- article-select-authors -->
                <!--<article-form-select
                    :name="'authors'"
                    :label="'Автор'"
                    v-bind:value.sync="author"
                />-->
                <!-- end-article-select-authors -->

                <!-- article-form-button -->
                <article-form-button
                    :label="'Кнопка'"
                    :id="'is-article-button'"
                    :name="'is-article-button'"
                    :text_button="text_button"
                    @update="buttonStore"
                />
                <!-- end-article-form-button -->

                <!-- article-form-direct-link -->
                <article-form-button
                    :label="'Пряма ссилка'"
                    :id="'is-article-link'"
                    :name="'is-article-link'"
                    :text_button="link"
                    @update="linkStore"
                />
                <!-- end-article-form-direct-link -->

                <!-- article-form-recommend-article -->
                <fragment-form-text :title="'Рекомендованi статтi'">
                    <multiselect
                        v-model="chosenRecommended"
                        tag-placeholder="Додати статтю"
                        placeholder="Вибрати статтю"
                        label="title"
                        track-by="id"
                        :options="recommended"
                        :multiple="true"
                        :taggable="true"
                        @tag="addTag"
                    />
                </fragment-form-text>
                <!-- end-article-form-recommend-article -->

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
import {ARTICLE, USER, USER_CREATE_NAME} from "../api/endpoints";
import FragmentFormText from "./fragmets/text";
import ArticleSidebar from "./templates/article/sidebar";
import ArticleMultiple from "./templates/article/form/multiple"

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
        Multiselect,
        ArticleMultiple
    },
    data() {
        return {
            ...this.$store.state.articles[0],
            new_user: ''
        }
    },
    computed: {
        //
    },
    validations: {
        title: {
            required
        },
        text: {
            required
        }
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
        },
        AddNewUser() {
            this.$get(USER_CREATE_NAME + '/' + this.new_user).then( response => {
                this.authors = response.data
            })
        },
        buttonStore(value) {
            this.text_button = value
        },
        insertStore(value) {
            this.insert = value
        },
        linkStore(value) {
            this.link = value
        },
        articleTypeStore(value) {
            this.articleType = value
        },
        multiplesStore(value) {
            this.multiples = value
        }
    },
    mounted() {
        this.$get(ARTICLE + '?count=12&type=1').then( response => {
            this.recommended = response.data
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
