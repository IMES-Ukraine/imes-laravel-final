<template>
    <div>
        <div class="card-body">

            <fragment-form-text>
                <input class="form-control" type="text" id="survey_question_title" name="title" v-model="question.title">
            </fragment-form-text>

            <div class="row mb-3">
                <div class="article-edit__text col-3">
                    Опис
                </div>
                <div class="col-9">
                    <textarea class="form-control" rows="4" v-model="question.text"></textarea>
                    <!--<div class="error" v-if="$v.text.$error">
                        Текст вопроса обязателен
                    </div>-->
                </div>
            </div>

            <SurveyTestVariants v-bind:answer="answer" v-bind:variants="variants"></SurveyTestVariants>

            <div class="row">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-outline-primary" @click="addSurvey">
                        Додати опрос
                    </button>
                </div>
            </div>
            <br>

        </div>
    </div>
</template>
<script>
    import {required} from 'vuelidate/lib/validators'
    //import SimpleTestVariant from './../components/inputs/SimpleTestVariant.vue';
    import SimpleTestVariants from './../inputs/SimpleTestVariantsArray.vue'
    import SurveyTestVariants from './../inputs/SurveyTestVariantsArray.vue'
    import VContent from "../templates/Content"
    import { getRandomId } from '../../utils'
    import FragmentFormText from "./text";
    import {PROJECT_IMAGE} from "../../api/endpoints"
    //import CreateProjectForm from "./CreateProjectForm";
    //import { mapActions, mapState } from 'vuex'
    export default {
        name: 'TestQuestion',
        props: ['title', 'text', 'link', 'button', 'variants', 'answer', 'question'],
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
                let length = this.variants.length
                let obj = {
                    itemId: getRandomId(),
                    title: alphabet[length],
                    variant: '',
                    isCorrect: false,
                };
                this.variants.push(obj)
            },
            /**
             * Handle changing of file input (cover, video, variants)
             * @param event
             */
            handleUpload( event) {

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
                    //this[type] = file.data
                    this.question.media[type] = file.data
                    //this.options.files[input.dataset.ref] = file.data
                })
            },
        }
    }
</script>
