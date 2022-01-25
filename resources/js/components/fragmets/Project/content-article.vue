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
                        <b-form-radio-group v-model="article.type"
                                            :aria-describedby="ariaDescribedby"
                                            class="articles_create__radio_circle-bloc"
                                            :options="articleTypes">
                        </b-form-radio-group>
                    </b-form-group>
                </div>
            </div>
        </div>
        <div class="articles_create__item half">
            <p class="articles_create__item-title">Обложка*</p>
            <file-input :key="article.title + '-img'"
                        :value="article.cover"
                        @fileInput="article.cover = $event"
                        :error="errorArticleCover"
                        type="image"
                        attachment="articles"/>
        </div>
        <div class="articles_create__item half">
            <p class="articles_create__item-title">Галерея</p>
            <div class="articles_create__item-content">
                <div class="articles_create__media" :key="JSON.stringify(article)">
                    <div v-for="(file, index) in article.featured_images" v-bind:key="file.itemId" class="articles_create__media-item">
                        <div class="articles_create__media-img">
                            <img :src="file.path" alt="">
                        </div>
                        <button type="button" class="delete_file deleteFile" @click="article.featured_images.splice(index, 1)"></button>
                    </div>
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
            <textarea class="form-control" rows="4" v-model="article.text"/>
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
<!--                    <div class="articles_create__addition-block width-194">-->
<!--                        <div class="articles_create-multiselect">-->
<!--                            <multiselect-->
<!--                                v-model="article.author"-->
<!--                                tag-placeholder="Обрати автора"-->
<!--                                placeholder="Обрати автора"-->
<!--                                label="name"-->
<!--                                track-by="id"-->
<!--                                :searchable="false"-->
<!--                                :close-on-select="true"-->
<!--                                :show-labels="false"-->
<!--                                :options="authors"-->
<!--                            />-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="articles_create__addition-block">
                        <div class="articles_create__addition-field" >
                            <input type="text" id="new_user" v-model="article.author2" placeholder="Указать  автора (авторов)"/>
                        </div>
<!--                        <button class="articles_create__addition-button" type="button"-->
<!--                                @click="AddNewUser">Добавить автора-->
<!--                        </button>-->
                    </div>
                </div>
            </div>
        </div>
                    <article-form-insert
                        v-bind:insert.sync="article.content"
                        v-bind:textInsert.sync="textInsert"
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
<!--                    <article-form-button-->
<!--                        :label="'Кнопка “Розпочати дослiдження”'"-->
<!--                        :id="'is-article-info'"-->
<!--                        :name="'is-article-info'"-->
<!--                        :text_button="text_button"-->
<!--                        @update="buttonStore"-->
<!--                    />-->
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
import {
    ARTICLE,
    ARTICLE_COVER,
    ARTICLE_LIST, ARTICLE_TAGS,
    PROJECT_IMAGE,
    TOKEN,
    USER_CREATE_NAME,
    USER_LIST
} from "../../../api/endpoints";
import Multiselect from "vue-multiselect";
import ArticleFormInsert from "../../templates/article/form/insert";
import ArticleFormButton from "../../templates/article/form/button";
import ArticleMultiple from "../../templates/article/form/multiple";
import axios from "axios";
import {checkIsImage, getRandomId} from "../../../utils";
import FileInput from "../../inputs/file-input";
import VTextarea from "../../templates/inputs/textarea";
import VInputText from "../../templates/inputs/text";

export default {
    name: "content-article",
    mixins: [ProjectMixin],
    components: {
        ValidationProvider,
        VCheckbox,
        Multiselect,
        ArticleFormInsert,
        ArticleFormButton,
        ArticleMultiple,
        FileInput,
        VTextarea,
        VInputText
    },
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
            article: this.$store.state.content.article

        }
    },
    mounted() {
        axios.get(USER_LIST, {params: {count: 12}}).then(response => {
            this.authors = response.data.data;
        });
        axios.get(ARTICLE_LIST, {params: {count: 12, type: 1}}).then(response => {
            this.recommended = response.data.data.data;
        });
        this.$get(ARTICLE_TAGS).then(response => {
            this.tags = response.data
        })

    },
    computed: {


    },
    methods: {
        insertStore(value) {
            this.article.insert = value
        },
        textInsertStore(value) {
            this.article.textInsert = value
        },
        linkStore(value) {
            this.article.link = value
        },
        articleTypeStore(value) {
            this.article.type = value
        },
        buttonStore(value) {
            this.article.text_button = value
        },


        storeArticle() {
            this.errorArticleTitle = '';
            this.errorArticleCover = '';
            this.errorArticleText = '';
            let error = false;

            if (!this.article.title || !this.article.title.trim()) {
                this.errorArticleTitle = this.requiredErrorText;
                error = true;
            }

            if (!this.article.text || !this.article.text.trim()) {
                this.errorArticleText = this.requiredErrorText;
                error = true;
            }

            if (!this.article.cover || (!this.article.cover.file_name) ) {
                this.errorArticleCover = this.requiredErrorText;
                error = true;
            }

            if (!error) {
                this.$store.dispatch('saveArticle', this.article).then(() => {
                    this.setStep(2);
                });
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
                let obj = file.data.data;
                this.article.featured_images.push(obj)
                $('#article_multiples').val(null);
            })
        },
    },

}
</script>

<style scoped>

.articles_create__addition-block, .articles_create__addition-block .articles_create__addition-field {
    width: 100%;
}

.articles_create__media .delete_file {
    /*display: none;*/
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border: none;
    background: 0 0;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg width='18' height='18' viewBox='0 0 18 18' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='9' cy='9' r='9' fill='%23FF608D'/%3e%3cg clip-path='url(%23clip0_508:1563)'%3e%3cpath d='M12.7542 6.21186L12.5554 5.6162C12.4798 5.38933 12.2682 5.23689 12.0291 5.23689H10.359V4.69314C10.359 4.42136 10.138 4.2002 9.86633 4.2002H8.3369C8.06526 4.2002 7.84423 4.42136 7.84423 4.69314V5.23689H6.17417C5.93501 5.23689 5.72346 5.38933 5.64779 5.6162L5.44908 6.21186C5.40383 6.34747 5.42676 6.49764 5.51032 6.61362C5.59389 6.72959 5.72909 6.79887 5.87205 6.79887H6.07976L6.53693 12.4521C6.57092 12.8716 6.92687 13.2002 7.34744 13.2002H10.9486C11.3691 13.2002 11.7251 12.8716 11.759 12.452L12.2162 6.79887H12.3312C12.4741 6.79887 12.6093 6.72959 12.6929 6.61369C12.7765 6.49771 12.7994 6.34747 12.7542 6.21186ZM8.37158 4.72754H9.83166V5.23689H8.37158V4.72754ZM5.98514 6.27153L6.14808 5.78305C6.15179 5.77179 6.16229 5.76424 6.17417 5.76424H12.0291C12.041 5.76424 12.0514 5.77179 12.0552 5.78305L12.2182 6.27153H5.98514Z' fill='white'/%3e%3cpath d='M10.3923 12.3915C10.397 12.3917 10.4016 12.3918 10.4062 12.3918C10.5456 12.3918 10.662 12.2826 10.6693 12.1419L10.9169 7.38877C10.9244 7.24334 10.8127 7.11926 10.6673 7.11171C10.5215 7.10395 10.3979 7.21587 10.3902 7.3613L10.1427 12.1144C10.1351 12.2598 10.2469 12.3839 10.3923 12.3915Z' fill='%23FF608D'/%3e%3cpath d='M7.54556 12.1424C7.55325 12.283 7.66957 12.3918 7.80861 12.3918C7.81342 12.3918 7.81836 12.3916 7.82324 12.3913C7.9686 12.3835 8.08004 12.2592 8.07215 12.1137L7.8128 7.36064C7.8049 7.21521 7.68062 7.10376 7.53519 7.11173C7.38983 7.11963 7.27839 7.24391 7.28628 7.38934L7.54556 12.1424Z' fill='%23FF608D'/%3e%3cpath d='M9.10449 12.3918C9.25013 12.3918 9.36816 12.2737 9.36816 12.1281V7.375C9.36816 7.22936 9.25013 7.11133 9.10449 7.11133C8.95885 7.11133 8.84082 7.22936 8.84082 7.375V12.1281C8.84082 12.2737 8.95885 12.3918 9.10449 12.3918Z' fill='%23FF608D'/%3e%3c/g%3e%3cdefs%3e%3cclipPath id='clip0_508:1563'%3e%3crect width='9' height='9' fill='white' transform='translate(4.6001 4.2002)'/%3e%3c/clipPath%3e%3c/defs%3e%3c/svg%3e");
    background-size: contain;
    padding: 0;
    position: absolute;
    top: -9px;
    right: 7px;
    z-index: 1;
}
</style>
