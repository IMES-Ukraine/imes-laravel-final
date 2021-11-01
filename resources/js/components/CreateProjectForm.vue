<template>

    <v-content>

        <template v-slot:sidebar>
            <project-form-sidebar
                :tag="project.tag"/>
        </template>

        <div class="articles">
            <div class="articles_create">
                <project-close/>
                <project-alert-test/>
                <ValidationObserver ref="form" v-slot="{ handleSubmit }">
                    <form class="articles_create-box">
                        <div v-show="currentStep == 1">
                            <div class="articles_create-block">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Обложка</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__item-file width-auto buttonAddFile">
                                            <input type="file" name="cover" @change="handleUpload('cover')">
                                            <p><span data-placeholder="Загрузить"></span></p>
                                            <button class="delete_file deleteFile" type="button"></button>
                                        </div>
                                        <div v-if="errorFile" class="errors">{{ errorFile }}</div>
                                    </div>
                                </div>
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Название</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__name">
                                            <div class="articles_create__name-block">
                                                <input type="text" name="name" v-model="options.title">
                                                <p class="articles_create__name-note">50 символов</p>
                                                <div v-if="errorTitle" class="errors">{{ errorTitle }}</div>
                                            </div>
                                            <div
                                                class="articles_create__name-block articles_create__name-block--number">
                                                <input type="text" name="tag" v-model="project.tag" placeholder="#">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="articles_create-block">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Аудитория</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__grid width-half column-gap-50">
                                            <div class="articles_create__grid-block">
                                                <button class="articles_create-add_btn" @click="showTargeting"
                                                        type="button"><span class="icon-left">Добавить</span></button>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__item-file buttonAddFile">
                                                    <input type="file" name="audience"
                                                           accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                           @change="handleUpload('audience')">
                                                    <p><span data-placeholder="Загрузить персональную аудиторию"></span>
                                                    </p>
                                                    <button class="delete_file deleteFile" type="button"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="articles_create-block" v-show="targeting">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Таргетинг</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__grid width-half column-gap-50">
                                            <div class="articles_create__grid-block">
                                                <select class="articles_create-select"
                                                        v-model="options.selected.category">
                                                    <option v-for="item in options.category" :value="item.id"
                                                            :key="item.id">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__several">
                                                    <div class="articles_create__several-block">
                                                        <select class="articles_create-select"
                                                                v-model="options.selected.region">
                                                            <option v-for="item in options.region" :value="item.id"
                                                                    :key="item.id">
                                                                {{ item.name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <!--<div class="articles_create__several-block">
                                                        <select class="articles_create-select">
                                                            <option value="1" selected>Вся Украина</option>
                                                            <option value="2">Харьковская обл.</option>
                                                            <option value="3">Киевская обл.</option>
                                                        </select>
                                                        <button class="articles_create-plus"></button>
                                                    </div>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="articles_create-block" v-show="block_content">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title height-47">Контент</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__grid width-third column-gap-25">
                                            <div class="articles_create__grid-block">
                                                <button class="articles_create-add_btn height-47" type="button"
                                                        @click.prevent="setStep(2)"><span
                                                    class="icon-right">Создать</span></button>
                                            </div>

                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__study">
                                                    <p class="articles_create__study-title">Исследование 1.1</p>
                                                    <div class="articles_create__study-controls">
                                                        <button
                                                            class="articles_create__study-button articles_create__study-button--edit"
                                                            @click="editItem()"></button>
                                                        <button
                                                            class="articles_create__study-button articles_create__study-button--delete"></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__study">
                                                    <p class="articles_create__study-title">Исследование 1.2</p>
                                                    <div class="articles_create__study-controls">
                                                        <button
                                                            class="articles_create__study-button articles_create__study-button--edit"></button>
                                                        <button
                                                            class="articles_create__study-button articles_create__study-button--delete"></button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--<div class="row mb-4" style="display: none;" id="block_content">
                                <div class="article-edit__text col-3">
                                    Контент
                                </div>
                                <div class="col-3">
                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                        <div @click.prevent="setStep(2)">
                                            <PlusButton label="Створити"></PlusButton>
                                            <input class="form-control" type="hidden" v-model="questions">
                                            <input class="form-control" type="hidden" v-model="articles">
                                        </div>
                                        <span class="errors">{{ errors[0] }}</span>
                                    </ValidationProvider>
                                </div>

                            </div>-->

                            <button class="articles_create-submit button-border" type="button" @click="showContent">
                                <template v-if="finishProject">Сохранить</template>
                                <template v-else>Далее</template>
                            </button>
                        </div>

                        <div v-show="currentStep == 2">
                            <project-close/>
                            <p class="articles_create-title">Создание пакета контента</p>
                            <content-pack />

                        </div>

                        <div v-show="currentStep == 3">
                            <p class="articles_create-title">Створення статті</p>
                            <content-article/>
                        </div>

                        <div v-show="currentStep == 4">
                            <p class="articles_create-title">Создание теста</p>
                            <project-close/>
                            <content-test/>
                        </div>

                        <div v-if="currentStep == 5">
                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Статус запуска проекта
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            Ayдитория
                                        </div>
                                        <div class="col-12">
                                            Пользователи
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 mb-2 smaller-text__article">
                                            1000
                                        </div>
                                        <div class="col-12 smaller-text__article">
                                            700
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-12 mb-2 smaller-text__article">
                                            Количество активности
                                        </div>
                                        <div class="col-12">
                                            1000
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </ValidationObserver>
            </div>
        </div>
    </v-content>
</template>
<script>
//import {required/*,minLength*/} from 'vuelidate/lib/validators'
import { ARTICLE_COVER, TOKEN} from "../api/endpoints";
import {ValidationObserver} from 'vee-validate'
import PlusButton from './controls/PlusButton'
import VContent from "./templates/Content"
import axios from 'axios'
import ProjectFormSidebar from "./templates/project/form/sidebar"
import ProjectClose from "./templates/project/Close"
import ProjectAlertTest from "./templates/project/AlertTest"
import VCheckbox from "./templates/inputs/checkbox"
import ArticleInputText from "./templates/article/form/text"
import FragmentFormText from "./fragmets/text"
import Multiselect from "vue-multiselect"
import VButton from "./templates/inputs/button"
import ArticleFormButton from "./templates/article/form/button"
import ArticleMultiple from "./templates/article/form/multiple"
import Cover from "./fragmets/cover"
import ArticleInputTitle from "./templates/article/form/title"


import VRadio from "./templates/inputs/radio"
import VTextarea from "./templates/inputs/textarea"
import CreateArticleForm from "./CreateArticleForm";

import ContentPack from "./fragmets/Project/content-pack";
import ContentArticle from "./fragmets/Project/content-article";
import ContentTest from "./fragmets/Project/content-test";


import ProjectMixin from "../ProjectMixin";

export default {
    name: 'CreateProjectForm',
    mixins: [ProjectMixin],
    components: {
        ContentArticle,
        ContentTest,
        ContentPack,

        CreateArticleForm,
        VContent,
        ProjectFormSidebar,
        ValidationObserver,
        PlusButton,
        ProjectClose,
        ProjectAlertTest,
        VCheckbox,
        ArticleInputText,
        FragmentFormText,
        Multiselect,
        VButton,
        ArticleFormButton,
        ArticleMultiple,
        ArticleInputCover: Cover,
        ArticleInputTitle,

        VRadio,
        VTextarea
    },
    data() {
        return {
            options: {
                ...this.$store.state.project.options
            },
            // tests: this.$store.state.questions,
            questions: this.$store.state.questions,
            project: {},

            isComplex: false,
            picked: 'test',
            type: 'easy',
            new_user: '',
            targeting: false,
            block_content: false,
        }
    },
    computed: {
        getPayload() {
            return {
                options: this.options,
                article: this.article,
                test: this.questions,
                entity: this.entity,
            }
        },
    },
    props: {
        msg: {
            type: String
        }
    },
    methods: {

        sendForm() {
            axios.get(`http://jsonplaceholder.typicode.com/posts`).then(
                response => {
                    this.title = response.data
                }
            )

            let formData = new FormData();
            formData.append('coverImage', this.files.cover)
            formData.append('audienceDatabase', this.files.audience)

            axios.post(`http://jsonplaceholder.typicode.com/posts`, formData).then(
                response => {
                    this.title = response.data.id
                }
            )
        },
        submitForm() {
/*            this.$refs.form.validate().then(success => {
                if (!success) {
                    if (this.options.files.cover == null) {
                        this.errorFile = 'Поле обязательно';
                    } else {
                        this.errorFile = '';
                    }

                    return;
                }

                let content = {
                    "title": this.content.title,
                    "article": {
                        "count": this.article.count,
                        "points": this.article.points,
                        "frequency": this.article.frequency
                    },
                    "test": {
                        "count": this.test.count,
                        "points": this.test.points,
                        "canRetake": this.test.canRetake
                    }
                }

                this.$post(PROJECT, {
                    options: this.options,
                    article: this.article,
                    tests: this.questions,
                    content: content
                })
                    .then((res) => {
                        this.$router.push({name: 'projectList'});
                    })
                    .catch((error) => {
                        console.log(error)
                    }).finally(() => {
                    console.log('success or error')
                });
            })*/

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
                this.article.images = file.data.data.id
            })
        },
        storeProject() {
            this.$store.dispatch('storeProject', this.options)
        },

        showTargeting() {
            this.targeting = true
        },
        validate() {
            this.$refs.form.validate().then(success => {
                if (!success) {
                    if (this.options.files.cover == null) {
                        this.errorFile = 'Поле обязательно';
                    } else {
                        this.errorFile = '';
                    }

                    return;
                }
            });
        },

        addComplexQuestion() {
            this.questions.push({
                question: {
                    complex: {
                        title: '',
                        text: '',
                        points: null,
                        media: {
                            cover: null,
                            video: null,
                        },
                    }
                },
            })
        },
        addQuestion() {
            this.questions.push({
                question: {
                    title: '',
                    text: '',
                    description: '',
                    link: '',
                    button: null,
                    count: null,
                    points: null,
                    media: {
                        cover: null,
                        video: null,
                    },
                    isComplex: this.isComplex,
                    agreement: null
                },
                complex_question: [],
                variants: [
                    {
                        itemId: 'variant-' + Math.random().toString(36).substr(2, 9),
                        title: 'A',
                        variant: '',
                        isCorrect: false,
                        answer: {
                            type: true,
                            right: true,
                            media: []
                        }
                    }
                ],
                answer: {
                    correct: [],
                    type: 'text' //answer type (variants | text field)
                },
            })
        },
        update(value) {
            this.check = value
        },
        getType(value) {
            this.$emit('update', value);
        },

        onChangePicked(event, key) {
            for (const [index, value] of Object.entries(this.tests[key].variants)) {
                if (index >= 2) {
                    this.tests[key].variants.splice(index, 1)
                    return
                }
            }
        },
        handleUpload(fileName) {
            if (event.target.files[0].size <= 1024 * 1024 * 1024) {
                //this.files[fileName] = event.target.files[0].name;
                this.options.files.cover = event.target.files[0].name
            } else {
                //this.$set(this.errorFile, 'cover', 'Изображение слишком большое')
                this.errorFile = 'Изображение слишком большое';
            }
            //}
        },
    },
    mounted() {
        if (sessionStorage.options) {
            this.options = JSON.parse(sessionStorage.options);
        }
        if (sessionStorage.article) {
            this.article = JSON.parse(sessionStorage.article);
        }
        this.addQuestion()

    },
    /*validations: {
        title: {
            required
        },
        text: {
            required
        }
    },*/
}
</script>
<style scoped>
</style>
