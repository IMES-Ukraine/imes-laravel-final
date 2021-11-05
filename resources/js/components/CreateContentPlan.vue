<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="articles">
            <div class="articles_create">
                <v-close :to="{name:'articleList'}"/>
                <swiper class="swiper articles_tabs articlesTabsNav" ref="swiper" :options="swiperOption" style="width: 100%;">
                    <swiper-slide class="articles_tabs-item" v-for="item in items" :key="'item_tabs_' + item">
                        <div class="articles_tabs-item">
                            <button class="articles_tabs-add"></button>
                        </div>
                    </swiper-slide>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </swiper>
                <div class="articles_tabs__content articlesTabsContent">
                    <div class="articles_tabs__content-block" v-for="item in items">
                        <div class="articles_create-box">
                            <div class="articles_create-block">
                                <div class="articles_create__item half">
                                    <p class="articles_create__item-title">Время</p>
                                    <div class="articles_create__item-content">
                                        <input type="text" class="date_time" value="12 : 00">
                                    </div>
                                </div>
                                <div class="articles_create__item half">
                                    <p class="articles_create__item-title">Дата</p>
                                    <div class="articles_create__item-content">
                                        <input type="text" class="date_time" value="11.04.19">
                                    </div>
                                </div>
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Заголовок</p>
                                    <div class="articles_create__item-content direction-column">
                                        <div class="articles_create__name-block">
                                            <input
                                                class="form-control"
                                                type="text"
                                                v-model="title"
                                            >
                                            <div class="errors" v-if="title_error">
                                                Заголовок обов'язковий
                                            </div>
                                        </div>
                                        <div class="articles_create__radio_circle">
                                            <div class="articles_create__radio_circle-block">
                                                <v-radio
                                                    :id="name+item+'_1'"
                                                    :name="name"
                                                    :value="1"
                                                    :checked="true"
                                                    v-on:update:value="getType"
                                                >Новости</v-radio>
                                            </div>
                                            <div class="articles_create__radio_circle-block">
                                                <v-radio
                                                    :id="name+item+'_2'"
                                                    :name="name"
                                                    :value="2"
                                                    v-on:update:value="getType"
                                                >Информация</v-radio>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="articles_create__item half">
                                    <p class="articles_create__item-title">Обложка</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__item-file width-auto buttonAddFile">
                                            <input
                                                type="file"
                                                id="articleCover"
                                                name="addCover"
                                                class="input-file-hidden"
                                                v-on:change="handleUploadArticle"
                                                role="button"
                                            >
                                            <p><span data-placeholder="Загрузить">Загрузить</span></p>
                                            <button class="delete_file deleteFile"></button>
                                        </div>
                                        <div v-if="errorArticleCover" class="errors">{{ errorArticleCover }}</div>
                                    </div>
                                </div>
                                <div class="articles_create__item half">
                                    <p class="articles_create__item-title">Галерея</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__media">
                                            <SimpleTestMedia :media="multiples"></SimpleTestMedia>
                                            <div class="articles_create__media-add">
                                                <input type="file" name="file" id="article_multiples" @change="addMedia($event)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Текст</p>
                                    <div class="articles_create__item-content">
                        <textarea
                            class="form-control"
                            rows="4"
                            v-model="$v.text.$model"
                        ></textarea>
                                    </div>
                                    <div class="errors" v-if="$v.text.$error">Текст обов'язковий</div>
                                </div>
                                <article-form-insert
                                    v-bind:insert.sync="insert"
                                    v-bind:textInsert.sync="textInsert"
                                    @insert="insertStore"
                                />
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Теги</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__name-block">
                                            <div class="field_wrap_for_tags">
                                                <multiselect
                                                    v-model="chosenTags"
                                                    tag-placeholder="Додати таг"
                                                    placeholder="Вибрати таг"
                                                    label="name"
                                                    track-by="id"
                                                    :options="tags"
                                                    :multiple="true"
                                                    :taggable="true"
                                                    :show-labels="false"
                                                    :close-on-select="false"
                                                    @tag="addTag"
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
                                                        v-model="user_id"
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
                                                    <input type="text" id="new_user" v-model="new_user" />
                                                </div>
                                                <button class="articles_create__addition-button" type="button" @click="AddNewUser">Добавить автора</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <article-form-button
                                    :label="'Кнопка'"
                                    :id="'is-article-button'"
                                    :name="'is-article-button'"
                                    :text_button="button"
                                    @update="buttonStore"
                                />
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Реком. статьи</p>
                                    <div class="articles_create__item-content">
                                        <multiselect
                                            v-model="chosenRecommended"
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
                                    :id="'is-article-link'"
                                    :name="'is-article-link'"
                                    :text_button="link"
                                    @update="linkStore"
                                />
                            </div>
                        </div>
                        <button class="articles_create-submit button-gradient" @click="submitForm">Опубликовать</button>
                    </div>
                </div>
            </div>
        </div>

    </v-content>
</template>

<script>
import ArticleSidebar from "./templates/article/sidebar"
import VContent from "./templates/Content"
import VClose from "./templates/Close"
import { Swiper, SwiperSlide } from 'vue-awesome-swiper'
import {required} from 'vuelidate/lib/validators'
import VSidebar from "./templates/Sidebar"
import ArticleInputTitle from "./templates/article/form/title"
import Cover from "./fragmets/cover"
import ArticleInputText from "./templates/article/form/text"
import ArticleFormInsert from "./templates/article/form/insert"
import ArticleFormSelect from "./templates/article/form/select"
import VButton from "./templates/inputs/button"
import ArticleFormButton from "./templates/article/form/button"
import Multiselect from "vue-multiselect";
import {ARTICLE, USER, USER_CREATE_NAME, ARTICLE_COVER, TOKEN, ARTICLE_TAGS} from "../api/endpoints";
import FragmentFormText from "./fragmets/text"
import ArticleMultiple from "./templates/article/form/multiple"
import SimpleTestMedia from "./fragmets/SimpleTestMedia"
import { getRandomId } from './../utils'
import VRadio from "./templates/inputs/radio"

export default {
    name: "CreateContentPlan",
    components: {
        VClose,
        VContent,
        ArticleSidebar,
        Swiper,
        SwiperSlide,
        VSidebar,
        ArticleInputTitle,
        Cover,
        ArticleInputText,
        ArticleFormInsert,
        ArticleFormSelect,
        VButton,
        ArticleFormButton,
        Multiselect,
        FragmentFormText,
        ArticleMultiple,
        SimpleTestMedia,
        VRadio
    },
    data() {
        return {
            //...this.$store.state.articles,
            items: 11,
            swiperOption: {
                spaceBetween: 22,
                slidesPerView: 'auto',
                direction: 'horizontal',
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                }
            },
            new_user: '',
            name: 'typePublication',
            errorArticleCover: '',
            recommended: [],
            authors: [],
            user_id: 0,
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
            textLocale: 0
        }
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
            this.errorArticleCover = '';
            let error = false

            if (this.image == null) {
                this.errorArticleCover = 'Обложка обязательна'
                error = true;
            }

            this.$v.$touch()
            if (this.$v.$invalid || error) {
                //this.$store.dispatch('submitArticle', this.$data)
            } else {
                this.$post(ARTICLE, {
                    title: this.title,
                    articleType: this.articleType,
                    text: this.text,
                    button: this.button,
                    action: this.link,
                    insert: this.insert,
                    user: this.user_id,
                    cover_image_id: this.image,
                    gallery: this.multiples,
                    tags: this.chosenTags,
                    recommended: this.chosenRecommended
                })
                    .then((res) => {
                        this.$router.push({ name: 'articleList' });
                    })
                    .catch((error) => {
                        console.log(error)
                    }).finally(() => {
                    console.log('success or error')
                });
                /*this.$store.dispatch('submitArticle', this.$data).then(() => {
                    this.$router.push({name: 'createContent'})
                })*/
            }
        },
        AddNewUser() {
            this.$get(USER_CREATE_NAME + '/' + this.new_user).then( response => {
                this.authors = response.data
            })
        },
        buttonStore(value) {
            this.button = value
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
                this.name = event.target.files[0].name
                //this.articles[0].imeges.push(file.data)
                this.image = file.data.data.id
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
                    itemId: getRandomId(),
                    file: file.data.data.id,
                    name: event.target.files[0].name,
                    data: file.data,
                    path: file.data.data.path
                };
                this.multiples.push(obj)
                $('#article_multiples').val(null);
            })
        },
    },
    mounted() {
        this.$get(ARTICLE + '?count=12&type=1').then( response => {
            this.recommended = response.data.data
        })
        this.$get(USER + '?count=12').then( response => {
            this.authors = response.data
        })
        this.$get(ARTICLE_TAGS).then( response => {
            this.tags = response.data
        })
    }
}
</script>

<style scoped>
    .swiper-slide {
        width: 45px;
        height: 45px;
    }

    .swiper-container {
        width: calc(100% + 44px);
        padding: 5px 22px;
        margin-left: -22px;
    }

    .swiper-wrapper {
        gap: 0 22px;
    }

    .swiper-button-prev,
    .swiper-button-next {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        background: none;
        margin: 0;
        -webkit-transform: translate(0, -50%);
        -ms-transform: translate(0, -50%);
        transform: translate(0, -50%);
    }

    .swiper-button-prev:after,
    .swiper-button-next:after {
        content: "";
        display: block;
        width: 8px;
        height: 11px;
        background-color: #BDBDBD;
        -webkit-mask-image: url("data:image/svg+xml;charset=UTF-8,%3csvg width='8' height='11' viewBox='0 0 8 11' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M2.18837 0.213756L6.9236 4.89361C7.12312 5.09061 7.12312 5.40888 6.9236 5.60639L2.18837 10.2862C1.90034 10.5713 1.43173 10.5713 1.1432 10.2862C0.855176 10.0012 0.855176 9.53868 1.1432 9.25367L5.19393 5.24974L1.1432 1.24684C0.855175 0.961325 0.855175 0.498764 1.1432 0.213756C1.43173 -0.0712523 1.90034 -0.0712524 2.18837 0.213756Z' fill='%23BDBDBD'/%3e%3c/svg%3e ");
        mask-image: url("data:image/svg+xml;charset=UTF-8,%3csvg width='8' height='11' viewBox='0 0 8 11' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M2.18837 0.213756L6.9236 4.89361C7.12312 5.09061 7.12312 5.40888 6.9236 5.60639L2.18837 10.2862C1.90034 10.5713 1.43173 10.5713 1.1432 10.2862C0.855176 10.0012 0.855176 9.53868 1.1432 9.25367L5.19393 5.24974L1.1432 1.24684C0.855175 0.961325 0.855175 0.498764 1.1432 0.213756C1.43173 -0.0712523 1.90034 -0.0712524 2.18837 0.213756Z' fill='%23BDBDBD'/%3e%3c/svg%3e ");
    }

    .swiper-button-prev {
        left: 0;
    }

    .swiper-button-prev:after {
        -webkit-transform: rotate(-180deg);
        -ms-transform: rotate(-180deg);
        transform: rotate(-180deg);
    }

    .swiper-button-next {
        right: 0;
    }

    .swiper-button-prev.swiper-button-disabled,
    .swiper-button-next.swiper-button-disabled {
        opacity: 0;
    }
</style>
