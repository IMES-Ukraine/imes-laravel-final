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
                        <div v-if="currentStep == 1 || currentStep == 5">
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
                                                <input type="text" name="name" v-model="project.options.title">
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
                                                        type="button">
                                                    <span class="icon-left" v-if="project.options.selected.category">Показать</span>
                                                    <span class="icon-left" v-else>Добавить</span>
                                                </button>
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
                                                <b-form-select class="articles_create-select"
                                                               v-model="project.options.selected.category"
                                                               :options="lists.categories"></b-form-select>

                                                <!--                                                <select class="articles_create-select"-->
                                                <!--                                                        v-model="project.options.selected.category">-->
                                                <!--                                                    <option v-for="item in lists.categories" :value="item.id"-->
                                                <!--                                                            :key="item.id">-->
                                                <!--                                                        {{ item.name }}-->
                                                <!--                                                    </option>-->
                                                <!--                                                </select>-->
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__several">
                                                    <div class="articles_create__several-block">
                                                        <b-form-select class="articles_create-select"
                                                                       v-model="project.options.selected.region"
                                                                       :options="lists.regions"></b-form-select>
                                                        <!--                                                        <select class="articles_create-select"-->
                                                        <!--                                                                v-model="project.options.selected.region">-->
                                                        <!--                                                            <option v-for="item in lists.region" :value="item.id"-->
                                                        <!--                                                                    :key="item.id">-->
                                                        <!--                                                                {{ item.name }}-->
                                                        <!--                                                            </option>-->
                                                        <!--                                                        </select>-->
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
                            <div class="articles_create-block">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title height-47">Контент</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__grid width-third column-gap-25">
                                            <div class="articles_create__grid-block">
                                                <button class="articles_create-add_btn height-47" type="button"
                                                        @click.prevent="newContent()"><span
                                                    class="icon-right">Создать</span>
                                                </button>
                                            </div>
                                            <span v-for="content in contentList" :key="content.title">
                                                    <div class="articles_create__grid-block">
                                                        <div class="articles_create__study">
                                                            <p class="articles_create__study-title">{{
                                                                    content.title
                                                                }}</p>
                                                            <div class="articles_create__study-controls">
                                                                <button type="button"
                                                                        class="articles_create__study-button articles_create__study-button--edit"
                                                                        @click="editItem(content.title)"></button>
                                                                <button type="button"
                                                                        class="articles_create__study-button articles_create__study-button--delete"
                                                                        @click="deleteItem(content.title)"></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </span>
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

                            <button class="articles_create-submit button-border" type="button" @click.prevent="showContent">
                                <template v-if="packsPresent">Сохранить</template>
                                <template v-else>Далее</template>
                            </button>
                        </div>

                        <div v-if="currentStep == 2">
                            <!--                            <project-close/>-->
                            <p class="articles_create-title">Створення пакета контента</p>
                            <content-pack/>

                        </div>
                        <div v-if="currentStep == 3">
                            <p class="articles_create-title">Створення статті</p>
                            <content-article/>
                        </div>
                        <div v-if="currentStep == 4">
                            <p class="articles_create-title">Создание теста</p>
                            <!--                            <project-close/>-->
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
                        <div v-if="currentStep == 6">
                            <project-preview/>
                        </div>
                    </form>
                </ValidationObserver>
            </div>
        </div>
    </v-content>
</template>
<script>
//import {required/*,minLength*/} from 'vuelidate/lib/validators'
import {ARTICLE_COVER, PROJECT, TOKEN} from "../api/endpoints";
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
import ProjectPreview from "./fragmets/Project/project-preview";

import ProjectMixin from "../ProjectMixin";
import ModalMixin from "../ModalMixin";

export default {
    name: 'CreateProjectForm',
    mixins: [ProjectMixin, ModalMixin],
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
        ProjectPreview,

        VRadio,
        VTextarea
    },
    data() {
        return {
            isComplex: false,
            picked: 'test',
            type: 'easy',
            new_user: '',
            targeting: false,
            block_content: false,
            errorContent: ''
        }
    },
    computed: {
        contentList() {
            return this.$store.state.project.content;
        },
        packsPresent() {
            return Object.keys(this.contentList).length;
        }
    },
    methods: {
        newContent() {
            this.$store.state.currentContent = null;
            this.setStep(2);
        },
        editItem(title) {
            this.$store.dispatch('editContent', title);
        },
        deleteItem(title) {
            this.$bvModal.msgBoxConfirm("Ви впевнені, що бажаєте видали дослідження " + title + " ?").then(value => {
                if (value) {
                    this.$store.dispatch('deleteContent', title);
                    this.project = this.$store.state.project;
                }
            });
        },
        saveCurrentProject() {
            this.$store.state.project = this.project;
            sessionStorage.project = JSON.stringify(this.project);
        },
        showContent() {
            this.errorFile = '';
            this.errorTitle = '';
            this.errorContent = '';

            if (this.project.options.files.cover == null) {
                this.errorFile = 'Поле обязательно'
            }

            if (this.project.options.title === '') {
                this.errorTitle = 'Требуется указать название'
            }

            if (this.project.options.title.length > 50) {
                this.errorTitle = 'Требуется указать название не больше 50 символов'
            }

            if (!Object.keys(this.project.content).length) {
                this.errorContent = 'Потрібно створити контент';
            }

            if (this.errorTitle === '' && this.errorFile === '' && this.errorContent == '') {
                this.block_content = true;
            }

            this.saveCurrentProject();

            if (this.block_content) {
                switch (this.currentStep) {
                    case 5:
                        this.setStep(6);
                        break;
                    case 6:
                       this.finalStoreProject();
                        break;
                    default:
                        this.setStep(5);
                }
            }
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
                this.article.images = file.data.data.id
            })
        },


        showTargeting() {
            this.targeting = true
        },
        validate() {
            this.$refs.form.validate().then(success => {
                if (!success) {
                    if (this.project.options.files.cover == null) {
                        this.errorFile = 'Поле обязательно';
                    } else {
                        this.errorFile = '';
                    }

                    return;
                }
            });
        },

        getType(value) {
            this.$emit('update', value);
        },


        handleUpload(fileName) {
            if (event.target.files[0].size <= 1024 * 1024 * 1024) {
                //this.files[fileName] = event.target.files[0].name;
                this.project.options.files.cover = event.target.files[0].name
            } else {
                //this.$set(this.errorFile, 'cover', 'Изображение слишком большое')
                this.errorFile = 'Изображение слишком большое';
            }
            //}
        },
    },
    mounted() {
        if (sessionStorage.project) {
            this.$store.state.project = JSON.parse(sessionStorage.project);
        }
        this.project = this.$store.state.project;


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
