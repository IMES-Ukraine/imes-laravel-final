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
                        :errors="testErrors"/>
                </div>
                <div class="mb20"></div>

            </div>

            <div v-if="test.type === 'complex'">
                <div>
                    <TestComplex :test="test"
                                 :variants="test.variants"
                                 :errors="testErrors"
                                 @input="storeTest($event)"
                                 :toValidate="toValidate"/>
                </div>

            </div>
        </div>

        <div v-if="test.picked === 'survey'">
            <div>
                <TestSurvey :test.sync="test"
                            @input="storeTest($event)"
                            :errorTestSurveyTitle="testErrors.title"
                            :errorTestSurveyText="testErrors.text"/>
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
        }
    },
    methods: {

        storeTest(data) {
            if (data){
                this.$store.commit('storeTest', data);
            }
            this.testErrors = {};

            //триггер для запуска валидации в дочерних компонентах сложного теста
            this.$store.commit('setTestError', false);
            this.toValidate = !this.toValidate;

            if (!this.test.title) {
                this.testErrors.title = 'Назва обовʼязкова'
            }

            if (!this.test.text) {
                this.testErrors.text = 'Питання обовʼязкове'
            }

            if (this.test.picked === 'survey') {
                for (const [index, value] of Object.entries(this.test.question.variants)) {
                    if (value.text == '') {
                        $('#variant-' + value.title).css('border', '1px solid red');
                        this.$store.commit('setTestError', true);
                    }
                }
            } else if (this.test.type == 'easy'){
                if(!this.test.question.correct.length) {
                    this.testErrors.correct = 'Має бути вказана принаймні одна правильна відповідь';
                }
            }
            else {
                if (!this.test.complex_question.count){
                    this.testErrors.complex = 'Має бути вказаний принаймні один блок з питаннями';
                }
            }

            if (!Object.keys(this.testErrors).length && !this.$store.state.testErrors) {
                this.$store.commit('saveTest', this.test);
                this.setStep(2);
            }
        },

    },

}
</script>

<style scoped>

</style>
