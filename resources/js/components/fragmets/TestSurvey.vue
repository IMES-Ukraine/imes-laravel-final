<template>
    <div>
        <div class="articles_create-box">

            <fragment-form-text>
                <input class="form-control" type="text" id="survey_question_title" name="title" v-model="test.title">
                <div v-if="errors.title" class="errors">{{ errors.title }}</div>
            </fragment-form-text>


            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Вопрос</p>
                    <div class="articles_create__item-content direction-column">
                        <textarea class="form-control" rows="4" v-model="test.text"></textarea>
                        <div v-if="errors.text" class="errors">{{ errors.text }}</div>
                    </div>
                </div>
            </div>

            <SurveyTestVariants :variants="test.question.variants"></SurveyTestVariants>
            <div class="mb20"></div>
            <div v-if="errors.variants" class="errors mb20">{{ errors.variants }}</div>
            <button class="articles_create-submit button-border" type="button" @click="addSurvey">добавить ответ</button>

        </div>
        <button class="articles_create-submit button-gradient" type="button"
                @click="$emit('input', test)">сохранить
        </button>
    </div>
</template>
<script>
    import {required} from 'vuelidate/lib/validators'
    import SimpleTestVariants from './../inputs/SimpleTestVariantsArray.vue'
    import SurveyTestVariants from './../inputs/SurveyTestVariantsArray.vue'
    import VContent from "../templates/Content"
    import { getRandomId } from '../../utils'
    import FragmentFormText from "./text";
    import ProjectMixin from "../../ProjectMixin";

    export default {
        name: 'TestQuestion',
        props: [ 'test', 'errors'],
        mixins: [ProjectMixin],
        components: {
            FragmentFormText,
            //SimpleTestVariant,
            SimpleTestVariants,
            SurveyTestVariants,
            VContent,
        },
        computed: {
            hasDescription: function() {
                return false;
            }
        },
        mounted() {
            if (! this.test.question.variants.length) {
                this.addAnswerTest(0);
                this.addAnswerTest(1);
            }
        },
        methods: {
            /**
             * Adding one more answer variant to question
             */
            addSurvey() {
                this.addAnswerTest(this.test.question.variants.length);
                // const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ']
                // let length = this.test.variants.length
                // let obj = {
                //     itemId: 'survey-' + getRandomId(),
                //     title: alphabet[length],
                //     variant: '',
                //     isCorrect: false,
                //     answer: {
                //         type: 'text'
                //     }
                // };
                // this.test.variants.push(obj)
            }
        }
    }
</script>
