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
                                    <!--<ValidationProvider
                                        ref="validator"
                                        name="image"
                                        rules="required"
                                        v-slot="{ errors }"
                                    >
                                        <p>
                                            <input type="file" accept="image/*" @change="selected">
                                        </p>

                                        <span v-if="errors">{{ errors[0] }}</span>
                                    </ValidationProvider>-->
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
                                        <ValidationProvider name="title" rules="required|min:3|max:6">
                                            <div slot-scope="{ errors }">
                                                <input v-model="title" type="text" name="title" class="form-control">
                                                <div class="errors">{{ errors[0] }}</div>
                                            </div>
                                        </ValidationProvider>
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
                                    <input type="button" name="addCover" class="input-file-hidden" @click="nextStep">
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
                        <div v-if="getStep > 1">
                            <hr>
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
                                            <PlusButton label="Створити"></PlusButton>
                                            <input class="form-control" type="hidden" v-model="questions">
                                            <input class="form-control" type="hidden" v-model="articles">
                                        </router-link>
                                        <span>{{ errors[0] }}</span>
                                    </ValidationProvider>
                                </div>

                            </div>

                        </div>

                        <div v-if="getStep > 3">
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
                                    <template v-if="finishProject">Сохранить</template>
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
    //import {required/*,minLength*/} from 'vuelidate/lib/validators'
    import { ValidationProvider, ValidationObserver } from 'vee-validate'
    import PlusButton from './controls/PlusButton'
    import VContent from "./templates/Content"
    import axios from 'axios'
    import ProjectFormSidebar from "./templates/project/form/sidebar";
    //import { ValidationProvider } from "vee-validate"

    export default {
        name: 'CreateProjectForm',
        components: {
            VContent,
            ProjectFormSidebar,
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
                tests: this.$store.state.questions,
                questions: this.$store.state.questions,
                project: {},
                errorFile: '',
                currentStep: 1,
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
                return this.$store.state.currentStep > 3
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
                //let formData = new FormData();
                /*console.log( this.$v )
                this.$v.$touch()
                if (this.$v.$invalid) {
                    //
                } else {
                    if ( this.finishProject) {
                        this.$store.dispatch('saveProject', this.$data)
                    } else {
                        this.$store.dispatch('nextStep')
                    }

                }*/
                this.$refs.form.validate().then( success => {
                    if(!success) {
                        if (this.options.files.cover == null) {
                            this.errorFile = 'Поле обязательно';
                        } else {
                            this.errorFile = '';
                        }

                        return;
                    }

                    if ( this.isFinalStep ) {
                        this.$store.dispatch('createEntity', this.getPayload )
                    } else {
                        //this.currentStep++
                        this.$store.dispatch(
                            'storeProject', this.project.options
                        )
                        this.nextStep()
                    }
                })

            },
            storeProject() {
                //this.$store.dispatch('storeProject', this.options )
                this.$store.dispatch('storeProject', this.project.options)
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
            nextStep() {
                this.currentStep++
                this.$store.dispatch('nextStep')
            },
            addContent() {
                this.$store.dispatch('addContent')
            },
            back() {
                this.$store.dispatch('projectList')
            },
        },
        /*validations: {
            title: {
                required
            }
        }*/
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
