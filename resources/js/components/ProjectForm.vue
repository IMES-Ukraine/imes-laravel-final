<template>
    <div class="main-articles">
        <div class="article-edit card">
            <router-link :to="{path:'/'}">
                <a class="article-edit__close" aria-label="закрити" title="закрити"></a>
            </router-link>
            <ValidationObserver ref="form">
              <div class="card-body">
                <div class="row mb-4">
                    <div class="article-edit__text col-3">
                        Обложка
                    </div>
                    <div class="col-4">
                        <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                            <span v-if="options.files.cover">
                                <span class="input-file-name">{{ options.files.cover }}</span>
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
                              <ValidationProvider rules="required" v-slot="{ errors }">
                                <input class="form-control" type="text" v-model="options.title" placeholder="Введите название проекта">
                                <span>{{ errors[0] }}</span>
                              </ValidationProvider>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <span class="input-file-name">{{ options.files.audience }}</span>
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
<!--                        <div class="col-3">
                            <label class="btn btn-outline-second btn-centered-content upload-cover is-small" v-if="article.title">
                                <span class="input-file-label">
                                    {{ article.title }}
                                </span>
                                <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="addContent">
                            </label>
                        </div>
                        <div class="col-3">
                            <label class="btn btn-outline-second btn-centered-content upload-cover is-small" v-if="test.title">
                                <span class="input-file-label">
                                    {{ test.title }}
                                </span>
                                <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="addContent">
                            </label>
                        </div>-->

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
            </ValidationObserver>
        </div>
    </div>                    <!-- main 2-1 -->
</template>
<script>
    import { ValidationProvider, ValidationObserver } from 'vee-validate'
    import PlusButton from './controls/PlusButton'

    //import { required } from 'vee-validate'

    export default {
        name: 'CreateProjectForm',
        components: {
          ValidationProvider,
          ValidationObserver,
          PlusButton
        },
        data() {
            return {
                options: {
                    title: '',
                    category : [
                        {name: 'ВСЕ', id: 1},
                        {name: 'Дерматология', id: 2},
                        {name: 'Кардиология', id: 3},
                        {name: 'Гастроэнтерология', id: 4},
                    ],
                    region : [
                        {name: 'ВСЯ УКРАИНА', id: 1},
                        {name: 'Одесская', id: 2},
                        {name: 'Киевская', id: 3},
                        {name: 'Черниговская', id: 4},
                        {name: 'Житомирская', id: 5},
                    ],
                    selected: {
                        category: 1,
                        region: 1
                    },
                    files: {
                        cover: null,
                        audience: null
                    },
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

                this.$get(`http://jsonplaceholder.typicode.com/posts`).then(
                //axios.get(`http://jsonplaceholder.typicode.com/posts`).then(
                        response => {
                            this.title = response.data
                        }
                    )

                    let formData = new FormData();
                    formData.append('coverImage', this.files.cover)
                    formData.append('audienceDatabase', this.files.audience)

                    this.$post(`http://jsonplaceholder.typicode.com/posts`, formData).then(
                    //axios.post(`http://jsonplaceholder.typicode.com/posts`, formData).then(
                        response => {
                            this.title = response.data.id
                        }
                    )
            },
            submitForm() {
                //let formData = new FormData();
                this.$refs.form.validate().then(() => {

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
        mounted() {

            if (!this.isNew) {
                let projectId = this.$route.params.projectId;
                this.$get(process.env.VUE_APP_API_URI + `/project/`+ projectId).then(response => {

                    this.$data.id = response.data.id
                    this.$data.options = response.data.options
                    this.$data.articles = response.data.articles
                    this.$data.tests = response.data.tests


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
