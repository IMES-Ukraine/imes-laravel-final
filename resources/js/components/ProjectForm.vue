<template>
    <v-content>

        <template v-slot:sidebar>
            <project-form-sidebar/>
        </template>

        <div class="article-edit card">

            <project-close/>

            <ValidationObserver ref="form" v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(submitForm)">
                    <div class="card-body">

                        <fragment-form-cover
                            v-bind:file.sync="options.files"
                            :file-key="'cover'"
                        />

                        <fragment-form-text>
                            <ValidationProvider rules="required" v-slot="{ errors }">
                                <input class="form-control" type="text" v-model="options.title" placeholder="Введите название проекта">
                                <span>{{ errors[0] }}</span>
                            </ValidationProvider>
                        </fragment-form-text>

                        <div class="row mb-4">
                            <div class="article-edit__text col-3">
                                Аудитория
                            </div>
                            <div class="col-3">
                                <PlusButton v-on:clickPlus="submitForm" label="Добавить"></PlusButton>
                            </div>

                            <div class="col-6">
                                <label class="btn btn-outline-second btn-centered-content is-small">
                                <span v-if="options.files.audience">
                                    <span class="input-file-name">{{ options.files.audience.file_name }}</span>
                                </span>
                                    <span v-else>
                                    <span class="input-file-label">
                                        <span class="icon-is-left icon-is-load-grey"></span>Персональная аудитория
                                    </span>
                                </span>
                                    <input type="file" data-ref="audience" class="input-file-hidden" @change="handleUpload">
                                </label>
                            </div>
                        </div>
                        <div v-if="getStep > 1">
                            <div class="row mb-4">
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
                        </div>

                        <div v-if="getStep > 2">

                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Контент
                                </div>
                                <div class="col-3">
                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                        <router-link :to="{path:'/project/content'}" @click.native="storeProject">
                                            <PlusButton label="Создать"></PlusButton>
                                            <input class="form-control" type="hidden" v-model="tests">
                                            <input class="form-control" type="hidden" v-model="articles">
                                        </router-link>
                                        <span>{{ errors[0] }}</span>
                                    </ValidationProvider>
                                </div>
                            </div>

                        </div>

                        <div v-if="getStep > 3 && this.hasContent">
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

                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-outline-primary" @click="submitForm">
                                    <template v-if="isFinalStep">Сохранить</template>
                                    <template v-else>Далее</template>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </ValidationObserver>
        </div>
    </v-content>
</template>
<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import PlusButton from './controls/PlusButton'
import VContent from "./templates/Content";
import ProjectFormSidebar from "./templates/project/form/sidebar";
import {PROJECT_IMAGE, ARTICLE, PROJECT} from "../api/endpoints";
import FragmentFormCover from "./fragmets/cover";
import FragmentFormText from "./fragmets/text";
import ProjectClose from "./templates/project/Close";

export default {
    name: 'CreateProjectForm',
    components: {
        //Form,
        ProjectClose,
        FragmentFormText,
        FragmentFormCover,
        ProjectFormSidebar,
        VContent,
        ValidationProvider,
        ValidationObserver,
        PlusButton
    },
    data() {
        return {
            options: {
                ...this.$store.state.project.options
            },
            articles: this.$store.state.articles,
            tests: this.$store.state.tests,
            id: null,
            entity: 'project',
            currentStep: 1,
        }
    },
    computed: {
        getStep() {
            return this.currentStep
        },
        isNew() {
            return !this.$route.params.projectId
        },
        isFinalStep() {
            return this.currentStep  > 3 && this.hasContent
        },
        hasContent() {
            return this.articles.length && this.tests.length;
        }
    },
    methods: {
        sendForm() {
            this.$get(ARTICLE).then(
                response => {
                    this.title = response.data
                }
            )
            let formData = new FormData();
            formData.append('coverImage', this.files.cover)
            formData.append('audienceDatabase', this.files.audience)
            this.$post(ARTICLE, formData).then(
                response => {
                    this.title = response.data.id
                }
            )
        },
        submitForm() {
            //let formData = new FormData();
            this.$refs.form.validate().then(success => {
                if( !success) {
                    return;
                }

                if ( this.isFinalStep ) {
                    if( this.isNew ) {
                        this.$store.dispatch('createEntity', {
                            options: this.options,
                            articles: this.articles,
                            tests: this.tests,
                            entity: this.entity
                        } )
                    } else {
                        this.$store.dispatch('updateEntity', this.$data )
                    }
                } else {
                    this.currentStep++
                }
            })
        },
        storeProject() {
            this.$store.dispatch('storeProject', this.options )
        },
        handleUpload(event) {
            /*if ( typeof this.files[fileName] !== 'undefined' )
                this.files[fileName] = event.target.files[0].name;*/
            let imageForm = new FormData();
            //imageForm.append('file', this.$refs.add_cover.files[0]);
            imageForm.append('file', event.target.files[0]);
            this.$post(PROJECT_IMAGE + event.target.dataset.ref,
                imageForm,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((file) => {
                this.options.files[event.target.dataset.ref] = file.data
            })
        },
        nextStep() {
            this.$store.dispatch('nextStep')
        },
        addContent() {
            this.$store.dispatch('addContent')
        },
        back() {
            this.$store.dispatch('projectList')
        }
    },
    mounted() {
        if (!this.isNew) {
            let projectId = this.$route.params.projectId;
            this.$get(PROJECT + '/' + projectId).then(response => {


                this.$store.dispatch('loadProject',
                    {
                        project: {
                            options: response.data.options,
                        },
                        content: response.data.content,
                        articles: response.data.articles,
                        tests: response.data.tests
                    })

                    this.options  = response.data.options
                    this.articles = response.data.articles
                    this.tests    = response.data.tests
            })
        }
    }
}
</script>
<style scoped>
.input-file-label strong {
    font-size: 1.5em;
}
.smaller-text__article {
    font-size: 0.8rem;
}
</style>
