<template>

    <v-content>

        <template v-slot:sidebar>
            <project-form-sidebar/>
        </template>

        <div class="main-articles">
            <div class="article-edit card">
                <a href="#" @click="back" class="article-edit__close" aria-label="закрити" title="закрити"></a>
                <h4>Тест</h4>

                <div v-for="item in questions" v-bind:key="item.title">

                    <Question

                        v-bind:question="item.question"
                        v-bind:variants="item.variants"
                        v-bind:answer="item.answer"></Question>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-outline-primary" @click="submitComplex">
                            <div v-if="isComplex">Добавить вопрос</div>
                            <div v-else>Сделать сложным</div>
                        </button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-outline-primary" @click="submitForm">
                            СОХРАНИТЬ
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </v-content>
</template>

<script>
import Question from './fragmets/TestQuestion.vue'
import VContent from "./templates/Content"
import ProjectFormSidebar from "./templates/project/form/sidebar";
//import { variantTemplate } from './../utils'
//@vue/component
export default {
    name: 'TestForm',
    components: {
        ProjectFormSidebar,
        Question,
        VContent
    },
    mixins: [],
    props: {},
    data() {
        return {
            questions: this.$store.state.questions,
            isComplex: false,
        }
    },
    computed: {
        isNew() {
            return !this.$route.params.entityId
        },
        entityId() {
            return this.$route.params.entityId
        }
    },
    watch: {},
    created() {
    },
    methods: {
        submitComplex() {
            this.addQuestion()
            this.isComplex = true;
        },
        addQuestion() {
            this.questions.push({
                question: {
                    title: '',
                    text: '',
                    description: '',
                    link: '',
                    button: null,
                    count: null,
                    points: null,
                    media: null,
                    isComplex: true,
                    agreement: null
                },
                variants: [
                    {
                        itemId: 'variant-' + Math.random().toString(36).substr(2, 9),
                        title: 'A',
                        variant: '',
                        isCorrect: false,
                    }
                ],
                answer: {
                    correct: [],
                    type: 'text' //answer type (variants | text field)
                },
            })
        },
        submitForm() {
            this.$store.dispatch('submitTest', this.$data.questions)
        },
        back() {
            this.$store.dispatch('addContent')
        }
    },
    mounted() {
        if ( this.isNew) {
            this.addQuestion()
        } else {
            this.addQuestion()
        }
    }
}
</script>
