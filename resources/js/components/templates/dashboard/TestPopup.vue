<template>
    <div>
        <button class="dashboard_study__block-more" type="button" data-toggle="modal" :data-target="'#db-test-popup-'+id">Подробней</button>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" :id="'db-test-popup-'+id" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="study template_box modal-content margin-auto">
                    <p class="template_title">{{ title }}</p>
                    <button class="template_close" type="button" data-dismiss="modal"></button>
                    <button class="study-download"><span>Скачать отчёт пакета</span></button>
                    <div class="study-box">
                        <div class="study__block">
                            <p class="study__block-title">Вопрос: <b>{{ question }}</b></p>
                            <div class="study__block-content" v-if="variants">
                                <div class="study__item" v-for="variant in variants">
                                    <div class="study__item-content">
                                        <div :class="hasCorrectAnswer(variant.itemId)">
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
                                    <button class="study__item-button">Смотреть</button>
                                </div>
                            </div>
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
            }
        },
        data () {
            return {
                isOpen: false,
                title: '',
                question: '',
                variants: [],
                correct_answer: [],
                test_type: ''
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
                    let data = response.data[0]
                    this.title = data.title
                    this.question = data.question
                    this.variants = data.variants.buttons
                    this.correct_answer = data.variants.correct_answer
                    this.test_type = data.test_type
                });
            },
            hasCorrectAnswer (itemId) {
                let active = '';
                if (this.test_type == "easy") {
                    if (this.correct_answer.includes(itemId)) {
                        active = ' active';
                    }
                }

                return 'study__answer' + active;
            }
        },
        mounted() {
            this.loadTest()
        }
    }
</script>
