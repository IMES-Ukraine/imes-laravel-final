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
                        <!--                        <div class="articles_create__item half">-->
                        <!--                            <p class="articles_create__item-title">Обложка</p>-->
                        <!--                            <file-input :key="question.itemId + '-cover'"-->
                        <!--                                        :value="question.cover"-->
                        <!--                                        @fileInput="question.cover = $event"-->
                        <!--                                        type="cover"/>-->

                        <!--                        </div>-->
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
                                <input type="radio" name="attach" value="image" v-model="question.fileType"
                                       @click="question.file = null">
                                <i></i>
                                <p>Изображения</p>
                            </div>
                            <div class="articles_create__item-content" style="margin-top: 35px;">
                                <file-input :key="JSON.stringify(question.file)"
                                            :value="question.file"
                                            :type="question.fileType"
                                            @fileInput="question.file = $event"
                                            :disabled="!question.fileType"/>
                            </div>
                        </div>
                        <div class="articles_create__item half"></div>
                        <div class="articles_create__item half" style="margin-top: -35px;">
                            <div class="articles_create__item-title has_radio">
                                <input type="radio" name="attach" value="video" v-model="question.fileType"
                                       @click="question.file = null">
                                <i></i>
                                <p>Видео</p>
                            </div>
                        </div>
                    </div>
                    <ComplexTestVariants :toValidate="toValidate"
                                         @input="getRadioType($event, index)"
                                         :errors="errorsParent" :i="index" :question.sync="question"/>

                    <button class="articles_create-submit button-border mtb20" type="button"
                            v-if="question.isCheckedVariant"
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
import FileInput from "./file-input";

export default {
    xname: 'ComplexTestQuestionArray',
    mixins: [ProjectMixin],
    components: {
        ComplexTestVariants,
        FileInput
    },
    props: ['complex_question', 'errorsParent', 'toValidate'],
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
    computed: {
        test() {
            return this.$store.state.test;
        }
    },
    watch: {
        toValidate() {
            this.varKey = Math.random();
            this.errors = {
                title: [],
                text: []
            }
            for (let indexQuestion in this.complex_question) {
                if (!this.complex_question[indexQuestion].title || !this.complex_question[indexQuestion].title.trim()) {
                    this.errors.title[indexQuestion] = 'Назва обовʼязкова';
                }

                if (!this.complex_question[indexQuestion].text || !this.complex_question[indexQuestion].text.trim()) {
                    this.errors.text[indexQuestion] = 'Питання обовʼязкове'
                }
                if (Object.keys(this.errors.title).length || Object.keys(this.errors.text).length) {
                    this.$store.commit('setTestError', true);
                }
            }

        }
    },
    methods: {

        removeComplexTest(itemId) {
            for (const [index, value] of Object.entries(this.complex_question)) {
                if (value.itemId === itemId) {
                    this.complex_question.splice(index, 1)
                    return
                }
            }
        },
        getRadioType(value, index) {
            this.complex_question[index].isCheckedVariant = true;

            if (value == 'text') {
                this.complex_question[index].isCheckedVariant = false;
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
