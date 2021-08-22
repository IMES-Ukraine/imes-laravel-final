<template>
    <v-content>

        <template v-slot:sidebar>
            OK
        </template>

        <div class="card-body">
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <b>Название</b>
                </div>
                <div class="col-9">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <input class="form-control" type="text" name="title" v-model="question.title">
                        </div>
                    </div>
                </div>
            </div>


            <template v-if="question.isComplex"></template>
            <template v-else>
                <div class="row mb-3">
                    <div class="article-edit__text col-3">
                        Соглашение
                    </div>
                    <div class="col-9">
                        <textarea class="form-control" rows="4" v-model="question.agreement"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="article-edit__text col-3">
                        Описание
                    </div>
                    <div class="col-9">
                        <textarea class="form-control" rows="4" v-model="question.description"></textarea>
                    </div>
                </div>
            </template>


            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <template v-if="question.isComplex">Видео</template>
                    <template v-else>Обложка</template>
                </div>
                <div class="col-4">
                    <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                            <span class="input-file-label">
                                <span class="icon-is-left icon-is-load-grey"></span>Завантажити
                            </span>
                        <input type="file" name="testCover" ref="testCover" v-on:change="fileChange" v-bind:variant_name="coverRandomId" class="input-file-hidden">
                    </label>
                </div>
            </div>
            <div class="row mb-3">
                <div class="article-edit__text col-3">
                    Вопрос
                </div>
                <div class="col-9">
                    <textarea class="form-control" rows="4" v-model="question.text"></textarea>
                    <!--<div class="error" v-if="$v.text.$error">
                        Текст вопроса обязателен
                    </div>-->
                </div>
            </div>

            <SimpleTestVariants @file_сhange="fileChange" v-bind:answer="answer" v-bind:variants="variants"></SimpleTestVariants>

            <div class="row">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-outline-primary" @click="addAnswer">
                        Добавить ответ
                    </button>
                </div>
            </div>
            <br>

            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="checkbox" name="is-article-link" id="is-article-link">
                        <label for="is-article-link" class="custom-checkbox__label"></label>
                    </div>
                    Изучить
                </div>
                <div class="col-9">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="input-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text is-text"></span>
                                </div>
                                <input type="text" class="form-control input-is-form is-text" placeholder="" aria-label="теги" v-model="question.link" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </v-content>
</template>
<script>
import {required} from 'vuelidate/lib/validators'
//import SimpleTestVariant from './../components/inputs/SimpleTestVariant.vue';
import SimpleTestVariants from './../components/inputs/SimpleTestVariantsArray.vue';
import { getRandomId } from '../utils'
import VContent from "./templates/Content"
//import CreateProjectForm from "./CreateProjectForm";

//import { mapActions, mapState } from 'vuex'

export default {
    name: 'CreateTestForm',
    props: ['title', 'text', 'link', 'button', 'variants', 'answer', 'question'],
    components: {
        //SimpleTestVariant,
        SimpleTestVariants,
        VContent
    },

    data() {
        return {
            //tests: this.$store.state.tests,
            coverRandomId: getRandomId(),
            files: {},
        }
    },
    validations: {

            text: {
                required
            },
            link: {
                required
            },
            button: {
                required
            },
    },
    computed: {
        hasDescription: function() {
            return false;
        }
    },
    methods: {

        handleFileChange(evt){

            this.files[evt.target.name] = this.$refs[evt.target.name].files[0];
        },
        addAnswer() {
            const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ']
            let length = this.variants.length
            let obj = {
                itemId: getRandomId(),
                title: alphabet[length],
                variant: '',
                isCorrect: false,
            };
            this.variants.push(obj)
        },
        fileChange( e){
            let element = e.target

            this.question.media = element.getAttribute('variant_name')

            this.$store.dispatch('addFiles', {file: element.files[0], id: element.getAttribute('variant_name')})
            //console.log( el.getAttribute('variant_name'));
        }
    }
}
</script>
