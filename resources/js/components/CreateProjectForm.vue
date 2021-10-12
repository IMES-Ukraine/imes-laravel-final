<template>

    <v-content>

        <template v-slot:sidebar>
            <project-form-sidebar
            :tag="project.tag"/>
        </template>

        <div class="article-edit card">
            <ValidationObserver ref="form" v-slot="{ handleSubmit }">
                <form>
                    <div class="card-body">
                        <div v-show="getStep == 1">
                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Обложка
                                </div>
                                <div class="col-4">
                                    <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                                        <!--<span v-if="files.cover">
                                            <span class="input-file-name">{{ files.cover }}</span>
                                        </span>
                                        <span v-else>-->
                                            <span class="input-file-label">
                                                <span class="icon-is-left icon-is-load-grey"></span>Загрузить
                                            </span>
                                        <!--</span>-->
                                        <input type="file" name="сover" class="input-file-hidden" @change="handleUpload('cover')"/>
                                    </label>
                                    <div v-if="errorFile" class="errors">{{ errorFile }}</div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Название
                                </div>
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-8 mb-2">
                                            <!--<input class="form-control" type="text" name="title">-->
                                            <!--<div class="error" v-if="$v.title.$error">
                                                Название обязательно v-model="$v.title.$model"
                                            </div>-->
                                            <!--<ValidationProvider name="title" rules="required|min:3|max:6">
                                                <div slot-scope="{ errors }">
                                                    <input v-model="title" type="text" name="title" class="form-control">
                                                    <div class="errors">{{ errors[0] }}</div>
                                                </div>
                                            </ValidationProvider>-->
                                            <validation-provider
                                                rules="required"
                                                v-slot="{ errors }">

                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    name="title"
                                                    v-model="options.title"
                                                    placeholder="Название">

                                                <span class="errors">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <input class="form-control" type="text" name="tag" v-model="project.tag" placeholder="#">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Аудитория
                                </div>
                                <div class="col-3">
                                    <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                                        <span class="input-file-label">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.25922 6.13763L1.56299 6.13763C1.17847 6.13763 0.866761 6.44934 0.86676 6.83386C0.866759 7.21838 1.17847 7.53009 1.56299 7.53009L12.1059 7.53009C12.4905 7.53009 12.8022 7.21837 12.8022 6.83386C12.8022 6.44934 12.4905 6.13762 12.1059 6.13762L2.25922 6.13763Z" fill="#00B7FF"/>
                                            <path d="M5.96771 11.2393L5.96771 11.9355C5.96771 12.3201 6.27942 12.6318 6.66394 12.6318C7.04846 12.6318 7.36017 12.3201 7.36017 11.9355L7.36016 1.39259C7.36016 1.00808 7.04845 0.696363 6.66393 0.696363C6.27941 0.696363 5.9677 1.00808 5.9677 1.3926L5.96771 11.2393Z" fill="#00B7FF"/>
                                            </svg>Добавить</span>
                                        <input type="button" name="addCover" class="input-file-hidden" @click="showTargeting">
                                    </label>
                                </div>

                                <div class="col-6">
                                    <label class="btn btn-outline-second btn-centered-content is-small">
                                        <!--<span v-if="files.audience">
                                            <span class="input-file-name">{{ files.audience }}</span>
                                        </span>
                                        <span v-else>-->
                                            <span class="input-file-label">
                                                <span class="icon-is-left icon-is-load-grey"></span>Загрузить персонализацию
                                            </span>
                                        <!--</span>-->
                                        <input type="file" name="audience" class="input-file-hidden" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" @change="handleUpload('audience')">
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-4" style="display: none;" id="block_targeting">
                                <div class="article-edit__text col-3">
                                    Таргетинг
                                </div>
                                <div class="col-4">
                                    <label class="btn btn-outline-second btn-centered-content is-small">
                                        <select class="form-control" v-model="options.selected.category">
                                            <option v-for="item in options.category" :value="item.id" :key="item.id">
                                                {{ item.name }}
                                            </option>
                                        </select>
                                    </label>
                                </div>

                                <div class="col-4">
                                    <label class="btn btn-outline-second btn-centered-content is-small">
                                        <select class="form-control" v-model="options.selected.region">
                                            <option v-for="item in options.region" :value="item.id" :key="item.id">
                                                {{ item.name }}
                                            </option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="row mb-4" style="display: none;" id="block_content">
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

                            </div>

                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-outline-primary" @click="showContent">
                                    <template v-if="finishProject">Сохранить</template>
                                    <template v-else>Далее</template>
                                </button>
                            </div>

                            <!--<div class="row">
                                <div class="col-12 text-center">
                                    <button type="button" class="btn btn-outline-primary" @click="submitForm">
                                        <template v-if="finishProject">Сохранить</template>
                                        <template v-else>Далее</template>
                                    </button>
                                </div>
                            </div>-->
                        </div>

                        <div v-show="getStep == 2">
                            <project-close/>
                            <h4>
                                <center>Створення пакета контента</center>
                            </h4>
                            <br>

                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Выбор шаблона
                                </div>
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <ValidationProvider>
                                                <router-link :to="{path:'/project/content'}" @click.native="storeProject">
                                                    <PlusButton label="Партнерский пакет №1"></PlusButton>
                                                </router-link>
                                            </ValidationProvider>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <ValidationProvider>
                                                <router-link :to="{path:'/project/content'}" @click.native="storeProject">
                                                    <PlusButton label="Уникальный пакет"></PlusButton>
                                                </router-link>
                                            </ValidationProvider>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Називання
                                </div>
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <validation-provider
                                                rules="required"
                                                v-slot="{ errors }">

                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    v-model="content.title">

                                                <span class="errors">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Статтi
                                </div>
                                <div class="col-3">
                                    <div @click.prevent="setStep(3)"><PlusButton label="Додати"></PlusButton></div>
                                    <!--<router-link
                                        :to="{name:'createArticle'}"
                                        @click.native="storeContent">

                                        <PlusButton label="Додати"></PlusButton>
                                    </router-link>-->
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <validation-provider rules="required" v-slot="{ errors }">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="name"
                                                    v-model="content.count"
                                                    placeholder="Кiлькiсть">

                                                <span class="errors">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <validation-provider
                                                rules="required"
                                                v-slot="{ errors }">

                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="name"
                                                    v-model="content.points"
                                                    placeholder="Бали">

                                                <span class="errors">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <validation-provider
                                                rules="required"
                                                v-slot="{ errors }">

                                                <input
                                                    class="form-control"
                                                    type="number"
                                                    name="name"
                                                    v-model="content.frequency"
                                                    placeholder="Частота">

                                                <span class="errors">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Тести
                                </div>
                                <div class="col-3">
                                    <div @click.prevent="setStep(4)" id="add_new_test_button"><PlusButton label="Створити новий"></PlusButton></div>
                                    <div id="add_new_test">
                                        <span></span>
                                        <div @click.prevent="setStep(4)"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                        <div @click.prevent="reloadBlockSurveyTest"><i class="fa fa-trash" aria-hidden="true"></i></div>
                                    </div>
                                    <!--<PlusButton label="Створити новий"></PlusButton>
                                    <router-link :to="{ name:'test' }">
                                        <input type="button" name="addCover" id="addCover" class="input-file-hidden"
                                               @click.native="storeContent">
                                    </router-link>-->
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <validation-provider
                                                rules="required"
                                                v-slot="{ errors }">

                                                <input
                                                    class="form-control"
                                                    type="number"
                                                    name="name"
                                                    v-model="test.count"
                                                    placeholder="Кiлькiсть">
                                                <span class="errors">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <validation-provider
                                                rules="required"
                                                v-slot="{ errors }">

                                                <input
                                                    class="form-control"
                                                    type="number"
                                                    name="name"
                                                    v-model="test.points"
                                                    placeholder="Бали">

                                                <span class="errors">{{ errors[0] }}</span>
                                            </validation-provider>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <v-checkbox
                                            :id="'can-retake-button'"
                                            :name="'can-retake-button'"
                                        />
                                        Повторне проходження
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="button" class="btn btn-outline-primary" @click="submitForm">
                                        Зберегти
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-show="getStep == 3">
                            <project-close/>
                            <h4>
                                <center>Створення статтi</center>
                            </h4>
                            <br>
                            <!-- article-title -->
                            <article-input-title
                                v-bind:type.sync="type"
                                :articleType="articleType"
                                @update="articleTypeStore"
                            />
                            <!-- end-article-title -->

                            <!-- article-cover -->
                            <article-input-cover
                                v-bind:file.sync="images"
                                :file-key="'cover'"
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
                                :v="$v"
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
                            <v-button @click="submitForm"/>
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

                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-outline-primary" @click="submitComplex">
                                                <div>Добавить блок</div>
                                            </button>
                                        </div>
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
                    </div>
                </form>
            </ValidationObserver>
        </div>
    </v-content>
</template>
<script>
    //import {required/*,minLength*/} from 'vuelidate/lib/validators'
    import {ARTICLE, USER, USER_CREATE_NAME, PROJECT} from "../api/endpoints";
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
                articles: this.$store.state.articles,
                tests: this.$store.state.questions,
                questions: this.$store.state.questions,
                project: {},
                errorFile: '',
                currentStep: 1,
                isComplex: false,
                content: {
                    ...this.$store.state.content
                },
                test: this.$store.state.tests,
                picked: 'test',
                type: 'easy'
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
            msg: String,
            /*errorFile: {
                type: Object
            },*/
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

                    console.log(this.options)
                    let formData = new FormData();
                    //formData.append('coverImage', this.files.cover)
                    //formData.append('audienceDatabase', this.files.audience)
                    formData.append('options', this.options)
                    formData.append('articles', this.articles)
                    formData.append('tests', this.tests)
                    formData.append('questions', this.questions)
                    formData.append('project', this.project)
                    formData.append('content', this.content)

                    this.$post(PROJECT, formData)
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
                    if (event.target.files[0].size <= 1024 * 1024) {
                        //this.files[fileName] = event.target.files[0].name;
                        this.options.files.cover = event.target.files[0]
                    } else {
                        //this.$set(this.errorFile, 'cover', 'Изображение слишком большое')
                        this.errorFile = 'Изображение слишком большое';
                    }
                //}
            },
            showTargeting() {
                $('#block_targeting').show()
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
                this.validate()
                $('#block_content').show()
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
                this.isComplex = true;
                this.addComplexQuestion()
            },
            addComplexQuestion() {
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
                $('#add_new_test span').html($('#survey_question_title').val());

                this.currentStep = 2
                this.$store.dispatch('nextStep')
            },
            saveTest() {
                $('#add_new_test_button').hide();
                $('#add_new_test').show();
                $('#add_new_test span').html($('#question_title').val());

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
            }
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
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .multiselect__tag, .multiselect__option--highlight {
        background: #05b7ff;
    }
</style>
