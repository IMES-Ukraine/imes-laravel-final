<template>
    <div class="main-articles">
        <div class="article-edit card">
            <router-link :to="{name:'projectList'}">
              <a href="" class="article-edit__close" aria-label="закрити" title="закрити"></a>
            </router-link>
            <h4><center>Создание пакета контента</center></h4>
            <br>
            <ValidationObserver ref="form">
              <div class="card-body">
                <div class="row mb-4">
                    <div class="article-edit__text col-3">
                        Название
                    </div>
                    <div class="col-9">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <ValidationProvider rules="required" v-slot="{ errors }">
                                  <input class="form-control" type="text" name="title" v-model="title">
                                  <span>{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="article-edit__text col-3">
                        Статьи
                    </div>
                    <div class="col-3">
                        <label class="btn btn-outline-second btn-centered-content is-small">
                                <span class="input-file-label">
                                    <span v-if="title">
                                        <span class="input-file-name">{{ title }}</span>
                                    </span>
                                    <span v-else>
                                    <PlusButton label="Добавить"></PlusButton>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.25922 6.13763L1.56299 6.13763C1.17847 6.13763 0.866761 6.44934 0.86676 6.83386C0.866759 7.21838 1.17847 7.53009 1.56299 7.53009L12.1059 7.53009C12.4905 7.53009 12.8022 7.21837 12.8022 6.83386C12.8022 6.44934 12.4905 6.13762 12.1059 6.13762L2.25922 6.13763Z" fill="#00B7FF"/>
<path d="M5.96771 11.2393L5.96771 11.9355C5.96771 12.3201 6.27942 12.6318 6.66394 12.6318C7.04846 12.6318 7.36017 12.3201 7.36017 11.9355L7.36016 1.39259C7.36016 1.00808 7.04845 0.696363 6.66393 0.696363C6.27941 0.696363 5.9677 1.00808 5.9677 1.3926L5.96771 11.2393Z" fill="#00B7FF"/>
</svg>
&nbsp;Добавить
                                    </span>
                                </span>
                          <span v-if="this.isNew">
                            <router-link :to="{ name: 'article' }">
                              <input type="button" name="addCover" class="input-file-hidden">
                            </router-link>
                          </span>
                          <span v-else>
                            <router-link :to="{ name: 'articleById', params: { articleId: 1 } }">
                              <input type="button" name="addCover" class="input-file-hidden">
                            </router-link>
                          </span>
                        </label>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="title" placeholder="Количество">
                                    <div class="error" v-if="title">
                                        Обязательно
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="title" placeholder="Баллы">
                                    <div class="error" v-if="title">
                                        Обязательно
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="title" placeholder="Частота">
                                    <div class="error" v-if="title">
                                        Обязательно
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="article-edit__text col-3">
                        Тесты
                    </div>
                    <div class="col-3">
                        <label class="btn btn-outline-second btn-centered-content is-small">
                                <span class="input-file-label">
                                    <span v-if="title">
                                        <span class="input-file-name">{{ title }}</span>
                                    </span>
                                    <span v-else>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.25922 6.13763L1.56299 6.13763C1.17847 6.13763 0.866761 6.44934 0.86676 6.83386C0.866759 7.21838 1.17847 7.53009 1.56299 7.53009L12.1059 7.53009C12.4905 7.53009 12.8022 7.21837 12.8022 6.83386C12.8022 6.44934 12.4905 6.13762 12.1059 6.13762L2.25922 6.13763Z" fill="#00B7FF"/>
<path d="M5.96771 11.2393L5.96771 11.9355C5.96771 12.3201 6.27942 12.6318 6.66394 12.6318C7.04846 12.6318 7.36017 12.3201 7.36017 11.9355L7.36016 1.39259C7.36016 1.00808 7.04845 0.696363 6.66393 0.696363C6.27941 0.696363 5.9677 1.00808 5.9677 1.3926L5.96771 11.2393Z" fill="#00B7FF"/>
</svg>
&nbsp;Создать новый
                                </span></span>
                          <router-link :to="{ path:'article', params:{ testId: 1 } }">
                            <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="addTest">
                          </router-link>
                        </label>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="title" placeholder="Количество">
                                    <div class="error" v-if="title">
                                        Обязательно
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="title" placeholder="Баллы">
                                    <div class="error" v-if="title">
                                        Обязательно
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-2">
                            <div class="row">

                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-outline-primary" @click="submitForm">
                            Сохранить
                        </button>
                    </div>
                </div>
            </div>
            </ValidationObserver>
        </div>
    </div>
    <!-- main 2-1 -->
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'

export default {
    name: 'ContentForm',
    comments: {
      ValidationProvider,
      ValidationObserver
    },
    data() {
        return {
            title: null,
            contentTitle: this.$store.state.contentTitle,
            articleCount: null,//this.$store.state.articles[0].count,
            articlePoints: null,//this.$store.state.articles[0].points,
            testPoints: null,//this.$store.state.tests[0].question.points,
            articleFrequency: null,//this.$store.state.articles[0].frequency,
            testCount: null,
            article: this.$store.state.articles[0],
            test: this.$store.state.tests[0],
        }
    },
    computed: {
      isNew() {
        return !this.$route.params.contentId
      },
      contentId() {
        return this.$route.params.contentId
      }
    },
    methods: {

        submitForm() {

                this.$v.$touch()
                if (this.$v.$invalid) {

                    //

                } else
                    this.$store.dispatch('submitContent', this.$data)
        },
        addArticle() {
            this.$store.dispatch('addPoints', [this.articlePoints, this.testPoints])
            //this.$store.dispatch('addArticle')
        },
        addTest() {
            //this.testPoints = this.$store.state.tests[0]
            this.$store.dispatch('addPoints', [this.articlePoints, this.testPoints])
            this.$store.dispatch('addTest')
        },
        back(){
            this.$store.dispatch('projectForm')
        }
    },
    mounted() {
        if( !this.isNew ) {
            let contentId = this.contentId
            this.$get( process.env.VUE_APP_API_URI + '/project/' + contentId).then( r => {
                  this.title = r.data.options.title
                  this.contentTitle = r.data.options.title
            }).catch( e => {
              console.log(e)
            })
        }
    }
}
</script>