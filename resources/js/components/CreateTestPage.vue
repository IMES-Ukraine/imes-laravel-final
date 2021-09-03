<template>
    <v-content>

        <template v-slot:sidebar>
            OK
        </template>

        <div class="main-articles">
            <div class="article-edit card">
                <a href="#" @click="back" class="article-edit__close" aria-label="закрити" title="закрити"></a>
                <h4>Тест</h4>

                <div v-for="test in tests" v-bind:key="test.title">
                    <CreateTestForm
                        v-bind:question="test.question"
                        v-bind:variants="test.variants"
                        v-bind:answer="test.answer"
                    />
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
    import VContent from "./templates/Content"
    import CreateTestForm from './../components/CreateTestForm.vue'
    //import { variantTemplate } from './../utils'
    //@vue/component
    export default {
        name: 'CreateTestPage',

        components: {
            CreateTestForm,
            VContent
        },

        mixins: [],

        props: {},

        data () {
            return {
                tests: this.$store.state.tests,
                isComplex: false,
            }
        },

        computed: {},

        watch: {},

        created () {},

        methods: {
            submitComplex() {

                this.tests.push({
                    question: {
                        title: 'Заголовок',
                        text: 'Тхт',
                        description: '',
                        //link: 'http://imes.pro/',
                        link: 'https://laravel-dev-final.imes.pro/',
                        button: 123,
                        count: null,
                        points: null,
                        media: null,
                        isComplex: true,
                        agreement: null
                    },
                    variants: [{
                        itemId: 'variant-' + Math.random().toString(36).substr(2, 9),
                        title: 'A',
                        variant: '',
                        isCorrect: false,
                    }],
                    answer: {
                        correct: [],
                        type: 'text' //answer type (variants | text field)
                    },
                })
                this.isComplex = true;
            },
            submitForm() {
                this.$store.dispatch('submitTest', this.$data.tests);
            },
            back(){
                this.$store.dispatch('addContent')
            }
        }
    }

</script>
