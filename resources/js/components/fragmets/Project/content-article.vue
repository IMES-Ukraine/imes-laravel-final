<template>
    <span>
    <div class="articles_create-box">
        <div class="articles_create-block">
            <div class="articles_create__item mb37">
                <p class="articles_create__item-title">Назва*</p>
                <div class="articles_create__item-content direction-column">
                    <div class="articles_create__name-block">
                        <input type="text" name="name" v-model="article.title">
                        <div v-if="errorArticleTitle" class="errors">{{
                                errorArticleTitle
                            }}
                        </div>
                    </div>

                    <b-form-group v-slot="{ ariaDescribedby }">
                        <b-form-radio-group v-model="article.type" :aria-describedby="ariaDescribedby"
                                            class="articles_create__radio_circle-bloc"
                                            :options="articleTypes">
                        </b-form-radio-group>
                    </b-form-group>
                </div>
            </div>
        </div>
        <div class="articles_create__item half">
            <p class="articles_create__item-title">Обкладинка*</p>
            <div class="articles_create__item-content">
                <div class="articles_create__item-file width-auto buttonAddFile">
                    <input
                        type="file"
                        id="articleCover"
                        @change="coverField"
                        name="cover"
                        class="input-file-hidden"
                        role="button" />
                    <p><span data-placeholder="Загрузить">Загрузить</span></p>
                    <button class="delete_file deleteFile" id="deleteFileArticle"
                            type="button">
                    </button>
                    <p v-if="article.cover">{{article.cover.name}}</p>
                </div>
                <div v-if="errorArticleCover" class="errors">{{ errorArticleCover }}</div>
            </div>
        </div>
        <div class="articles_create__item half">
            <p class="articles_create__item-title">Галерея</p>
            <div class="articles_create__item-content">
                <div class="articles_create__media">
                    <SimpleTestMedia
                        :media="article.multiples"></SimpleTestMedia>
                    <div class="articles_create__media-add">
                        <input type="file" name="file" id="article_multiples"
                               @change="addMedia($event)">
                    </div>
                </div>
            </div>
        </div>
        <div class="articles_create__item mb54">
            <p class="articles_create__item-title">Текст*</p>
            <div class="articles_create__item-content">
                                            <textarea
                                                class="form-control"
                                                rows="4"
                                                v-model="article.text"></textarea>
            </div>
            <div class="errors" v-if="errorArticleText">{{ errorArticleText }}</div>
        </div>
        <!--<div class="articles_create__item">
            <p class="articles_create__item-title">Категории</p>
            <div class="articles_create__item-content">
                <div class="articles_create__addition">
                    <div class="articles_create__addition-block">
                        <div class="articles_create__addition-select">
                            <select class="my-ui-select articles_create-select">
                                <option value="1">Значение 1</option>
                                <option value="2">Значение 2</option>
                                <option value="3">Значение 3</option>
                                <option value="4">Значение 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="articles_create__addition-block">
                        <div class="articles_create__addition-field">
                            <input type="text" name="text" class="">
                        </div>
                        <button class="articles_create__addition-button">Добавить категорию</button>
                    </div>
                </div>
            </div>
        </div>-->
        <!--<div class="articles_create__item">
            <p class="articles_create__item-title">Рубрики</p>
            <div class="articles_create__item-content">
                <div class="articles_create__addition">
                    <div class="articles_create__addition-block">
                        <div class="articles_create__addition-select">
                            <select class="my-ui-select articles_create-select">
                                <option value="1">Значение 1</option>
                                <option value="2">Значение 2</option>
                                <option value="3">Значение 3</option>
                                <option value="4">Значение 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="articles_create__addition-block">
                        <div class="articles_create__addition-field">
                            <input type="text" name="text" class="">
                        </div>
                        <button class="articles_create__addition-button">Добавить рубрику</button>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="articles_create__item">
            <p class="articles_create__item-title">Автор</p>
            <div class="articles_create__item-content">
                <div class="articles_create__addition">
                    <div class="articles_create__addition-block width-194">
                        <div class="articles_create-multiselect">
                            <multiselect
                                v-model="article.author"
                                tag-placeholder="Обрати автора"
                                placeholder="Обрати автора"
                                label="name"
                                track-by="id"
                                :searchable="false"
                                :close-on-select="true"
                                :show-labels="false"
                                :options="authors"
                            />
                        </div>
                    </div>
                    <div class="articles_create__addition-block">
                        <div class="articles_create__addition-field">
                            <input type="text" id="new_user" v-model="new_user"/>
                        </div>
                        <button class="articles_create__addition-button" type="button"
                                @click="AddNewUser">Добавить автора
                        </button>
                    </div>
                </div>
            </div>
        </div>
                    <article-form-insert
                        v-bind:insert.sync="article.insert"
                        v-bind:textInsert.sync="article.textInsert"
                        @insert="insertStore"
                    />
                    <article-form-button
                        :label="'Статья-ссылка'"
                        :id="'is-article-button'"
                        :name="'is-article-button'"
                        :text_button="text_button"
                        @update="buttonStore"
                    />
                    <article-form-button
                        :label="'Кнопка “Читати ще”'"
                        :id="'is-article-more'"
                        :name="'is-article-more'"
                        :text_button="text_button"
                        @update="buttonStore"
                    />
                    <article-form-button
                        :label="'Кнопка “Розпочати дослiдження”'"
                        :id="'is-article-info'"
                        :name="'is-article-info'"
                        :text_button="text_button"
                        @update="buttonStore"
                    />
        <div class="articles_create__item">
            <p class="articles_create__item-title">Реком. статьи</p>
            <div class="articles_create__item-content">
                <multiselect
                    v-model="article.recommended"
                    tag-placeholder="Додати статтю"
                    placeholder="Вибрати статтю"
                    label="title"
                    track-by="id"
                    :options="recommended"
                    :multiple="true"
                    :taggable="true"
                    :show-labels="false"
                    :close-on-select="false"
                    @tag="addTag"
                />
            </div>
        </div>
    </div>
    <button class="articles_create-submit button-gradient"
            type="button"
            @click="storeArticle">
        Публікувати
    </button>
    </span>
</template>

<script>
import ProjectMixin from "../../../ProjectMixin";

import {ValidationProvider} from "vee-validate";
import VCheckbox from "../../templates/inputs/checkbox"
import {ARTICLE, ARTICLE_COVER, ARTICLE_LIST, TOKEN, USER, USER_CREATE_NAME, USER_LIST} from "../../../api/endpoints";
import SimpleTestMedia from "../SimpleTestMedia";
import Multiselect from "vue-multiselect";
import ArticleFormInsert from "../../templates/article/form/insert";
import ArticleFormButton from "../../templates/article/form/button";
import ArticleMultiple from "../../templates/article/form/multiple";
import axios from "axios";
import {getRandomId} from "../../../utils";

export default {
    name: "content-article",
    mixins: [ProjectMixin],
    components: {ValidationProvider, VCheckbox, SimpleTestMedia, Multiselect, ArticleFormInsert, ArticleFormButton, ArticleMultiple},
    data() {
        return {
            recommended: [],
            authors: [],
            new_user: null,
            chosenRecommended: null,
            textInsert: '',
            text_button: '',
            articleTypes: {
                1: "Новини",
                2: "Інформація"
            },
            requiredErrorText: "Поле обовʼязкове"
        }
    },
    mounted() {
        axios.get(USER_LIST, {params: {count: 12}}).then(response => {
            this.authors = response.data.data;
        });
        axios.get(ARTICLE_LIST, {params: {count: 12, type: 1}}).then(response => {
            this.recommended = response.data.data;
        });

    },
    computed: {
        article() {
            return this.$store.state.content.article ;
        }
    },
    methods: {
        AddNewUser() {
            this.$get(USER_CREATE_NAME + '/' + this.new_user).then(response => {
                this.authors = response.data
            })
        },
        insertStore(value) {
            this.article.insert = value
        },
        // textInsertStore(value) {
        //     this.article.textInsert = value
        // },
        // linkStore(value) {
        //     this.article.link = value
        // },
        // articleTypeStore(value) {
        //     this.article.type = value
        // },
        buttonStore(value) {
            this.article.text_button = value
        },


        storeArticle() {
            this.errorArticleTitle = '';
            this.errorArticleCover = '';
            this.errorArticleText = '';
            let error = false;

            if (this.article.title == null) {
                this.errorArticleTitle = this.requiredErrorText;
                error = true;
            }

            if (this.article.text == null) {
                this.errorArticleText = this.requiredErrorText;
                error = true;
            }

            if (this.article.cover == null) {
                this.errorArticleCover = this.requiredErrorText;
                error = true;
            }

            if (!error) {
                this.$store.commit('storeArticle', this.article);

                this.setStep(2);
            }
        },
        addMedia(event) {
            let imageForm = new FormData()
            imageForm.append('file', event.target.files[0])

            axios.post(
                ARTICLE_COVER + '/articles',
                imageForm,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    params: {
                        access_token: TOKEN
                    },
                }
            ).then((file) => {
                let obj = {
                    itemId: getRandomId(),
                    file: file.data.data.id,
                    name: event.target.files[0].name,
                    data: file.data,
                    path: file.data.data.path
                };
                this.article.multiples.push(obj)
                $('#article_multiples').val(null);
            })
        },
        coverField() {
            this.$store.state.project.options.files.article_cover = event.target.files[0];
            this.article.cover = event.target.files[0].name;
            console.log(event.target.files[0].name, this.article.cover);
        },

    },

}
</script>

<style scoped>

</style>
