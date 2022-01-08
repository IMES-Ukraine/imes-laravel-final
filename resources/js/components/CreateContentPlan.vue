<template>
    <v-content>

        <template v-slot:sidebar>
            <article-sidebar/>
        </template>

        <div class="articles">
            <div class="articles_create">
                <v-close :to="{name:'articleList'}"/>
                <div class="articles_tabs">
                    <swiper class="swiper articles_tabs-wrap articlesTabsNav" ref="swiper" :options="swiperOption" style="width: 100%;">
                        <swiper-slide :class="(item.id == 0 && item.active)?'articles_tabs-item active':'articles_tabs-item'" v-for="(item, key) in items" :key="'item_tabs_' + item.id">
                            <template v-if="item.real">
                                <div class="articles_tabs-info">{{ key + 1 }}</div>
                                <button class="articles_tabs-delete" type="button" data-toggle="modal" :data-target="'#db-modal--' + item.id"></button>
                            </template>
                            <template v-else>
                                <button class="articles_tabs-add" :disabled="(item.id != 0)?true:false"></button>
                            </template>
                        </swiper-slide>
                    </swiper>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </div>
                <div class="articles_tabs__content articlesTabsContent" :key="itemsKey">
                    <div class="articles_tabs__content-block" v-for="(item, key) in items" >
                        <div class="articles_create-box">
                            <div class="articles_create-block">
                                <div class="articles_create__item half">
                                    <p class="articles_create__item-title">Время</p>
                                    <div class="articles_create__item-content">
                                        <date-picker
                                            v-model="item.time"
                                            format="HH:mm"
                                            value-type="format"
                                            type="time"
                                            placeholder="HH:mm"
                                        ></date-picker>
                                        <div class="errors" v-if="item.time_error">{{ item.time_error }}</div>
                                    </div>
                                </div>
                                <div class="articles_create__item half">
                                    <p class="articles_create__item-title">Дата</p>
                                    <div class="articles_create__item-content">
                                        <date-picker v-model="item.date" value-type="format" format="DD-MM-YYYY"></date-picker>
                                        <div class="errors" v-if="item.date_error">{{ item.date_error }}</div>
                                    </div>
                                </div>
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Заголовок</p>
                                    <div class="articles_create__item-content direction-column">
                                        <div class="articles_create__name-block">
                                            <input
                                                class="form-control"
                                                type="text"
                                                v-model="item.title"
                                            >
                                            <div class="errors" v-if="item.title_error">{{ item.title_error }}</div>
                                        </div>
                                        <div class="articles_create__radio_circle">
                                            <div class="articles_create__radio_circle-block">
                                                <div class="article-edit__radio form-check form-check-inline">
                                                    <input
                                                        type="radio"
                                                        :id="name+item.id+'_1'"
                                                        :name="name+item.id"
                                                        :value="1"
                                                        :checked="(item.type == 1)?true:false"
                                                        @click="articleTypeStore(1, key)"
                                                    >
                                                    <label class="article-edit__label article-edit__radio form-check-label"
                                                           :for="name+item.id+'_1'"
                                                    >Новости</label>
                                                </div>
                                            </div>
                                            <div class="articles_create__radio_circle-block">
                                                <div class="article-edit__radio form-check form-check-inline">
                                                    <input
                                                        type="radio"
                                                        :id="name+item.id+'_2'"
                                                        :name="name+item.id"
                                                        :value="2"
                                                        :checked="(item.type == 2)?true:false"
                                                        @click="articleTypeStore(2, key)"
                                                    >
                                                    <label class="article-edit__label article-edit__radio form-check-label"
                                                        :for="name+item.id+'_2'"
                                                    >Информация</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="articles_create__item half">
                                    <p class="articles_create__item-title">Обложка</p>
                                    <div class="articles_create__item-content">
                                        <div :class="['articles_create__item-file', 'width-auto buttonAddFile', {has_file: item.cover_image.path} ]" :id="'inputFile'+item.id">
                                            <input
                                                type="file"
                                                class="input-file-hidden"
                                                v-on:change="handleUploadArticle($event, key)"
                                                role="button" />
                                            <p><span >{{ item.cover_image.disk_name || 'Загрузить' }}</span></p>
                                            <button class="delete_file deleteFile" @click="item.cover_image={}"></button>
                                        </div>
                                        <div v-if="item.image_error" class="errors">{{ item.image_error }}</div>
                                    </div>
                                </div>
                                <div class="articles_create__item half">
                                    <p class="articles_create__item-title">Галерея</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__media">
                                            <div v-for="file in item.featured_images" v-bind:key="file.itemId" class="articles_create__media-item">
                                                <div class="articles_create__media-img">
                                                    <img :src="file.path" alt="">
                                                </div>
                                            </div>
                                            <div class="articles_create__media-add">
                                                <input type="file" @change="addMedia($event, key)">
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
                                            v-model="item.excerpt"
                                        ></textarea>
                                    </div>
                                    <div class="errors" v-if="item.text_error">{{item.text_error}}</div>
                                </div>
                                <div class="articles_create-block">
                                    <div class="articles_create__item">
                                        <div class="articles_create__item-title has_radio">
                                            <input
                                                type="checkbox"
                                                name="article-has-insert"
                                                id="article-has-insert"
                                                v-model="item.textLocale"
                                                :checked="item.textLocale"
                                                @onclick="getTextInsert"
                                            >
                                            <i></i>
                                            <p>Вставка в тексте</p>
                                        </div>
                                        <div class="articles_create__item-content direction-column" v-if="item.textLocale">
                                            <v-input-text
                                                v-on:update:value="updateInsertTitle($event, key)"
                                                placeholder="Заголовок"
                                                :value="item.content[0].title"
                                                :text="''"
                                                :classes="'mb20'"
                                            />
                                            <v-textarea
                                                :rows="4"
                                                :text="item.content[0].content"
                                                v-on:update:text="updateInsertContent($event, key)"
                                                placeholder="Текст"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Теги</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__name-block">
                                            <div class="field_wrap_for_tags">
                                                <multiselect
                                                    v-model="item.tags"
                                                    :value="item.tags"
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
                                                        v-model="item.user"
                                                        :value="item.user"
                                                        @input="selectAuthor($event, key)"
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
                                    v-model="item.button"
                                    :text_button="item.button"
                                    @update="buttonStore($event, key)"
                                />
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Реком. статьи</p>
                                    <div class="articles_create__item-content">
                                        <multiselect
                                            v-model="item.recommended"
                                            :value="item.recommended"
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
                                    v-model="item.action"
                                    :text_button="item.action"
                                    @update="linkStore($event, key)"
                                />
                            </div>
                        </div>
                        <input type="hidden" v-model="item.post_id" />
                        <button class="articles_create-submit button-gradient" @click="submitForm(item.id)">{{ (item.post_id)?'Редактировать':'Опубликовать' }}</button>
                        <div class="modal db-modal fade" v-if="item.real" :id="'db-modal--' + item.id" tabindex="-1" role="dialog" aria-hidden="true"><!-- data -->
                            <div class="modal-dialog modal-dialog-centered db-edit-modal__dialog" role="document">
                                <div class="db-edit-modal__content modal-content">
                                    <button class="articles_create-close" data-dismiss="modal"></button>
                                    <div class="modal-body p-0">
                                        <div class="form-row">
                                            Вы уверены что хотите удалить статью "{{ item.title }}"?
                                        </div>
                                        <div class="mb20"></div>
                                        <div class="form-row">
                                            <div class="d-flex justify-content-center ml-auto mr-auto buttons mb-1">
                                                <button class="btn btn-outline-primary mr-4" type="button" @click="removePost(item.id)">
                                                    Да
                                                </button>
                                                <button class="btn btn-outline-primary" type="button" data-dismiss="modal">
                                                    Нет
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
import Multiselect from "vue-multiselect"
import {
    ARTICLE,
    USER_LIST,
    USER_CREATE_NAME,
    ARTICLE_COVER,
    TOKEN,
    ARTICLE_TAGS,
    ARTICLE_TIMES,
    ARTICLE_UPDATE,
    ARTICLE_DESTROY
} from "../api/endpoints";
import FragmentFormText from "./fragmets/text"
import ArticleMultiple from "./templates/article/form/multiple"
import { getRandomId, currentDate, changeFormatDate } from './../utils'
import VRadio from "./templates/inputs/radio"
import VTextarea from "./templates/inputs/textarea"
import VInputText from "./templates/inputs/text"
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

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
        VRadio,
        VTextarea,
        VInputText,
        DatePicker
    },
    data() {
        return {
            items: [],
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
            text_error: '',
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
            featured_images: [],
            articleType: 1,
            type: 1,
            textLocale: 0,
            current_date: currentDate(),
            image: [],
            itemsKey: Math.random()
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
        updateInsertTitle(value, key) {
            this.items[key].content[0].title = value
        },
        updateInsertContent(value, key) {
            this.items[key].content[0].content = value
        },
        getTextInsert() {
            this.textLocale = 1;
        },
        addTag (newTag, key) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.items[key].recommended.push(tag)
            this.items[key].chosenRecommended.push(tag)
        },
        selectAuthor (value, key) {
            this.items[key].user_id = this.items[key].user.id
        },
        submitForm(id) {
            let error = false

            for (const [index, item] of Object.entries(this.items)) {
                if (item.id === id) {
                    if (!item.title || !item.title.trim()) {
                        this.items[index].title_error = 'Название обязательно';
                        error = true;
                    } else {
                        this.items[index].title_error = '';
                    }

                    if (!item.date || !item.date.trim()) {
                        this.items[index].date_error = 'Дата обязательна';
                        error = true;
                    } else {
                        this.items[index].date_error = '';
                    }

                    let field = $('#inputFile'+item.id+' input');
                    let block = field.parents(".buttonAddFile");
                    let text = block.find("p span");

                    if (!item.cover_image.disk_name) {
                        this.items[index].image_error = 'Обложка обязательна'
                        error = true;
                    } else {
                        this.items[index].image_error = '';
                    }

                    if (!item.excerpt || !item.excerpt.trim()) {
                        this.items[index].text_error = 'Описание обязательно';
                        error = true;
                    } else {
                        this.items[index].text_error = '';
                    }

                    if (!error) {
                        let url = ARTICLE;
                        if (item.id) {
                            url = ARTICLE_UPDATE + '/' + item.id;
                        }

                        this.$post(url, item, {
                            params: {
                                access_token: TOKEN
                            },
                        })
                            .then((res) => {
                                document.location.reload();
                            })
                            .catch((error) => {
                                console.log(error)
                            }).finally(() => {
                            console.log('success or error')
                        });
                    }
                }
            }
        },
        AddNewUser() {
            this.$get(USER_CREATE_NAME + '/' + this.new_user).then( response => {
                this.authors = response.data
            })
        },
        buttonStore(value, key) {
            this.items[key].button = value
        },
        insertStore(value) {
            this.insert = value
        },
        linkStore(value, key) {
            this.items[key].action = value
        },
        articleTypeStore(value, key) {
            this.items[key].type = value;
        },
        multiplesStore(value) {
            this.featured_images = value
        },
        handleUploadArticle(event, id) {
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
                this.items[id].cover_image = file.data.data
                //this.image.push({id: id, image_id: file.data.data/*file.data.data.path*/})
            })
        },
        addMedia(event, id) {
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
                this.items[id].featured_images.push(obj)
                $('#article_multiples').val(null);
            })
        },
        removePost(id) {
            this.$delete(ARTICLE_DESTROY + id)
                .then((res) => {
                    document.location.reload();
                })
        }
    },
    mounted() {
        this.$get(ARTICLE + '?count=12&type=1').then( response => {
            this.recommended = response.data.data
        })
        this.$get(USER_LIST + '?count=12').then( response => {
            this.authors = response.data
        })
        this.$get(ARTICLE_TAGS).then( response => {
            this.tags = response.data
        })
        this.$get(ARTICLE_TIMES).then(response => {
            if (response.data.length > 0) {
                for (const [index, item] of Object.entries(response.data)) {

                    this.items.push({
                        id: item.id,
                        cover_image: item.cover_image,
                        content: [
                            {
                                type: 'text',
                                title: (item.content)?item.content[0].title:'',
                                content: (item.content)?item.content[0].content:''
                            }
                        ],
                        post_id: item.id,
                        real: true,
                        active: false,
                        date: changeFormatDate(item.date),
                        time: item.time.substr(0, 5),
                        title: item.title,
                        title_error: '',
                        time_error: '',
                        date_error: '',
                        image_error: '',
                        excerpt: item.excerpt,
                        text_error: '',
                        type: item.type,
                        text: item.content_html,
                        textLocale: (item.content)?1:0,
                        tags: item.tags,
                        user: (item.user)?[{id: item.user.id, name: item.user.name}]:0,
                        active_user_id: (item.user)?[{id: item.user.id, name: item.user.name}]:0,
                        recommended: item.recommended,
                        action: item.action,
                        featured_images: item.featured_images,//gallery,
                        button: item.button,
                    });
                }

                let length = response.data.length;
                let count = 12 - length;

                if (count > 0) {
                    for (let i = 0; i < count; i++) {
                        this.items.push({
                            id: i,
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
                            post_id: 0,
                            real: false,
                            active: false,
                            date: currentDate(),
                            time: '12:00',
                            title: '',
                            title_error: '',
                            time_error: '',
                            date_error: '',
                            image_error: '',
                            excerpt: '',
                            text_error: '',
                            type: 1,
                            text: '',
                            content_title: '',
                            content_text: '',
                            textLocale: 0,
                            tags: [],
                            user: {
                                id: 0,
                                name: ''
                            },
                            active_user_id: 0,
                            button: '',
                            recommended: [],
                            action: '',
                            featured_images: []
                        });
                    }
                }
            } else {
                for (let i = 0; i < 12; i++) {
                    this.items.push({
                        id: i,
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
                        post_id: 0,
                        real: false,
                        active: true,
                        date: currentDate(),
                        time: '12:00',
                        title: '',
                        title_error: '',
                        time_error: '',
                        date_error: '',
                        image_error: '',
                        excerpt: '',
                        text_error: '',
                        type: 1,
                        text: '',
                        textLocale: 0,
                        tags: [],
                        user: {
                            id: 0,
                            name: ''
                        },
                        active_user_id: 0,
                        button: '',
                        recommended: [],
                        action: '',
                        featured_images: []
                    });
                }
            }
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
        width: 100%;
        padding: 5px 0;
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
        left: -20px;
    }

    .swiper-button-prev:after {
        -webkit-transform: rotate(-180deg);
        -ms-transform: rotate(-180deg);
        transform: rotate(-180deg);
    }

    .swiper-button-next {
        right: -20px;
    }

    .swiper-button-prev.swiper-button-disabled,
    .swiper-button-next.swiper-button-disabled {
        opacity: 0;
    }
</style>
