<template>
    <div>
        <div class="articles_create-box">

            <fragment-form-text>
                <input class="form-control" type="text" id="survey_question_title" name="title" v-model="test.title">
                <div v-if="errors.testTitle" class="errors">{{ errors.testTitle }}</div>
            </fragment-form-text>

            <div class="articles_create__item half">
                <p class="articles_create__item-title">Обложка</p>
                <file-input :key="JSON.stringify(test)"
                            :value="test.cover"
                            @fileInput="test.cover = $event"
                            type="image"/>

            </div>

            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Вопрос</p>
                    <div class="articles_create__item-content direction-column">
                        <textarea class="form-control" rows="4" v-model="test.text"></textarea>
                        <div v-if="errors.testText" class="errors">{{ errors.testText }}</div>
                    </div>
                </div>
            </div>

            <SurveyTestVariants :variants="test.question.variants"></SurveyTestVariants>
            <div class="mb20"></div>
            <div v-if="errors.variants" class="errors mb20">{{ errors.variants }}</div>
            <button class="articles_create-submit button-border" type="button" @click="addSurvey">добавить ответ
            </button>

        </div>
        <button class="articles_create-submit button-gradient" type="button"
                @click="$emit('input', test)">сохранить
        </button>
    </div>
</template>
<script>
import SimpleTestVariants from './../inputs/SimpleTestVariantsArray.vue'
import SurveyTestVariants from './../inputs/SurveyTestVariantsArray.vue'
import VContent from "../templates/Content"
import FragmentFormText from "./text";
import ProjectMixin from "../../ProjectMixin";
import FileInput from "../inputs/file-input";

export default {
    name: 'TestQuestion',
    props: ['test', 'errors'],
    mixins: [ProjectMixin],
    components: {
        FragmentFormText,
        SimpleTestVariants,
        SurveyTestVariants,
        FileInput,
        VContent,
    },
    computed: {
        hasDescription: function () {
            return false;
        }
    },
    mounted() {
        if (!this.test.question.variants.length) {
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
        }
    }
}
</script>
