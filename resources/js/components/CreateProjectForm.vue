<template>

    <v-content>

        <template v-slot:sidebar>
            <project-form-sidebar
            :tag="project.tag"/>
        </template>

        <div class="articles">
            <div class="articles_create">
                <project-close/>
                <ValidationObserver ref="form" v-slot="{ handleSubmit }">
                    <form class="articles_create-box">
                        <div v-show="getStep == 1">
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
                                            <div class="articles_create__name-block articles_create__name-block--number">
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
                                                <button class="articles_create-add_btn" @click="showTargeting" type="button"><span class="icon-left">Добавить</span></button>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__item-file buttonAddFile">
                                                    <input type="file" name="audience" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" @change="handleUpload('audience')">
                                                    <p><span data-placeholder="Загрузить персональную аудиторию"></span></p>
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
                                                <select class="articles_create-select" v-model="options.selected.category">
                                                    <option v-for="item in options.category" :value="item.id" :key="item.id">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__several">
                                                    <div class="articles_create__several-block">
                                                        <select class="articles_create-select" v-model="options.selected.region">
                                                            <option v-for="item in options.region" :value="item.id" :key="item.id">
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
                                                <button class="articles_create-add_btn height-47" type="button" @click.prevent="setStep(2)"><span class="icon-right">Создать</span></button>
                                            </div>
                                            <!--<div class="articles_create__grid-block">
                                                <div class="articles_create__study">
                                                    <p class="articles_create__study-title">Исследование 1.1</p>
                                                    <div class="articles_create__study-controls">
                                                        <button class="articles_create__study-button articles_create__study-button--edit"></button>
                                                        <button class="articles_create__study-button articles_create__study-button--delete"></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__study">
                                                    <p class="articles_create__study-title">Исследование 1.2</p>
                                                    <div class="articles_create__study-controls">
                                                        <button class="articles_create__study-button articles_create__study-button--edit"></button>
                                                        <button class="articles_create__study-button articles_create__study-button--delete"></button>
                                                    </div>
                                                </div>
                                            </div>-->
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

                        <div v-show="getStep == 2">
                            <project-close/>
                            <p class="articles_create-title">Создание пакета контента</p>
                            <div class="articles_create-box">
                                <div class="articles_create-block">
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title">Выбор шаблона</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__grid width-half column-gap-50">
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__sorting">
                                                        <input type="radio" name="sorting-1" checked="">
                                                        <p><span class="icon-plus">Партнерский пакет №1</span></p>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__sorting">
                                                        <input type="radio" name="sorting-1">
                                                        <p><span class="icon-plus">Уникальный пакет</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="articles_create-block">
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title">Название</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__name-block">
                                                <validation-provider
                                                    rules="required|max:35"
                                                    :custom-messages="{ max: 'Максимальное количество 35 символов' }"
                                                    v-slot="{ errors }">

                                                    <input
                                                        class="form-control"
                                                        type="text"
                                                        v-model="content.title">
                                                    <span class="errors">{{ errors[0] }}</span>
                                                </validation-provider>
                                                <p class="articles_create__name-note">35 символов</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title height-47">Статьи</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__grid width-main-1">
                                                <div class="articles_create__grid-block">
                                                    <button class="articles_create-add_btn height-47" type="button" @click.prevent="setStep(3)" id="add_new_article_button"><span class="icon-right">Создать</span></button>
                                                    <div class="articles_create__study" id="add_new_article">
                                                        <p class="articles_create__study-title">Статья 1.1. Заголовок статьи</p>
                                                        <div class="articles_create__study-controls">
                                                            <button class="articles_create__study-button articles_create__study-button--edit" @click.prevent="setStep(3)" type="button"></button>
                                                            <button class="articles_create__study-button articles_create__study-button--delete" @click.prevent="reloadBlockArticle"></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Количество</p>
                                                        <validation-provider rules="required" v-slot="{ errors }">
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                name="name"
                                                                v-model="content.count">

                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Баллы</p>
                                                        <validation-provider
                                                            rules="required"
                                                            v-slot="{ errors }">

                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                name="name"
                                                                v-model="content.points">

                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Частота</p>
                                                        <validation-provider
                                                            rules="required"
                                                            v-slot="{ errors }">

                                                            <input
                                                                class="form-control"
                                                                type="number"
                                                                name="name"
                                                                v-model="content.frequency">

                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title height-47">Тесты</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__grid width-main-1">
                                                <div class="articles_create__grid-block">
                                                    <button class="articles_create-add_btn height-47" type="button" @click.prevent="setStep(4)" id="add_new_test_button"><span class="icon-right">Создать</span></button>
                                                    <div class="articles_create__study" id="add_new_test">
                                                        <p class="articles_create__study-title">Статья 1.1. Заголовок статьи</p>
                                                        <div class="articles_create__study-controls">
                                                            <button class="articles_create__study-button articles_create__study-button--edit" type="button" @click.prevent="setStep(4)"></button>
                                                            <button class="articles_create__study-button articles_create__study-button--delete" type="button" @click.prevent="reloadBlockSurveyTest"></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Количество</p>
                                                        <validation-provider
                                                            rules="required"
                                                            v-slot="{ errors }">

                                                            <input
                                                                class="form-control"
                                                                type="number"
                                                                name="name"
                                                                v-model="test.count">
                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Баллы</p>
                                                        <validation-provider
                                                            rules="required"
                                                            v-slot="{ errors }">

                                                            <input
                                                                class="form-control"
                                                                type="number"
                                                                name="name"
                                                                v-model="test.points">

                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <div class="articles_create__field_with_label-checkbox">
                                                            <v-checkbox
                                                                :id="'can-retake-button'"
                                                                :name="'can-retake-button'"
                                                                v-model="test.canRetake"
                                                            />
                                                            <i></i>
                                                            <p>Повторное прохождение</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="articles_create-note">1 пользователь = 1 тест + 2 статьи = 60 баллов</p>
                            </div>

                            <button class="articles_create-submit button-gradient" type="button" @click="submitForm">сохранить</button>
                        </div>

                        <div v-show="getStep == 3">
                            <project-close/>
                            <h4>
                                <center>Створення статтi</center>
                            </h4>
                            <br>
                            <!-- article-title -->
                            <!--<article-input-title
                                v-bind:type.sync="type"
                                :articleType="articleType"
                                @update="articleTypeStore"
                            />-->
                            <div class="row mb-4">

                                <div class="article-edit__text col-3">
                                    Заголовок
                                </div>

                                <div class="col-9">
                                    <div class="row">

                                        <div class="col-12 mb-2">
                                            <label>
                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    name="title"
                                                    id="article_title"
                                                    v-model="articles.title"
                                                >
                                            </label>
                                            <div v-if="errorArticleTitle" class="errors">{{ errorArticleTitle }}</div>
                                        </div>

                                        <div class="col-12">
                                            <v-radio
                                                id="typePublication_1"
                                                name="typePublication"
                                                :value="1"
                                                :checked="(articleType==1)?true:false"
                                                v-on:update:value="getType"
                                            >
                                                Новини
                                            </v-radio>
                                            <v-radio
                                                id="typePublication_2"
                                                name="typePublication"
                                                :value="2"
                                                :checked="(articleType==2)?true:false"
                                                v-on:update:value="getType"
                                            >
                                                Iнформацiя
                                            </v-radio>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!-- end-article-title -->

                            <!-- article-cover -->
                            <article-input-cover
                                v-bind:file.sync="images"
                                :file-key="'article'"
                            />
                            <!-- end-article-cover -->

                            <!-- article-cover -->
                            <article-multiple
                                v-bind:files.sync="multiples"
                                :title="'Галерея'"
                                :images="multiples"
                                @update="multiplesStore"
                            />

                            <!-- article-content -->
                            <article-input-text
                                :title="'Текст статтi'"
                                :error="'Контент заповнити обов\'язково'"
                            />
                            <!-- end-article-content -->

                            <!-- article-form-author -->
                            <fragment-form-text :title="'Автор'">
                                <multiselect
                                    v-model="user_id"
                                    tag-placeholder="Обрати автора"
                                    placeholder="Обрати автора"
                                    label="name"
                                    track-by="id"
                                    :options="authors"
                                />
                                <input type="text" id="new_user" v-model="new_user" />
                                <button type="button" class="btn btn-outline-primary" @click="AddNewUser">Добавить автора</button>
                            </fragment-form-text>
                            <!-- end-article-form-author -->

                            <!-- article-insert -->
                            <article-form-insert
                                v-bind:insert.sync="insert"
                                v-bind:textInsert.sync="textInsert"
                                @insert="insertStore"
                            />
                            <!-- end-article-insert -->

                            <!-- article-select-category -->
                            <!--<article-form-select
                                :name="'categories'"
                                :label="'Категориi'"
                                v-bind:value.sync="category"
                            />-->
                            <!-- end-article-select-category -->

                            <!-- article-select-headings -->
                            <!--<article-form-select
                                :name="'headings'"
                                :label="'Рубрики'"
                                v-bind:value.sync="headings"
                            />-->
                            <!-- end-article-select-headings -->

                            <!-- article-select-authors -->
                            <!--<article-form-select
                                :name="'authors'"
                                :label="'Автор'"
                                v-bind:value.sync="author"
                            />-->
                            <!-- end-article-select-authors -->

                            <!-- article-form-button -->
                            <article-form-button
                                :label="'Кнопка'"
                                :id="'is-article-button'"
                                :name="'is-article-button'"
                                :text_button="text_button"
                                @update="buttonStore"
                            />
                            <!-- end-article-form-button -->

                            <!-- article-form-direct-link -->
                            <article-form-button
                                :label="'Пряма ссилка'"
                                :id="'is-article-link'"
                                :name="'is-article-link'"
                                :text_button="link"
                                @update="linkStore"
                            />
                            <!-- end-article-form-direct-link -->

                            <!-- article-form-recommend-article -->
                            <fragment-form-text :title="'Рекомендованi статтi'">
                                <multiselect
                                    v-model="chosenRecommended"
                                    tag-placeholder="Додати статтю"
                                    placeholder="Вибрати статтю"
                                    label="title"
                                    track-by="id"
                                    :options="recommended"
                                    :multiple="true"
                                    :taggable="true"
                                    @tag="addTag"
                                />
                            </fragment-form-text>
                            <!-- end-article-form-recommend-article -->

                            <!-- publish button -->
                            <v-button @click="saveArticle"/>
                            <!-- end publish button -->
                        </div>

                        <div v-show="getStep == 4">
                            <project-close/>
                            <h4>Создание</h4>

                            <div class="col-12">
                                <input type="radio" name="radio" id="one" value="test" v-model="picked">
                                <label for="one">Теста</label>
                                <input type="radio" name="radio" id="two" value="survey" v-model="picked">
                                <label for="two">Опроса</label>
                                <br>
                            </div>

                            <div v-if="picked === 'test'">
                                <div class="col-12">
                                    <input type="radio" name="radio_type" id="easy" value="easy" v-model="type">
                                    <label for="easy">Простой</label>
                                    <input type="radio" name="radio_type" id="complex" value="complex" v-model="type">
                                    <label for="complex">Сложный</label>
                                    <br>
                                </div>

                                <div v-if="type === 'easy'">
                                    <div v-for="item in questions" v-bind:key="item.title">

                                        <Question
                                            :question="item.question"
                                            :variants="item.variants"
                                            :answer="item.answer"></Question>
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-outline-primary" @click="saveTest">
                                                Зберегти
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="type === 'complex'">
                                    <div v-for="item in questions" v-bind:key="item.title">
                                        <TestComplex :question="item.question"
                                                    :variants="item.variants"
                                                    :answer="item.answer"></TestComplex>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-outline-primary" @click="saveTest">
                                                Зберегти
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="picked === 'survey'">
                                <div v-for="item in questions" v-bind:key="item.title">
                                    <TestSurvey :question="item.question"
                                                :variants="item.variants"
                                                :answer="item.answer"></TestSurvey>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-outline-primary" @click="saveTestSurvey">
                                            Зберегти
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="getStep == 5">
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
    import {ARTICLE, USER, USER_CREATE_NAME, PROJECT, ARTICLE_COVER, PROJECT_IMAGE} from "../api/endpoints";
    import { ValidationProvider, ValidationObserver } from 'vee-validate'
    import PlusButton from './controls/PlusButton'
    import VContent from "./templates/Content"
    import axios from 'axios'
    import ProjectFormSidebar from "./templates/project/form/sidebar"
    import ProjectClose from "./templates/project/Close"
    import VCheckbox from "./templates/inputs/checkbox"
    import ArticleInputText from "./templates/article/form/text"
    import FragmentFormText from "./fragmets/text"
    import Multiselect from "vue-multiselect"
    import VButton from "./templates/inputs/button"
    import ArticleFormButton from "./templates/article/form/button"
    import ArticleFormInsert from "./templates/article/form/insert"
    import ArticleMultiple from "./templates/article/form/multiple"
    import Cover from "./fragmets/cover"
    import ArticleInputTitle from "./templates/article/form/title"
    import Question from './fragmets/TestQuestion.vue'
    import TestSurvey from './fragmets/TestSurvey.vue'
    import TestComplex from './fragmets/TestComplex.vue'
    import VRadio from "./templates/inputs/radio"

    export default {
        name: 'CreateProjectForm',
        components: {
            VContent,
            ProjectFormSidebar,
            ValidationProvider,
            ValidationObserver,
            PlusButton,
            ProjectClose,
            VCheckbox,
            ArticleInputText,
            FragmentFormText,
            Multiselect,
            VButton,
            ArticleFormButton,
            ArticleFormInsert,
            ArticleMultiple,
            ArticleInputCover: Cover,
            ArticleInputTitle,
            TestComplex,
            Question,
            TestSurvey,
            VRadio
        },
        data() {
            return {
                options: {
                    ...this.$store.state.project.options
                },
                ...this.$store.state.articles[0],
                articles: this.$store.state.articles,
                tests: this.$store.state.questions,
                questions: this.$store.state.questions,
                project: {},
                errorFile: '',
                errorTitle: '',
                errorArticleTitle: '',
                currentStep: 1,
                isComplex: false,
                content: {
                    ...this.$store.state.content
                },
                test: this.$store.state.tests,
                picked: 'test',
                type: 'easy',
                new_user: '',
                targeting: false,
                block_content: false
            }
        },
        computed: {
            /*getStep() {
                return this.$store.getters.currentStep
            },*/
            getStep() {
                return this.currentStep
            },
            getTitle() {
                console.log(this.$store.state.currentStep)
                return this.$store.state.currentStep
            },
            finishProject() {
                return this.$store.state.currentStep > 4
            },
            getPayload() {
                return {
                    options: this.options,
                    articles: this.articles,
                    tests: this.questions,
                    entity: this.entity,
                }
            }
        },
        props: {
            msg: String
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
            /*showStep(step) {
                this.currentStep++
                this.$store.dispatch('nextStep')
            },*/
            submitForm() {
                this.$refs.form.validate().then( success => {
                    if(!success) {
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
                            "count": this.content.count,
                            "points": this.content.points,
                            "frequency": this.content.frequency
                        },
                        "test": {
                            "count": this.test.count,
                            "points": this.test.points,
                            "canRetake": this.test.canRetake
                        }
                    }

                    console.log(content)

                    this.$post(PROJECT, {
                        options: this.options,
                        articles: this.articles,
                        tests: this.questions,
                        content: content
                    })
                        .then((res) => {
                            this.$router.push({ name: 'projectList' });
                        })
                        .catch((error) => {
                            console.log(error)
                        }).finally(() => {
                            console.log('success or error')
                        });

                    /*if ( this.isFinalStep ) {
                        this.$store.dispatch('createEntity', this.getPayload )
                    } else {
                        //this.currentStep++
                        this.$store.dispatch(
                            'storeProject', this.options
                        )
                        this.nextStep()
                    }*/
                })

            },
            storeProject() {
                this.$store.dispatch('storeProject', this.options )
                //this.$store.dispatch('storeProject', this.project.options)
            },
            handleUpload(fileName) {
                //if ( typeof this.files[fileName] !== 'undefined' ) {
                    if (event.target.files[0].size <= 1024 * 1024 * 1024) {
                        //this.files[fileName] = event.target.files[0].name;
                        this.options.files.cover = event.target.files[0]
                    } else {
                        //this.$set(this.errorFile, 'cover', 'Изображение слишком большое')
                        this.errorFile = 'Изображение слишком большое';
                    }
                //}
            },
            showTargeting() {
                //$('#block_targeting').show()
                this.targeting = true
            },
            validate() {
                this.$refs.form.validate().then( success => {
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
            showContent() {
                //this.validate()
                this.errorFile = '';
                this.errorTitle = '';

                if (this.options.files.cover == null) {
                    this.errorFile = 'Поле обязательно'
                }

                if (this.options.title === '') {
                    this.errorTitle = 'Требуется указать название'
                }

                if (this.options.title.length > 50) {
                    this.errorTitle = 'Требуется указать название не больше 50 символов'
                }

                if (this.errorTitle === '' && this.errorFile === '') {
                    //$('#block_content').show()
                    this.block_content = true;
                }
            },
            nextStep() {
                this.currentStep++
                this.$store.dispatch('nextStep')
            },
            setStep(step) {
                this.currentStep = step
                this.$store.dispatch('nextStep')
            },
            addContent() {
                this.$store.dispatch('addContent')
            },
            back() {
                this.$store.dispatch('projectList')
            },
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
            AddNewUser() {
                this.$get(USER_CREATE_NAME + '/' + this.new_user).then( response => {
                    this.authors = response.data
                })
            },
            buttonStore(value) {
                this.text_button = value
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
            submitComplex() {
                //this.isComplex = true;
                //this.addComplexQuestion()
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
                    variants: [
                        {
                            itemId: 'variant-' + Math.random().toString(36).substr(2, 9),
                            title: 'A',
                            variant: '',
                            isCorrect: false,
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
            saveTestSurvey() {
                $('#add_new_test_button').hide();
                $('#add_new_test').show();
                $('#add_new_test .articles_create__study-title').html($('#survey_question_title').val());

                this.currentStep = 2
                this.$store.dispatch('nextStep')
            },
            saveTest() {
                $('#add_new_test_button').hide();
                $('#add_new_test').show();
                $('#add_new_test .articles_create__study-title').html($('#question_title').val());

                this.currentStep = 2
                this.$store.dispatch('nextStep')
            },
            saveArticle() {
                $('#add_new_article_button').hide();
                $('#add_new_article').show();
                $('#add_new_article .articles_create__study-title').html($('#article_title').val());

                this.currentStep = 2
                this.$store.dispatch('nextStep')
            },
            reloadBlockSurveyTest() {
                for (const [index, value] of Object.entries(this.questions)) {
                    this.questions.splice(index, 1)
                    //return
                }

                this.addQuestion()

                $('#add_new_test_button').show();
                $('#add_new_test').hide();

                //this.currentStep = 4
                //this.$store.dispatch('nextStep')
            },
            reloadBlockTest() {
                for (const [index, value] of Object.entries(this.questions)) {
                    this.questions.splice(index, 1)
                    //return
                }

                this.addQuestion()

                $('#add_new_test_button').show();
                $('#add_new_test').hide();

                //this.currentStep = 4
                //this.$store.dispatch('nextStep')
            },
            reloadBlockArticle() {
                for (const [index, value] of Object.entries(this.articles)) {
                    this.articles.splice(index, 1)
                    //return
                }

                $('#add_new_article_button').show();
                $('#add_new_article').hide();
            },
            getType (value) {
                this.$emit('update', value);
            },
            /*saveArticleImage(event) { //console.log(event.target);
                let imageForm = new FormData();
                //imageForm.append('file', this.$refs.add_cover.files[0]);
                imageForm.append('file', event.target.files[0]);
                this.$post(ARTICLE_COVER + event.target.dataset.ref,
                    imageForm,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((file) => {
                    this.articles.files[event.target.dataset.ref] = file.data
                })
            }*/
        },
        mounted() {
            this.addQuestion()
            this.$get(ARTICLE + '?count=12&type=1').then( response => {
                this.recommended = response.data
            })
            this.$get(USER + '?count=12').then( response => {
                this.authors = response.data
            })
        }
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
  .input-file-label strong {
      font-size: 1.5em;
  }
  .smaller-text__article {
      font-size: 0.8rem;
  }
  #add_new_test {
      display: none;
  }
  #add_new_article {
      display: none;
  }
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .multiselect__tag, .multiselect__option--highlight {
        background: #05b7ff;
    }
</style>
