<template>
    <div>
        <button class="dashboard_study__block-more" type="button" data-toggle="modal" :data-target="'#db-test-popup-'+id">Подробней</button>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" :id="'db-test-popup-'+id" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="study template_box modal-content margin-auto" v-if="test">
                    <p class="template_title">{{ test.title }}</p>
                    <button class="template_close" type="button" data-dismiss="modal"></button>
                    <button class="study-download" type="button"><span>Скачать отчёт пакета</span></button>
                    <div class="study-box">
                        <div class="study__block">
                            <template v-if="(test.type == 'easy' && test.picked == 'test')">
                                <p class="study__block-title">Вопрос: <b>{{ test.text }}</b></p>
                                <div class="study__block-content" v-if="test.question.variants">
                                    <div class="study__item" v-for="variant in test.question.variants">
                                        <div class="study__item-content">
                                            <div :class="(variant.isCorrect && type == 'variants')?'study__answer active':'study__answer'">
                                                <p class="study__answer-letter">{{ variant.title }}</p>
                                                <p class="study__answer-text">{{ variant.text }}</p>
                                            </div>
                                            <div class="study__info">
                                                <div class="study__info-block">
                                                    <p class="study__info-data">0%</p>
                                                    <div class="dashboard_main__status-line">
                                                        <span style="width:0%;"></span>
                                                    </div>
                                                </div>
                                                <p class="study__info-quantity">0</p>
                                            </div>
                                        </div>
                                        <button class="study__item-button">Смотреть</button>
                                    </div>
                                </div>
                            </template>

                            <template v-if="(test.type == 'easy' && test.picked == 'survey')">
                                <p class="study__block-title">Вопрос: <b>{{ test.text }}</b></p>
                                <div class="study__block-content" v-if="test.question.variants">
                                    <div class="study__item" v-for="variant in test.question.variants">
                                        <div class="study__item-content">
                                            <div class="study__answer">
                                                <p class="study__answer-letter">{{ variant.title }}</p>
                                                <p class="study__answer-text">{{ variant.variant }}</p>
                                            </div>
                                            <div class="study__info">
                                                <div class="study__info-block">
                                                    <p class="study__info-data">0%</p>
                                                    <div class="dashboard_main__status-line">
                                                        <span style="width:0%;"></span>
                                                    </div>
                                                </div>
                                                <p class="study__info-quantity">0</p>
                                            </div>
                                        </div>
                                        <!--<button class="study__item-button" type=button>Смотреть</button>-->
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FragmentCloseItem from "../../fragmets/close-item"
    import {TEST} from "../../../api/endpoints"

    export default {
        name: "test-popup",
        components: {FragmentCloseItem},
        props: {
            id: {
                type: Number,
                default: 0
            },
            test: {
                type: Object,
                default: {}
            }
        },
        data () {
            return {
                isOpen: false,
                title: '',
                question: '',
                variants: [],
                correct_answer: [],
                test_type: '',
                type: ''
            }
        },
        methods: {
            toggle () {
                this.isOpen = this.isOpen === false;
            },
            close () {
                this.$router.push({ path: '/' })
            },
            loadTest () {
                this.$get(TEST + '/' + this.id).then(response => {
                    console.log(response)
                    let data = response.data[0].items.data
                    this.title = data.title
                    this.question = data.text
                    this.variants = data.variants
                    this.test_type = data.test_type
                    this.type = data.question.answer.type
                });
            }
        },
        mounted() {
            //this.loadTest()
        }
    }
</script>
