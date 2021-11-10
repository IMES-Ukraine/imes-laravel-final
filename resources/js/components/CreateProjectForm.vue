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
                <form class="articles_create-box">
                    <div v-if="currentStep == 1 || currentStep == 5">
                        <div class="articles_create-block">
                            <div class="articles_create__item">
                                <p class="articles_create__item-title">Обложка</p>
                                <div class="articles_create__item-content">
                                        <div class="articles_create__item-file width-auto buttonAddFile">
                                            <input type="file" name="cover"
                                                   @change="handleUpload('cover')">
                                            <p><span data-placeholder="Загрузить">{{ project.options.files.cover }}</span></p>
                                            <button class="delete_file deleteFile" type="button"></button>
                                        </div>
                                        <span class="errors">{{ errorCover }}</span>
                                </div>
                            </div>
                            <div class="articles_create__item">
                                <p class="articles_create__item-title">Название</p>
                                <div class="articles_create__item-content">
                                    <div class="articles_create__name">
                                        <div class="articles_create__name-block">
                                            <validation-provider
                                                rules="required|max:35"
                                                ref="title"
                                                :custom-messages="{ max: 'Максимальное количество 50 символов' }"
                                                v-slot="{ validate, errors }">
                                                <input type="text" name="name" v-model="project.options.title"
                                                       @input="validate">
                                                <span class="errors">{{ errors[0] }}</span>
                                            </validation-provider>
                                            <p class="articles_create__name-note">50 символов</p>
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
                                            <span class="errors">{{ errorContent }}</span>
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
                    </div>
                    <div v-if="currentStep == 2">
                       <p class="articles_create-title">Створення пакета контента</p>
                        <content-pack />

                    </div>
                    <div v-if="currentStep == 3">
                        <p class="articles_create-title">Створення статті</p>
                        <content-article />
                    </div>
                    <div v-if="currentStep == 4">
                        <p class="articles_create-title">Створення теста</p>
                        <!--                            <project-close/>-->
                        <content-test :projectParent="project"/>
                    </div>
                    <div v-if="currentStep == 5">
                        <div class="articles_create__item">
                            <p class="articles_create__item-title">Тип подачи информации</p>
                            <div class="articles_create__item-content">
                                <div class="articles_create__grid width-third column-gap-25">
                                    <div class="articles_create__grid-block">
                                        <div class="articles_create__sorting">
                                            <input type="radio" name="sorting-1" v-model="project.options.presentation_type" value="at_once">
                                            <p><span class="icon-list">Все сразу</span></p>
                                        </div>
                                    </div>
                                    <div class="articles_create__grid-block">
                                        <div class="articles_create__sorting">
                                            <input type="radio" name="sorting-1" v-model="project.options.presentation_type" value="scheduled" @click="scheduledKey=Math.random(); setStep(6)">
                                            <p><span class="icon-list">По графику</span></p>
                                        </div>
                                    </div>
                                    <div class="articles_create__grid-block">
                                        <div class="articles_create__sorting">
                                            <input type="radio" name="sorting-1" v-model="project.options.presentation_type" value="series">
                                            <p><span class="icon-list">Последовательность</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="articles_create__item">
                            <p class="articles_create__item-title">Статус запуска проекта</p>
                            <div class="articles_create__item-content">
                                <div class="articles_create__visual">
                                    <div class="articles_create__indicators">
                                        <div class="articles_create__indicators-block">
                                            <p class="articles_create__indicators-title">Аудитория</p>
                                            <div class="articles_create__indicators-item articlesCreateIndicators">
                                                <div class="articles_create__indicators-line" data-value="1000" data-max="1000">
                                                    <span></span></div>
                                                <p class="articles_create__indicators-data"></p>
                                            </div>
                                        </div>
                                        <div class="articles_create__indicators-block">
                                            <p class="articles_create__indicators-title">Пользователи</p>
                                            <div class="articles_create__indicators-item articlesCreateIndicators">
                                                <div class="articles_create__indicators-line" data-value="700" data-max="1000">
                                                    <span></span></div>
                                                <p class="articles_create__indicators-data"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles_create__visual-quantity">
                                        <p>Количество активности</p>
                                        <p><b>1000</b></p>
                                    </div>
                                    <div class="articles_create__result not_ready">
                                        <!-- Для зеленого блока класс "ready" -->
                                        <i class="articles_create__result-icon"></i>
                                        <p class="articles_create__result-title">Часть аудитории еще не установили приложение
                                        </p>
                                        <ul class="articles_create__result-list">
                                            <li class="articles_create__result-item">уменьшите количество активности, или</li>
                                            <li class="articles_create__result-item">увеличить количество пользователей</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button  v-if="currentStep == 1 || currentStep == 5" class="articles_create-submit button-border" type="button"
                            @click.prevent="createProject">
                        <template v-if="packsPresent">Сохранить</template>
                        <template v-else>Далее</template>
                    </button>

                    <div v-if="currentStep == 6" >
                        <schedule />
                    </div>
                    <div v-if="currentStep == 7">
                        <project-preview />
                    </div>
                </form>
                </div>
            </div>
    </v-content>
</template>
<script>
import {ValidationProvider} from "vee-validate";
import {ARTICLE_COVER, PROJECT, PROJECT_IMAGE, TOKEN} from "../api/endpoints";
// import {ValidationObserver} from 'vee-validate'
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
import Schedule from "./fragmets/Project/schedule";


import ProjectMixin from "../ProjectMixin";
import ModalMixin from "../ModalMixin";

export default {
    name: 'CreateProjectForm',
    mixins: [ProjectMixin, ModalMixin],
    components: {
        ContentArticle,
        ContentTest,
        ContentPack,

        ValidationProvider,

        CreateArticleForm,
        VContent,
        ProjectFormSidebar,
        // ValidationObserver,
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
        Schedule,

        VRadio,
        VTextarea
    },
    data() {
        return {
            project: {...this.$store.state.project},
            isValidated: false,
            isComplex: false,
            picked: 'test',
            type: 'easy',
            new_user: '',
            targeting: false,
            errorContent: '',
            errorCover: '',
            scheduledKey: Math.random()
        }
    },
    computed: {

        contentList() {
            return this.$store.state.project.content;
        },
        packsPresent() {
            return Object.keys(this.contentList).length;
        },
        projectCover() {
            return this.project.options.files.cover;
        }
    },
    methods: {
        newContent() {
            this.errorContent = '';
            this.$store.dispatch('setContent', this.contentTemplate);
            this.setStep(2);
        },
        editItem(title) {
            this.$store.dispatch('setCurrentContent', title);
            this.setStep(2);
        },
        deleteItem(title) {
            this.$bvModal.msgBoxConfirm("Ви впевнені, що бажаєте видали дослідження " + title + " ?").then(value => {
                if (value) {
                    this.$store.dispatch('deleteContent', title);
                    this.project = this.$store.state.project;
                }
            });
        },
        async createProject() {
            this.errorContent = '';
            this.errorCover = '';
            this.project.content = this.$store.state.project.content;

            if (!Object.keys(this.project.content).length) {
                this.errorContent = 'Потрібно створити контент';
            }
            if (! this.projectCover) {
                this.errorCover = 'Виберіть обкладинку';
            }
            const valTitle = await this.$refs['title'].validate();
            this.isValidated = !this.errorContent.length && !this.errorCover.length && valTitle.valid;

                if (this.isValidated) {
                    await this.$store.dispatch('storeProject', this.project);
                    if (!this.errorContent) {

                        switch (this.currentStep) {
                            case 5:
                                this.setStep(7);
                                break;
                            case 6:
                                this.finalStoreProject();
                                break;
                            default:
                                this.setStep(5);
                        }
                    }
                }

        },

        handleUploadArticle(event) {
            let imageForm = new FormData()
            imageForm.append('file', event.target.files[0])

            axios.post(
                PROJECT_IMAGE + '/articles',
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

        getType(value) {
            this.$emit('update', value);
        },


        handleUpload(fileName) {
            if (event.target.files[0].size <= 1024 * 1024 * 1024) {
                this.project.options.files.cover = event.target.files[0].name;
                this.errorCover = '';
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
