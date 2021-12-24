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
                    <template v-if="currentStep == 1 || currentStep == 5">
                        <div class="articles_create-block">
                            <div class="articles_create__item">
                                <p class="articles_create__item-title">Обложка</p>
                                <file-input
                                    :value="project.options.files.cover"
                                    @fileInput="project.options.files.cover = $event"
                                    :error="errorCover"
                                    type="image"
                                    attachment="project"/>
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
                                        <div class="articles_create__item">
                                            <file-input
                                                :value="project.options.files.audience"
                                                @fileInput="project.options.files.audience = $event"
                                                :extensions="['csv']"
                                                attachment="project"/>
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
                                                </div>
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
                                                    @click.prevent="newContent()">
                                                <span class="icon-right">Создать</span>
                                            </button>
                                            <span class="errors">{{ errorContent }}</span>
                                        </div>
                                        <span v-for="content in this.$store.state.project.content" :key="content.title">
                                                    <div class="articles_create__grid-block">
                                                        <div class="articles_create__study">
                                                            <p class="articles_create__study-title">{{
                                                                    content.title
                                                                }}</p>
                                                            <div class="articles_create__study-controls">
                                                                <button type="button"
                                                                        class="articles_create__study-button articles_create__study-button--edit"
                                                                        @click="editContent(content.title)"></button>
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
                    </template>
                    <div v-if="currentStep == 2">
                        <p class="articles_create-title">Створення пакета контента</p>
                        <content-pack :key="contentTitle"/>

                    </div>
                    <div v-if="currentStep == 3">
                        <p class="articles_create-title">Створення статті</p>
                        <content-article/>
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
                                            <input type="radio" name="sorting-1"
                                                   v-model="project.options.presentation_type" value="at_once">
                                            <p><span class="icon-list">Все сразу</span></p>
                                        </div>
                                    </div>
                                    <div class="articles_create__grid-block">
                                        <div class="articles_create__sorting">
                                            <input type="radio" name="sorting-1"
                                                   v-model="project.options.presentation_type" value="scheduled"
                                                   @click="scheduledKey=Math.random(); setStep(6)">
                                            <p><span class="icon-list">По графику</span></p>
                                        </div>
                                    </div>
                                    <!--<div class="articles_create__grid-block">
                                        <div class="articles_create__sorting">
                                            <input type="radio" name="sorting-1" v-model="project.options.presentation_type" value="series">
                                            <p><span class="icon-list">Последовательность</span></p>
                                        </div>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="articles_create__item">
                            <div class="articles_create__item-title has_radio">
                                <input type="checkbox" name="checkbox_file" v-model="hasAgreement">
                                <i></i>
                                <p>Соглашение</p>
                            </div>
                            <div class="articles_create__item-content">
                                <textarea v-model="project.options.agreement"
                                          :disabled="!hasAgreement"/>
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
                                                <div class="articles_create__indicators-line" :data-value="totalAudience">
                                                    <span :style="'width:'+percentActive(usersAudience, Math.max(totalAudience, usersAudience))+'%;'"></span>
                                                </div>
                                                <p class="articles_create__indicators-data">{{totalAudience}}</p>
                                            </div>
                                        </div>
                                        <div class="articles_create__indicators-block">
                                            <p class="articles_create__indicators-title">Пользователи</p>
                                            <div class="articles_create__indicators-item articlesCreateIndicators">
                                                <div class="articles_create__indicators-line" :data-value="usersAudience">
                                                    <span :style="'width:'+percentActive(usersAudience, Math.max(totalAudience, usersAudience))+'%;'"></span>
                                                </div>
                                                <p class="articles_create__indicators-data">{{usersAudience}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles_create__visual-quantity">
                                        <p>Количество активности</p>
                                        <p><b>{{activity}}</b></p>
                                    </div>
                                    <div class="articles_create__result not_ready" v-if="(totalAudience > usersAudience) || (activity < usersAudience)">
                                        <!-- Для зеленого блока класс "ready" -->
                                        <i class="articles_create__result-icon"></i>
                                        <p class="articles_create__result-title">Часть аудитории еще не установили
                                            приложение
                                        </p>
                                        <ul class="articles_create__result-list">
                                            <li class="articles_create__result-item">уменьшите количество активности,
                                                или
                                            </li>
                                            <li class="articles_create__result-item">увеличить количество
                                                пользователей
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="articles_create__result ready" v-else>
                                        <!-- Для зеленого блока класс "ready" -->
                                        <i class="articles_create__result-icon"></i>
                                        <p class="articles_create__result-title">Проект готов к запуску!
                                        </p>
                                        <ul class="articles_create__result-list">
                                            <li class="articles_create__result-item">Вся аудитория является пользователями
                                            </li>
                                            <li class="articles_create__result-item">Количество активностей соответствует количеству пользователей
                                                пользователей
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button v-if="currentStep == 1 || currentStep == 5" class="articles_create-submit button-border"
                            type="button"
                            @click.prevent="createProject">
                        <template v-if="packsPresent">Сохранить</template>
                        <template v-else>Далее</template>
                    </button>

                    <div v-if="currentStep == 6">
                        <schedule/>
                    </div>
                    <div v-if="currentStep == 7">
                        <project-preview/>
                    </div>
                </form>
            </div>
        </div>
    </v-content>
</template>
<script>
import {ValidationProvider} from "vee-validate";
import {ARTICLE_COVER, PROJECT, PROJECT_FILE, PROJECT_IMAGE, TOKEN} from "../api/endpoints";
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

import store from "../store/index"
import FileInput from "./inputs/file-input";

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
        FileInput,
        VRadio,
        VTextarea,
    },
    data() {
        return {
            hasAgreement: false,
            isValidated: false,
            isComplex: false,
            picked: 'test',
            type: 'easy',
            new_user: '',
            targeting: false,
            errorContent: '',
            errorCover: '',
            contentTitle: '',
            totalAudience: 0,
            usersAudience: 0
        }
    },
    computed: {
        project: {
            get: function () {
                return store.state.project
            },
            set: function (newValue) {
                store.commit('storeProject', newValue);
            }
        },
        packsPresent() {
            return Object.keys(this.$store.state.project.content).length;
        },
        activity() {
            let sum = 0;
            for(let contIndex in this.project.content ){
                let testCount = this.project.content[contIndex].test.count || 0;
                let articleCount = this.project.content[contIndex].article.count || 0;
                sum += parseInt(testCount) + parseInt(articleCount);
            }
            return sum;
        }
    },
    watch: {
        currentStep() {
            if (this.currentStep == 5) {
                this.getStat();
            }
        }
    },
    methods: {
        newContent() {
            this.$store.dispatch("storeProject", this.project);
            this.errorContent = '';
            this.$store.dispatch('setCurrentAction', 'create');
            this.$store.dispatch('setCurrentContentTitle', '');
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
            if (this.project.options.files.cover.file_name == '') {
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
        showTargeting() {
            this.targeting = true
        },

        getType(value) {
            this.$emit('update', value);
        },

        getStat() {
            axios.put(PROJECT + '/stats', {
                data:  this.project
            }).then(res => {
               this.totalAudience = res.data.data.total;
               this.usersAudience = res.data.data.users;
            });
        },
        percentActive(status, total) {
            if (status) {
                let result = status / total;
                result = Math.ceil(result * 100);
                return parseInt(result);
            }

            return 0;
        }

    },
    mounted() {
        if (sessionStorage.project) {
            this.$store.dispatch("storeProject", JSON.parse(sessionStorage.project));
        }
        this.hasAgreement = !!this.project.options.agreement;
    },
}
</script>
<style>
.h20 {
    height: 20px;
}
</style>
