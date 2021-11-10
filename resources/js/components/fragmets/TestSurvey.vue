<template>
    <div>
        <div class="articles_create-box">

            <fragment-form-text>
                <input class="form-control" type="text" id="survey_question_title" name="title" v-model="test.title">
                <div v-if="errorTestSurveyTitle" class="errors">{{ errorTestSurveyTitle }}</div>
            </fragment-form-text>


            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Вопрос</p>
                    <div class="articles_create__item-content direction-column">
                        <textarea class="form-control" rows="4" v-model="test.text"></textarea>
                        <div v-if="errorTestSurveyText" class="errors">{{ errorTestSurveyText }}</div>
                    </div>
                </div>
            </div>

            <SurveyTestVariants :variants="test.variants"></SurveyTestVariants>
            <div class="mb20"></div>

            <button class="articles_create-submit button-border" type="button" @click="addSurvey">добавить ответ</button>

        </div>
    </div>
</template>
<script>
    import {required} from 'vuelidate/lib/validators'
    import SimpleTestVariants from './../inputs/SimpleTestVariantsArray.vue'
    import SurveyTestVariants from './../inputs/SurveyTestVariantsArray.vue'
    import VContent from "../templates/Content"
    import { getRandomId } from '../../utils'
    import FragmentFormText from "./text";

    export default {
        name: 'TestQuestion',
        props: [ 'test', 'errorTestSurveyTitle', 'errorTestSurveyText'],
        components: {
            FragmentFormText,
            //SimpleTestVariant,
            SimpleTestVariants,
            SurveyTestVariants,
            VContent
        },

        data() {
            return {
                //tests: this.$store.state.tests,
            }
        },
        validations: {
            text: {
                required
            },
        },
        computed: {
            hasDescription: function() {
                return false;
            }
        },
        methods: {
            /**
             * Adding one more answer variant to question
             */
            addSurvey() {
                const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ']
                let length = this.test.variants.length
                let obj = {
                    itemId: 'survey-' + getRandomId(),
                    title: alphabet[length],
                    variant: '',
                    isCorrect: false,
                    answer: {
                        type: 'text'
                    }
                };
                this.test.variants.push(obj)
            }
        }
    }
</script>
