<template>
    <div class="main-articles">
        <div class="article-edit card">
            <a href="#" @click="back" class="article-edit__close" aria-label="закрити" title="закрити"></a>
            <h4><center>Создание пакета контента</center></h4>
            <br>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="article-edit__text col-3">
                        Название
                    </div>
                    <div class="col-9">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <input class="form-control" type="text" name="title" v-model="$v.contentTitle.$model">
                                <div class="error" v-if="$v.contentTitle.$error">
                                    Название обязательно
                                </div>
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
                                    <span v-if="article.title">
                                        <span class="input-file-name">{{ article.title }}</span>
                                    </span>
                                    <span v-else>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.25922 6.13763L1.56299 6.13763C1.17847 6.13763 0.866761 6.44934 0.86676 6.83386C0.866759 7.21838 1.17847 7.53009 1.56299 7.53009L12.1059 7.53009C12.4905 7.53009 12.8022 7.21837 12.8022 6.83386C12.8022 6.44934 12.4905 6.13762 12.1059 6.13762L2.25922 6.13763Z" fill="#00B7FF"/>
<path d="M5.96771 11.2393L5.96771 11.9355C5.96771 12.3201 6.27942 12.6318 6.66394 12.6318C7.04846 12.6318 7.36017 12.3201 7.36017 11.9355L7.36016 1.39259C7.36016 1.00808 7.04845 0.696363 6.66393 0.696363C6.27941 0.696363 5.9677 1.00808 5.9677 1.3926L5.96771 11.2393Z" fill="#00B7FF"/>
</svg>
&nbsp;Создать новую
                                    </span>
                                </span>
                            <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="addArticle">
                        </label>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="$v.articleCount.$model" placeholder="Количество">
                                    <div class="error" v-if="$v.articleCount.$error">
                                        Обязательно
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="$v.articlePoints.$model" placeholder="Баллы">
                                    <div class="error" v-if="$v.articlePoints.$error">
                                        Обязательно
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="$v.articleFrequency.$model" placeholder="Частота">
                                    <div class="error" v-if="$v.articleFrequency.$error">
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
                                    <span v-if="test.question.title">
                                        <span class="input-file-name">{{ test.question.title }}</span>
                                    </span>
                                    <span v-else>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.25922 6.13763L1.56299 6.13763C1.17847 6.13763 0.866761 6.44934 0.86676 6.83386C0.866759 7.21838 1.17847 7.53009 1.56299 7.53009L12.1059 7.53009C12.4905 7.53009 12.8022 7.21837 12.8022 6.83386C12.8022 6.44934 12.4905 6.13762 12.1059 6.13762L2.25922 6.13763Z" fill="#00B7FF"/>
<path d="M5.96771 11.2393L5.96771 11.9355C5.96771 12.3201 6.27942 12.6318 6.66394 12.6318C7.04846 12.6318 7.36017 12.3201 7.36017 11.9355L7.36016 1.39259C7.36016 1.00808 7.04845 0.696363 6.66393 0.696363C6.27941 0.696363 5.9677 1.00808 5.9677 1.3926L5.96771 11.2393Z" fill="#00B7FF"/>
</svg>
&nbsp;Создать новый
                                </span></span>
                            <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click="addTest">
                        </label>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="$v.testCount.$model" placeholder="Количество">
                                    <div class="error" v-if="$v.testCount.$error">
                                        Обязательно
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-2">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input class="form-control" type="text" name="title" v-model="$v.testPoints.$model" placeholder="Баллы">
                                    <div class="error" v-if="$v.testPoints.$error">
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
        </div>
    </div>
    <!-- main 2-1 -->
</template>

<script>
import {required/*,minLength*/} from 'vuelidate/lib/validators'
//import { mapActions } from 'vuex'

export default {
    name: 'CreateContentForm',
    
    data() {
        return {

            contentTitle: this.$store.state.contentTitle,
            articleCount: this.$store.state.articles[0].count,
            articlePoints: this.$store.state.articles[0].points,
            testPoints: this.$store.state.tests[0].question.points,
            articleFrequency: this.$store.state.articles[0].frequency,
            testCount: null,
            article: this.$store.state.articles[0],
            test: this.$store.state.tests[0],
        }
    },
    validations: {
            contentTitle: {
                required
            },
            articleCount: {
                required
            },
            articlePoints: {
                required
            },
            articleFrequency: {
                required
            },
            testPoints: {
                required
            },
            testCount: {
                required
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
            this.$store.dispatch('addArticle')
        },
        addTest() {
            //this.testPoints = this.$store.state.tests[0]
            this.$store.dispatch('addPoints', [this.articlePoints, this.testPoints])
            this.$store.dispatch('addTest')
        },
        back(){
            this.$store.dispatch('projectForm')
        }
    }
}
</script>