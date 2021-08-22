<template>
    <v-content>
        <template v-slot:sidebar>
        </template>
        <div class="main-articles">
            <div class="article-edit card">
                <project-close/>
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
                                <router-link :to="{name:'createArticle'}" @click.native="storeContent">
                                    <PlusButton label="Добавить"></PlusButton>
                                </router-link>
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
                                <PlusButton label="Создать новый"></PlusButton>
                                <router-link :to="{ name:'test' }">
                                    <input type="button" name="addCover" id="addCover" class="input-file-hidden" @click.native="storeContent">
                                </router-link>
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
    </v-content>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import VContent from "./templates/Content"
import PlusButton from './controls/PlusButton'
import ProjectClose from "./templates/project/Close";
export default {
    name: 'ContentForm',
    components: {
        ProjectClose,
        ValidationProvider,
        ValidationObserver,
        VContent,
        PlusButton
    },
    data() {
        return {
            ...this.$store.state.content,
            contentTitle: this.$store.state.contentTitle,
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
        storeContent() {
            this.$store.dispatch('storeContent', this.$data)
        },
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
