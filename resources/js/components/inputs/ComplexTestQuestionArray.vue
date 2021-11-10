<template>
    <div>
        <div class="articles" v-for="(question, index) in complex_question" :key="question.itemId"
             :id="'block-'+question.itemId">
            <div class="articles_create padding-style-2">
                <p class="articles_create-title">Создание блока {{ index + 1 }}</p>
                <button class="articles_create-close" type="button"
                        @click="removeComplexTest(question.itemId)"></button>
                <div class="articles_create-block">
                    <div class="articles_create-block">
                        <div class="articles_create__item">
                            <p class="articles_create__item-title">Название</p>
                            <div class="articles_create__item-content">
                                <div class="articles_create__name-block">
                                    <input type="text" name="title" id="question_title" v-model="question.title">
                                    <div v-if="errors.title[index]" class="errors">{{ errors.title[index] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="articles_create__item half">
                            <p class="articles_create__item-title">Обложка</p>
                            <div class="articles_create__item-content">
                                <div class="articles_create__item-file width-auto buttonAddFile">
                                    <input type="file" name="name" v-on:change="handleUpload(index, $event)">
                                    <p><span data-placeholder="Загрузить"></span></p>
                                    <button class="delete_file deleteFile"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="articles_create-block">
                        <div class="articles_create__item">
                            <p class="articles_create__item-title">Вопрос</p>
                            <div class="articles_create__item-content">
                                <textarea v-model="question.text"></textarea>
                                <div v-if="errors.text[index]" class="errors">{{ errors.text[index] }}</div>
                            </div>
                        </div>
                        <div class="articles_create__item half">
                            <div class="articles_create__item-title has_radio">
                                <input type="checkbox" name="checkbox_file" @change="checkboxChange">
                                <i></i>
                                <p>Изображения</p>
                            </div>
                            <div class="articles_create__item-content">
                                <div class="articles_create__item-file width-auto buttonAddFile"
                                     :class="(!isCheckedFile)?'fileDisabled':''">
                                    <input type="file" name="name" :disabled="!isCheckedFile">
                                    <p><span data-placeholder="Загрузить"></span></p>
                                    <button class="delete_file deleteFile" type="button"></button>
                                </div>
                            </div>
                        </div>
                        <div class="articles_create__item half"></div>
                        <div class="articles_create__item half">
                            <div class="articles_create__item-title has_radio">
                                <input type="checkbox" name="checkbox_video" @change="checkboxChangeVideo">
                                <i></i>
                                <p>Видео</p>
                            </div>
                            <div class="articles_create__item-content">
                                <div class="articles_create__item-file width-auto buttonAddFile"
                                     :class="(!isCheckedVideo)?'fileDisabled':''">
                                    <input type="file" name="name" :disabled="!isCheckedVideo">
                                    <p><span data-placeholder="Загрузить"></span></p>
                                    <button class="delete_file deleteFile" type="button"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ComplexTestVariants :toValidate="toValidate" :question.sync="question"/>

                    <button class="articles_create-submit button-border mtb20" type="button"
                            @click="addAnswerTest(question.variants.length, index + 1)">добавить ответ
                    </button>
                    <div class="articles_create-line"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {required} from 'vuelidate/lib/validators'
import {PROJECT_IMAGE} from "../../api/endpoints"
import ComplexTestVariants from './../inputs/ComplexTestVariantsArray.vue'
import ProjectMixin from "../../ProjectMixin";

export default {
    xname: 'ComplexTestQuestionArray',
    mixins: [ProjectMixin],
    components: {
        ComplexTestVariants
    },
    props: ['complex_question', 'toValidate'],
    data() {
        return {
            isCheckedFile: false,
            isCheckedVideo: false,
            varKey: Math.random(),
            errors: {
                title: [],
                text: []
            }
        }
    },
    watch: {
        toValidate() {
            this.varKey = Math.random();
            for (let indexQuestion in this.complex_question) {
                if (this.complex_question[indexQuestion].title == '') {
                    this.errors.title[indexQuestion] = 'Назва обовʼязкова';
                }

                if (this.complex_question[indexQuestion].text == '') {
                    this.errors.text[indexQuestion] = 'Питання обовʼязкове'
                }

                if (Object.keys(this.errors).length) {
                    this.$store.commit('setTestError', true);
                }
            }

        }
    },
    methods: {

        /**
         * Handle changing of file input (cover, video, variants)
         * @param event
         */
        handleUpload(index, event) {

            let imageForm = new FormData();
            let input = event.target
            let type = input.getAttribute('img_type')


            imageForm.append('file', input.files[0]);

            this.$post(PROJECT_IMAGE + type,
                imageForm,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((file) => {
                this.complex_question.cover = file.data
            })
        },

        removeComplexTest(itemId) {
            for (const [index, value] of Object.entries(this.complex_question)) {
                if (value.itemId === itemId) {
                    this.complex_question.splice(index, 1)
                    return
                }
            }
        },
        checkboxChange() {
            if (this.isCheckedFile) {
                this.isCheckedFile = false
            } else {
                this.isCheckedFile = true
            }
        },
        checkboxChangeVideo() {
            if (this.isCheckedVideo) {
                this.isCheckedVideo = false
            } else {
                this.isCheckedVideo = true
            }
        }
    },
    validations: {
        text: {
            required
        }
    },
}
</script>

<style>
.custom-checkbox__input {
    margin-right: 10px;
}
</style>
