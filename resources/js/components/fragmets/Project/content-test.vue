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
                        :errors="testErrors"/>
                </div>
                <div class="mb20"></div>
                <button class="articles_create-submit button-gradient" type="button"
                        @click="storeTest">сохранить
                </button>
            </div>

            <div v-if="test.type === 'complex'">
                <div>
                    <TestComplex :test.sync="test"
                                 :variants="test.variants"
                                 :errors="testErrors"
                                 :toValidate="toValidate"/>
                </div>
                <div class="mb20"></div>
                <button class="articles_create-submit button-gradient" type="button"
                        @click="storeTest">сохранить
                </button>
            </div>
        </div>

        <div v-if="test.picked === 'survey'">
            <div>
                <TestSurvey :question="test.question"
                            :variants="test.variants"
                            :errorTestSurveyTitle="errorTestSurveyTitle"
                            :errorTestSurveyText="errorTestSurveyText"/>
            </div>
            <div class="mb20"></div>
            <button class="articles_create-submit button-gradient" type="button"
                    @click="saveTestSurvey">сохранить
            </button>
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
            return this.$store.state.content.test;
        }
    },
    methods: {
        saveTestSurvey() {
            this.errorTestSurveyTitle = ''
            this.errorTestSurveyText = ''
            let error = false

            $('#survey-test input').css('border', '1px solid #D9D9D9')

            if (this.test.question.title == '') {
                this.errorTestSurveyTitle = 'Название обязательно'
                error = true
            }

            if (this.test.question.text == '') {
                this.errorTestSurveyText = 'Вопрос обязателен'
                error = true
            }

            for (const [index, value] of Object.entries(this.test.question.variants)) {
                if (value.variant == '') {
                    $('#variant-' + value.title).css('border', '1px solid red')
                    error = true
                }
            }
            if (!error) {
                this.$store.dispatch('setCurrentTest', this.test);
                this.setStep(2);
            }
        },
        storeTest() {
            this.testErrors = {};

            //триггер для запуска валидации в дочерних компонентах сложного теста
            this.$store.commit('setTestError', false);
            this.toValidate = !this.toValidate;

            //валидация простого теста
            if (this.test.type === 'easy') {

                if (this.test.title == '') {
                    this.testErrors.title = 'Назва обовʼязкова'
                }

                if (this.test.text == '') {
                    this.testErrors.text = 'Питання обовʼязкове'
                }
                if (!this.test.question.answer.correct.length) {
                    this.testErrors.correct = 'Має бути вказана принаймні одна правильна відповідь';
                }
            }
            if (!Object.keys(this.testErrors).length && !this.$store.state.testErrors) {
                this.$store.commit('storeTest', this.test);
                this.setStep(2);
            }
        },

    },

}
</script>

<style scoped>

</style>
