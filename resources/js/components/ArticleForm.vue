<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>
        <div class="articles">
            <div class="articles_create">
                <v-close :to="{name:'articleList'}"/>
                <div class="articles_create-box">
                    <div class="articles_create-block">
                        <div class="articles_create__item">
                            <p class="articles_create__item-title">Заголовок</p>
                            <div class="articles_create__item-content direction-column">
                                <div class="articles_create__name-block">
                                    <input
                                        class="form-control"
                                        type="text"
                                        v-model="article.title"/>
                                    <div class="errors" v-if="title_error">
                                        Заголовок обов'язковий
                                    </div>
                                </div>
                                <!-- article-title -->
                                <article-input-title
                                    v-bind:type.sync="type"
                                    :articleType="articleType"
                                    :title_error="title_error"
                                    @update="articleTypeStore"
                                />
                                <!-- end-article-title -->
                            </div>
                        </div>
                        <div class="articles_create__item half">
                            <p class="articles_create__item-title">Обложка</p>

                            <div class="articles_create__item-content">
                                <div
                                    :class="['articles_create__item-file', 'width-auto buttonAddFile', {has_file: article.cover_image.path} ] ">
                                    <input
                                        type="file"
                                        name="addCover"
                                        class="input-file-hidden"
                                        v-on:change="handleUploadArticle"
                                        role="button"/>

                                    <p><span>{{ article.cover_image.file_name || 'Загрузить' }}</span>
                                    </p>
                                    <button class="delete_file deleteFile" @click="article.cover_image={}"></button>
                                </div>
                                <div v-if="errorArticleCover" class="errors">{{ errorArticleCover }}</div>
                            </div>

                        </div>
                        <div class="articles_create__item half">
                            <p class="articles_create__item-title">Галерея</p>
                            <div class="articles_create__item-content">
                                <div class="articles_create__media">
                                    <div class="articles_create__media-list">
                                        <div v-for="file in article.featured_images" v-bind:key="file.itemId" class="articles_create__media-item">
                                            <div class="articles_create__media-img">
                                                <img :src="file.path" alt="">
                                            </div>
                                        </div>
                                        <div class="articles_create__media-add">
                                            <input type="file" name="file" id="article_multiples"
                                                   @change="addMedia($event)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="articles_create__item">
                            <p class="articles_create__item-title">Текст</p>
                            <div class="articles_create__item-content direction-column">
                                <textarea
                                    class="form-control"
                                    rows="4"
                                    v-model="article.excerpt"/>
                                <div class="errors" v-if="text_error">{{ text_error }}</div>
                            </div>
                        </div>

                        <div class="articles_create-block">
                            <div class="articles_create__item">
                                <div class="articles_create__item-title has_radio">
                                    <input
                                        type="checkbox"
                                        name="article-has-insert"
                                        id="article-has-insert"
                                        v-model="textLocale"
                                        :checked="textLocale"
                                        @onclick="getTextInsert"/>
                                    <i></i>
                                    <p>Вставка в тексте</p>
                                </div>
                                <div class="articles_create__item-content direction-column" v-if="textLocale">
                                    <v-input-text
                                        :name="'title'"
                                        v-on:update:value="updateInsertTitle($event)"
                                        placeholder="Заголовок"
                                        :value="article.content[0].title"
                                        :text="''"
                                        :classes="'mb20'"
                                    />
                                    <v-textarea
                                        :rows="4"
                                        :text="article.content[0].content"
                                        v-on:update:text="updateInsertContent($event)"
                                        placeholder="Текст"
                                    />
                                </div>
                            </div>
                            <!--<article-input-text
                                v-if="textLocale"
                                :v="v"
                                :title="'Продовження статтi'"
                                :text="this.insert[1].content"
                                v-on:update:text="updateInsert(1, 'content', $event)"
                            />-->
                        </div>
                        <div class="articles_create__item">
                            <p class="articles_create__item-title">Теги</p>
                            <div class="articles_create__item-content">
                                <div class="articles_create__name-block">
                                    <div class="field_wrap_for_tags">
                                        <multiselect
                                            v-model="article.tags"
                                            :value="article.tags"
                                            tag-placeholder="Добавить тег"
                                            placeholder="Выбрать тег"
                                            label="name"
                                            track-by="id"
                                            :options="tags"
                                            :multiple="true"
                                            :taggable="true"
                                            :show-labels="false"
                                            :close-on-select="false"
                                        />
                                    </div>
                                </div>
                            </div>
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
                        </div>
                        <div class="articles_create__item">
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
                                                v-model="article.user"
                                                :value="article.user"
                                                @input="selectAuthor"
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
                                            <div class="errors" v-if="addUserError">{{ addUserError }}</div>
                                        </div>
                                        <button class="articles_create__addition-button" type="button"
                                                @click="AddNewUser">Добавить автора
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <article-form-button
                            :label="'Кнопка'"
                            :text_button="article.button"
                            @update="buttonStore"
                        />
                        <div class="articles_create__item">
                            <p class="articles_create__item-title">Реком. статьи</p>
                            <div class="articles_create__item-content">
                                <multiselect
                                    v-model="article.recommended"
                                    :value="article.recommended"
                                    tag-placeholder="Додати статтю"
                                    placeholder="Вибрати статтю"
                                    label="title"
                                    track-by="id"
                                    :options="recommended"
                                    :multiple="true"
                                    :taggable="true"
                                    :show-labels="false"
                                    :close-on-select="false"
                                />
                            </div>
                        </div>
                        <article-form-button
                            :label="'Пряма ссилка'"
                            :text_button="article.action"
                            @update="linkStore"
                        />
                    </div>
                </div>
                <button class="articles_create-submit button-gradient" @click="submitForm">Опубликовать</button>
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
import {
    ARTICLE,
    ARTICLE_UPDATE,
    USER_CREATE_NAME,
    ARTICLE_COVER,
    TOKEN,
    ARTICLE_TAGS,
    USER_LIST, ARTICLE_LIST
} from "../api/endpoints";
import FragmentFormText from "./fragmets/text";
import ArticleSidebar from "./templates/article/sidebar";
import ArticleMultiple from "./templates/article/form/multiple"
import {getRandomId} from './../utils'
import VTextarea from "./templates/inputs/textarea"
import VInputText from "./templates/inputs/text"

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
        ArticleMultiple,
        VTextarea,
        VInputText
    },
    data() {
        return {
            articleId: 0,
            new_user: '',
            errorArticleCover: '',
            addUserError: '',
            title_error: '',
            text_error: '',
            recommended: [],
            authors: [],
            user: {},
            chosenRecommended: [],
            chosenTags: [],
            tags: [],
            link: '',
            button: '',
            textInsert: '',
            insert: [],
            multiples: [],
            articleType: 1,
            type: 1,
            textLocale: 0,
            article: {
                type: 1,
                title: '',
                excerpt: '',
                cover_image: {
                    file_name: ''
                },
                content: [
                    {
                        type: 'text',
                        title: '',
                        content: ''
                    }
                ],
                tags: [],
                recommended: [],
                featured_images: [],
                user_id: 0,
                user: {
                    id: 0,
                    name: ''
                },
                button: '',
                action: ''
            }
        }
    },
    methods: {
        getTextInsert() {
            this.textLocale = 1;
        },
        addTag(newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.recommended.push(tag)
            this.chosenRecommended.push(tag)
        },
        selectAuthor() {
            this.article.user_id = this.article.user.id
        },
        submitForm() {
            this.errorArticleCover = '';
            this.title_error = '';
            this.text_error = '';
            let error = false

            if (!this.article.cover_image.file_name) {
                this.errorArticleCover = 'Обложка обязательна'
                error = true;
            }

            if (!this.article.title) {
                this.title_error = 'Название обязательно'
                error = true;
            }

            if (!this.article.excerpt) {
                this.text_error = 'Описание обязательно'
                error = true;
            }

            if (!error) {
                let url = ARTICLE;
                if (this.articleId) {
                    url = ARTICLE_UPDATE + '/' + this.articleId;
                }
                this.$post(url, this.article, {
                    params: {
                        access_token: TOKEN
                    },
                }).then((res) => {
                    this.$router.push({name: 'articleList'});
                })
                    .catch((error) => {
                        console.log(error)
                    }).finally(() => {
                    console.log('success or error')
                });
            }
        },
        AddNewUser() {
            this.addUserError = '';

            if (this.new_user == '') {
                this.addUserError = 'Поле не должно быть пустое'
            }

            if (this.addUserError == '') {
                this.$get(USER_CREATE_NAME + '/' + this.new_user).then(response => {
                    this.authors = response.data
                })
            }
        },
        titleStore(value) {
            this.title = value
        },
        buttonStore(value) {
            this.article.button = value
        },
        insertStore(value) {
            this.article.insert = value
        },
        linkStore(value) {
            this.article.action = value
        },
        articleTypeStore(value) {
            this.article.type = value
        },
        multiplesStore(value) {
            this.article.multiples = value
        },
        updateInsertTitle(value) {
            this.article.content[0].title = value
        },
        updateInsertContent(value) {
            this.article.content[0].content = value
        },
        handleUploadArticle(event) {
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
                this.article.cover_image = file.data.data
            })
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
                    itemId: 'article-' + getRandomId(),
                    id: file.data.data.id,
                    file_name: file.data.data.file_name,
                    path: file.data.data.path
                };
                this.article.featured_images.push(obj)
                $('#article_multiples').val(null);
            })
        },
    },
    mounted() {
        this.articleId = this.$route.params.articleId;
        if (this.articleId) {
            this.$get(ARTICLE + '/' + this.articleId).then(response => {
                this.article = response.data[0] || this.article;
                this.textLocale = !!this.article.content[0].content
            });
        }
        this.$get(ARTICLE_LIST, {count: 12}).then(response => {
            console.log(response.data.data.data, response.data.data);
            this.recommended = response.data.data || {}
        });
        this.$get(USER_LIST, {count: 12}).then(response => {
            this.authors = response.data
        });
        this.$get(ARTICLE_TAGS).then(response => {
            this.tags = response.data
        });
    }
}
</script>

<!--<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>-->
<style>
.multiselect {
    min-height: 35px;
}

.multiselect--active:not(.multiselect--above) .multiselect__tags {
    border-radius: 5px 5px 0 0;
}

.multiselect--active .multiselect__tags {
    border-radius: 0 0 5px 5px;
}

.multiselect--active .multiselect__tags:after {
    -webkit-transform: rotate(-180deg);
    -ms-transform: rotate(-180deg);
    transform: rotate(-180deg);
}

.multiselect--active .multiselect__placeholder {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}

.multiselect--active.multiselect--above {
    border-radius: 0 0 5px 5px;
}

.multiselect--active.multiselect--above .multiselect__content-wrapper {
    border-bottom: none;
    border-radius: 5px 5px 0 0;
}

.multiselect--above .multiselect__content-wrapper {
    border: 1px solid #D9D9D9;
    top: auto;
    bottom: 100%;
}

.multiselect__select {
    display: none;
}

.multiselect__tags {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    min-height: 35px;
    font-weight: 500;
    font-size: 11px;
    line-height: 1.2;
    letter-spacing: -0.0024em;
    border: 1px solid #D9D9D9;
    border-radius: 5px;
    padding: 0;
    position: relative;
}

.multiselect__tags:after {
    content: "";
    display: block;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    width: 9px;
    height: 6px;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg width='9' height='6' viewBox='0 0 9 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M9 0.951882L4.5 6L-2.2066e-07 0.951882L0.848528 -1.20525e-07L4.5 4.09624L8.15147 -4.39747e-07L9 0.951882Z' fill='%2300B7FF'/%3e%3c/svg%3e ");
    background-size: contain;
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 0;
    -webkit-transition: .3s;
    -o-transition: .3s;
    transition: .3s;
}

.multiselect__tags-wrap {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    width: 100%;
    gap: 5px;
    padding: 5px;
    padding-right: 30px;
}

.multiselect__tags input {
    border: none;
    padding: 0 14px;
}

.multiselect__tags input:focus {
    border-color: #D9D9D9;
}

.multiselect__tag {
    background: #00B7FF;
    padding: 5px 25px 5px 10px;
    margin: 0;
}

.multiselect__tag-icon:hover {
    background: #FF608D;
}

.multiselect__tag-icon:after {
    font-size: 16px;
    color: #fff;
}

.multiselect__placeholder {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start;
    width: 100%;
    min-height: 35px;
    padding: 0 14px;
    padding-right: 30px;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    position: relative;
    z-index: 1;
}

.multiselect__single {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start;
    width: 100%;
    min-height: 35px;
    font-size: 11px;
    line-height: 1.2;
    padding: 0 14px;
    padding-right: 30px;
    margin: 0;
    white-space: pre;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    position: relative;
    z-index: 1;
}

.multiselect__content {
    overflow: hidden;
}

.multiselect__content-wrapper {
    border: 1px solid #D9D9D9;
    border-top: none;
    border-radius: 0 0 5px 5px;
    padding-top: 11px;
    padding-bottom: 14px;
    margin: 0;
    top: 100%;
}

.multiselect__content-wrapper::-webkit-scrollbar {
    width: 5px;
    height: 5px;
    background-color: #F2F2F2;
}

.multiselect__content-wrapper::-webkit-scrollbar-thumb {
    background-color: #D9D9D9;
}

.multiselect__element {
    margin-bottom: 8px;
}

.multiselect__element:last-child {
    margin-bottom: 0;
}

.multiselect__option {
    min-height: auto;
    font-size: 12px;
    line-height: 1.2;
    color: #4F4F4F;
    white-space: normal;
    padding: 0 14px;
}

.multiselect__option--selected {
    font-weight: bold;
    color: #4F4F4F;
    background: transparent;
}

.multiselect__option--selected.multiselect__option--highlight {
    font-weight: bold;
    color: #4F4F4F;
    background: transparent;
}

.multiselect__option--highlight {
    font-weight: bold;
    color: #4F4F4F;
    background: transparent;
}
</style>
