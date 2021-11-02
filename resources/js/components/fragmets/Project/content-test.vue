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
                                       v-model="test.picked" @change="onChangePicked($event, index_test)"
                                       checked="">
                                <p><span class="icon-plus">Теста</span></p>
                            </div>
                        </div>
                        <div class="articles_create__grid-block">
                            <div class="articles_create__sorting">
                                <input type="radio" name="radio" id="two" value="survey"
                                       @change="onChangePicked($event, index_test)"
                                       v-model="test.picked">
                                <p><span class="icon-plus">Опроса</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="picked === 'test'">
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

            <div v-if="type === 'easy'">
                <div v-for="item in test.questions" v-bind:key="item.title">

                    <Question
                        :question="item.question"
                        :variants="item.variants"
                        :answer="item.answer"></Question>
                </div>
                <div class="mb20"></div>
                <button class="articles_create-submit button-gradient" type="button"
                        @click="saveTest">сохранить
                </button>
            </div>

            <div v-if="type === 'complex'">
                <div v-for="item in test.questions" v-bind:key="item.title">
                    <TestComplex :question="item.question"
                                 :complex_question="item.complex_question"
                                 :answer="item.answer"></TestComplex>
                </div>
                <div class="mb20"></div>
                <button class="articles_create-submit button-gradient" type="button"
                        @click="saveTest">сохранить
                </button>
            </div>
        </div>

        <div v-if="picked === 'survey'">
            <div v-for="item in test.questions" v-bind:key="item.title">
                <TestSurvey :question="item.question"
                            :variants="item.variants"
                            :errorTestSurveyTitle="errorTestSurveyTitle"
                            :errorTestSurveyText="errorTestSurveyText"
                            :answer="item.answer"></TestSurvey>
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
          test: {
              questions: {},
              picked: 'test',
              type: 'easy'
          }
      }
    },
    methods: {
        saveTestSurvey() {
            this.errorTestSurveyTitle = ''
            this.errorTestSurveyText = ''
            let error = false

            $('#survey-test input').css('border', '1px solid #D9D9D9')

            if (this.test.questions[0].question.title == '') {
                this.errorTestSurveyTitle = 'Название обязательно'
                error = true
            }

            if (this.test.questions[0].question.text == '') {
                this.errorTestSurveyText = 'Вопрос обязателен'
                error = true
            }

            for (const [index, value] of Object.entries(this.test.questions[0].variants)) {
                if (value.variant == '') {
                    $('#variant-' + value.title).css('border', '1px solid red')
                    error = true
                }
            }

            if (!error) {
                this.add_new_test = true
                //$('#add_new_test .articles_create__study-title').html($('#survey_question_title').val());

                this.setStep(2);
                this.$store.dispatch('nextStep')

                this.tests.push([])

                this.index_test += 1
            }
        },
        saveTest() {
            this.add_new_test = true
            //$('#add_new_test .articles_create__study-title').html($('#question_title').val());
            this.$store.state.content.test = this.test;
            this.setStep(2);
        },
    },
    mounted() {
        this.test = this.$store.state.content.test
    }
}
</script>

<style scoped>

</style>
