<template>
    <div class="main-articles">
        <!--<h2>{{ getTitle }}</h2>-->
        <div class="article-edit card">
            <a href="#" class="article-edit__close" aria-label="закрити" title="закрити" @click="back"></a>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="article-edit__text col-3">
                        Обложка
                    </div>
                    <div class="col-4">
                        <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                            <span v-if="files.cover">
                                <span class="input-file-name">{{ files.cover }}</span>
                            </span>
                            <span v-else>
                                <span class="input-file-label">
                                    <span class="icon-is-left icon-is-load-grey"></span>Загрузить
                                </span>
                            </span>
                            <input type="file" name="addCover" id="addCover" class="input-file-hidden" @change="handleUpload('cover')">
                        </label>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="article-edit__text col-3">
                        Название
                    </div>
                    <div class="col-9">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <input class="form-control" type="text" name="title" v-model="$v.title.$model">
                                <div class="error" v-if="$v.title.$error">
                                    Название обязательно
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
</svg>
&nbsp;Добавить
                            </span>
                            <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="submitForm">
                        </label>
                    </div>

                    <div class="col-6">
                        <label class="btn btn-outline-second btn-centered-content is-small">
                            <span v-if="files.audience">
                                <span class="input-file-name">{{ files.audience }}</span>
                            </span>
                            <span v-else>
                                <span class="input-file-label">
                                    <span class="icon-is-left icon-is-load-grey"></span>Персональная аудитория
                                </span>
                            </span>
                            <input type="file" name="addCover" id="addCover" class="input-file-hidden" @change="handleUpload('audience')">
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
                                <select class="form-control" v-model="selected.category">
                                    <option v-for="item in category" :value="item.id" :key="item.id">
                                    {{ item.name }}
                                    </option>
                                </select>
                            </label>
                        </div>

                        <div class="col-4">
                            <label class="btn btn-outline-second btn-centered-content is-small">
                                <select class="form-control" v-model="selected.region">
                                    <option v-for="item in region" :value="item.id" :key="item.id">
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
                            <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                                <span class="input-file-label">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M2.25922 6.13763L1.56299 6.13763C1.17847 6.13763 0.866761 6.44934 0.86676 6.83386C0.866759 7.21838 1.17847 7.53009 1.56299 7.53009L12.1059 7.53009C12.4905 7.53009 12.8022 7.21837 12.8022 6.83386C12.8022 6.44934 12.4905 6.13762 12.1059 6.13762L2.25922 6.13763Z" fill="#00B7FF"/>
    <path d="M5.96771 11.2393L5.96771 11.9355C5.96771 12.3201 6.27942 12.6318 6.66394 12.6318C7.04846 12.6318 7.36017 12.3201 7.36017 11.9355L7.36016 1.39259C7.36016 1.00808 7.04845 0.696363 6.66393 0.696363C6.27941 0.696363 5.9677 1.00808 5.9677 1.3926L5.96771 11.2393Z" fill="#00B7FF"/>
    </svg>
    &nbsp;Создать
                                </span>
                                <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="addContent">
                            </label>
                        </div>
                        <div class="col-3">
                            <label class="btn btn-outline-second btn-centered-content upload-cover is-small" v-if="article.title">
                                <span class="input-file-label">
                                    {{ article.title }}
                                </span>
                                <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="addContent">
                            </label>
                        </div>
                        <div class="col-3">
                            <label class="btn btn-outline-second btn-centered-content upload-cover is-small" v-if="test.question.title">
                                <span class="input-file-label">
                                    {{ test.question.title }}
                                </span>
                                <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="addContent">
                            </label>
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
        </div>
    </div>                    <!-- main 2-1 -->
</template>
<script>
    import {required/*,minLength*/} from 'vuelidate/lib/validators'
    import axios from 'axios'

    export default {
        name: 'CreateProjectForm',
        data() {
            return {
                ...this.$store.state.projectList[this.$store.state.current.project].options,
                article: this.$store.state.articles[0],
                test: this.$store.state.tests[0],
            }
        },
        computed: {
            getStep() {
                return this.$store.getters.currentStep
            },
            getTitle() {
                console.log(this.$store.state.currentStep)
                return this.$store.state.currentStep
            },
            finishProject() {
                return this.$store.state.currentStep > 3
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
            submitForm() {
                //let formData = new FormData();
                console.log( this.$v )
                this.$v.$touch()
                if (this.$v.$invalid) {
                    //
                } else {
                    if ( this.finishProject) {
                        this.$store.dispatch('saveProject', this.$data)
                    } else {
                        this.$store.dispatch('nextStep')
                    }

                }

            },
            handleUpload(fileName) {
                if ( typeof this.files[fileName] !== 'undefined' )
                    this.files[fileName] = event.target.files[0].name;
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
        validations: {
            title: {
                required
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
