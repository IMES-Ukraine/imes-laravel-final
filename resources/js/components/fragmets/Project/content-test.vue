<template>
    <div>
        <div class="articles_create-block">
            <div class="articles_create__item">
                <p class="articles_create__item-title">Создание</p>
                <div class="articles_create__item-content">
                    <div class="articles_create__grid width-half column-gap-50">
                        <div class="articles_create__grid-block">
                            <div class="articles_create__sorting">
                                <input type="radio" name="radio" id="one" value="test"
                                       v-model="test.picked"
                                       checked="">
                                <p><span class="icon-plus">Теста</span></p>
                            </div>
                        </div>
                        <div class="articles_create__grid-block">
                            <div class="articles_create__sorting">
                                <input type="radio" name="radio" id="two" value="survey"
                                       v-model="test.picked">
                                <p><span class="icon-plus">Опроса</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="test.picked === 'test'">
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Формат теста</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__grid width-half column-gap-50">
                            <div class="articles_create__grid-block">
                                <div class="articles_create__sorting">
                                    <input type="radio" name="radio_type" id="easy" value="easy"
                                           v-model="test.type" checked="">
                                    <p><span class="icon-plus">Простой</span></p>
                                </div>
                            </div>
                            <div class="articles_create__grid-block">
                                <div class="articles_create__sorting">
                                    <input type="radio" name="radio_type" id="complex"
                                           value="complex" v-model="test.type">
                                    <p><span class="icon-plus">Сложный</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div v-if="test.type === 'easy'">
                <div>
                    <Question
                        :test.sync="test"
                        @input="storeTest($event)"
                        :toValidate="toValidate"
                        :errors.sync="stateErrors"/>
                </div>
                <div class="mb20"></div>

            </div>

            <div v-if="test.type === 'complex'">
                <div>
                    <TestComplex :test="test"
                                 :variants="test.variants"
                                 :errors="stateErrors"
                                 @input="storeTest($event)"
                                 :toValidate="toValidate"/>
                </div>

            </div>
        </div>

        <div v-if="test.picked === 'survey'">
            <div>
                <TestSurvey :test.sync="test"
                            @input="storeTest($event)"
                            :errors="stateErrors"/>
            </div>
            <div class="mb20"></div>
        </div>
    </div>
</template>

<script>
import Question from '../TestQuestion.vue';
import TestSurvey from '../TestSurvey.vue';
import TestComplex from '../TestComplex.vue'
import ProjectMixin from "../../../ProjectMixin";

export default {
    name: "content-test",
    mixins: [ProjectMixin],
    components: {Question, TestSurvey, TestComplex},
    data() {
        return {
            testErrors: {},
            toValidate: false
        }
    },
    computed: {
        test() {
            return this.$store.state.test;
        },
        stateErrors() {
            return this.$store.state.errors;
        }
    },
    methods: {

        storeTest(data) {
            if (data) {
                this.$store.commit('storeTest', data);
            }
            this.testErrors = {};
            let isLocalErrors = false;

            //триггер для запуска валидации в дочерних компонентах сложного теста
            this.$store.commit('setTestError', false);
            this.toValidate = !this.toValidate;

            if (!this.test.title || !this.test.title.trim()) {
                this.testErrors.testTitle = 'Назва обовʼязкова';
                isLocalErrors = true;
            }

            if (!this.test.text || !this.test.title.trim()) {
                this.testErrors.testText = 'Поле обовʼязкове';
                isLocalErrors = true;
            }

            if (this.test.picked === 'survey') {
                if (this.test.question.variants.length < 2) {
                    this.testErrors.variants = 'Должно быть не менее двух вариантов ответа';
                    isLocalErrors = true;
                    this.$store.commit('setTestError', true);
                }
                for (const [index, value] of Object.entries(this.test.question.variants)) {
                    if (value.title == '') {
                        $('#variant-' + value.variant).css('border', '1px solid red');
                        this.$store.commit('setTestError', true);
                        isLocalErrors = true;
                    }
                }
            } else if (this.test.type == 'easy') {
                if (this.test.question.type === 'variants') {
                    this.testErrors.title = [];
                    for (const [index, value] of Object.entries(this.test.question.variants)) {
                        if (!value.title || !value.title.trim()) {
                            this.testErrors.title[index] = 'Текст ответа обязателен';
                            isLocalErrors = true;
                        }
                    }
                }
                if (this.test.question.type === 'media') {
                    this.testErrors.media = [];
                    isLocalErrors = false;
                    for (const [index, value] of Object.entries(this.test.question.variants)) {
                        if (!value.media.length) {
                            this.testErrors.media[index] = 'Изображение для  ответа обязательно';
                            isLocalErrors = true;
                        }
                    }
                }

                if ((this.test.question.type !== 'text') && !this.test.question.correct.length) {
                    this.testErrors.correct = this.notCorrectAnswer;
                    isLocalErrors = true;
                }
            } else {
                if (!this.test.complex_question.length) {
                    this.testErrors.complex = 'Має бути вказаний принаймні один блок з питаннями';
                    isLocalErrors = true;
                }
            }
            this.$store.commit('setErrors', this.testErrors);

            if (!isLocalErrors && !this.$store.state.testErrors) {
                this.$store.commit('saveTest', this.test);
                this.setStep(2);
            }
        },

    },

}
</script>

<style scoped>

</style>
