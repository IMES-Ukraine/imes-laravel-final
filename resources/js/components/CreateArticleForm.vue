<template>
    <v-content>
        <template v-slot:sidebar>
        </template>
        <div class="main-articles">
            <div class="article-edit card">
                <router-link :to="{name:'createContent'}">
                    <a href="#" class="article-edit__close" aria-label="закрити" title="закрити"></a>
                </router-link>
                <h4>Статья</h4>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="article-edit__text col-3">
                            Заголовок
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="$v.title.$model">
                                    <div class="error" v-if="$v.title.$error">
                                        Заголовок обязателен
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="article-edit__radio form-check form-check-inline">
                                        <input type="radio" name="typePublication" id="typePublication_1" value="1" v-model="articleType">
                                        <label class="article-edit__label article-edit__radio form-check-label" for="typePublication_1">
                                            Новости
                                        </label>
                                    </div>
                                    <div class="article-edit__radio form-check form-check-inline">
                                        <input type="radio" name="typePublication" id="typePublication_2" value="2" v-model="articleType">
                                        <label class="article-edit__label form-check-label" for="typePublication_2">
                                            Информация
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="article-edit__text col-3">
                            Обложка
                        </div>
                        <div class="col-4">
                            <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                        <span class="input-file-label">
                            <span class="icon-is-left icon-is-load-grey"></span>Завантажити
                        </span>
                                <input type="file" name="addCover" id="articleCover" ref="articleCover" class="input-file-hidden">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-4" id="my-strictly-unique-vue-upload-multiple-image" style="text-align: center;">

<!--                        <vue-upload-multiple-image
                            @upload-success="uploadImageSuccess"
                            @before-remove="beforeRemove"
                            @edit-image="editImage"
                            @data-change="dataChange"
                        ></vue-upload-multiple-image>-->
                    </div>
                    <div class="row mb-3">
                        <div class="article-edit__text col-3">
                            Текст статьи
                        </div>
                        <div class="col-9">
                            <textarea class="form-control" rows="4" v-model="$v.text.$model"></textarea>
                            <div class="error" v-if="$v.text.$error">
                                Контент обязателен
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4" v-if="textInsert">
                        <div class="article-edit__text col-3">
                            Заголовок
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12">
                                    <input class="form-control" type="text" name="title" v-model="insert[0].title">
                                    <div class="error" v-if="$v.title.$error">
                                        Заголовок обязателен
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="article-edit__text col-3">
                            <div class="article-edit__btn-pos custom-checkbox">
                                <input class="custom-checkbox__input" type="checkbox" name="article-has-insert" id="article-has-insert" v-model="textInsert">
                                <label for="article-has-insert" class="custom-checkbox__label"></label>
                            </div>
                            Вставка в тексте
                        </div>
                        <div class="col-9" v-if="textInsert">
                            <textarea class="form-control" rows="4" v-model="insert[0].content"></textarea>
                            <div class="error" v-if="$v.text.$error">
                                Текст вставки обязателен
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3" v-if="textInsert">
                        <div class="article-edit__text col-3">
                            Продолжение статьи
                        </div>
                        <div class="col-9">
                            <textarea class="form-control" rows="4" v-model="insert[1].content"></textarea>
                            <div class="error" v-if="$v.text.$error">
                                Контент обязателен
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="article-edit__text col-3">
                            Категории
                        </div>
                        <div class="col-4">
                            <label class="btn btn-outline-second btn-centered-content is-small">
                                <select class="form-control" v-model="category">
                                    <option v-for="item in categoryList" :value="item.id" :key="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                            </label>
                        </div>

                    </div>

                    <div class="row mb-4">
                        <div class="article-edit__text col-3">
                            Рубрики
                        </div>
                        <div class="col-4">
                            <label class="btn btn-outline-second btn-centered-content is-small">
                                <select class="form-control" v-model="headings">
                                    <option v-for="item in headingsList" :value="item.id" :key="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                            </label>
                        </div>

                    </div>

                    <div class="row mb-4">
                        <div class="article-edit__text col-3">
                            Автор
                        </div>
                        <div class="col-4">
                            <label class="btn btn-outline-second btn-centered-content is-small">
                                <select class="form-control" v-model="author">
                                    <option v-for="item in authorList" :value="item.id" :key="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                            </label>
                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="article-edit__text col-3">
                            <div class="article-edit__btn-pos custom-checkbox">
                                <input class="custom-checkbox__input" type="checkbox" name="is-article-button" id="is-article-button">
                                <label for="is-article-button" class="custom-checkbox__label"></label>
                            </div>
                            Кнопка
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="input-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text is-text"></span>
                                        </div>
                                        <input type="text" class="form-control input-is-form is-text" placeholder="" aria-label="теги" v-model="button">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="article-edit__text col-3">
                            Реком. статьи
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="input-group input-group">
                                        <multiselect v-model="chosenRecommended" tag-placeholder="Add this as new tag" placeholder="Search or add a tag" label="title" track-by="id" :options="recommended" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="article-edit__text col-3">
                            Автор
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="input-group input-group">
                                        <multiselect v-model="user_id" tag-placeholder="Выбрать автора" placeholder="Выбрать автора" label="name" track-by="id" :options="authors"></multiselect>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="article-edit__text col-3">
                            <div class="article-edit__btn-pos custom-checkbox">
                                <input class="custom-checkbox__input" type="checkbox" name="is-article-link" id="is-article-link">
                                <label for="is-article-link" class="custom-checkbox__label"></label>
                            </div>
                            Прямая ссылка
                        </div>
                        <div class="col-9">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="input-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text is-text"></span>
                                        </div>
                                        <input type="text" class="form-control input-is-form is-text" placeholder="" aria-label="теги" v-model="link" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-outline-primary"  @click="submitForm">
                                Опубликовать
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-content>
</template>
<script>
import {required} from 'vuelidate/lib/validators'
import Multiselect from 'vue-multiselect'
import VContent from "./templates/Content"
import axios from 'axios'
import VueUploadMultipleImage from 'vue-upload-multiple-image'
import {ARTICLE, ARTICLE_LIST, USER_LIST, V1} from "../api/endpoints";

export default {
    name: 'CreateArticleForm',
    components: {
        Multiselect,
        VContent,
        VueUploadMultipleImage
    },
    data() {
        return {
            ...this.$store.state.articles[0],
            textInsert: null,
            categoryList : [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
            headingsList : [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
            authorList : [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
            articleCover : '',
            recommended: []
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
        //...mapActions(['article']),
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
            this.articleCover = this.$refs.articleCover.files[0];
            this.$v.$touch()
            if (this.$v.$invalid) {
                //this.$store.dispatch('submitArticle', this.$data)
            } else
                this.$store.dispatch('submitArticle', this.$data)
        },
        back(){
            this.$store.dispatch('addContent')
        }
    },
    mounted() {
        axios.get(ARTICLE_LIST, {count:12}).then(
            response => {
                this.recommended = response.data.data.data
            })
        axios.get(USER_LIST, {count: 12}).then(
            response => {
                this.authors = response.data.data
            })
    }
}
</script>
<!--<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>-->

<style>
.multiselect__tag, .multiselect__option--highlight {
    background: #05b7ff;
}
</style>
