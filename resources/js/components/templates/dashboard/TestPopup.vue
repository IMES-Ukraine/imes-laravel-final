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

                                <div class="study__block-content" v-if="(test.question.variants && test.question.answer.type != 'text')">
                                    <div class="study__item" v-for="variant in test.question.variants">
                                        <div class="study__item-content">
                                            <div :class="(variant.right)?'study__answer active':'study__answer'">
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
                                        <!--<button class="study__item-button" type="button">Смотреть</button>-->
                                    </div>
                                </div>
                                <div class="study__block-content" v-else>
                                    <div class="study__table">
                                        <div class="study__table-block study__table-block--head">
                                            <div class="study__table-item">
                                                <p class="study__table-title">№ п</p>
                                            </div>
                                            <div class="study__table-item">
                                                <p class="study__table-title">ID</p>
                                            </div>
                                            <div class="study__table-item">
                                                <p class="study__table-title">ФИО</p>
                                            </div>
                                            <div class="study__table-item">
                                                <p class="study__table-title">Ответ</p>
                                            </div>
                                            <div class="study__table-item">
                                                <div class="study__table-controls--title">
                                                    <p>Принять</p>
                                                    <p>Отклонить</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="study__table-block" v-for="(moderation, key) in moderations">
                                            <div class="study__table-item">
                                                <p class="study__table-number">{{key}}</p>
                                            </div>
                                            <div class="study__table-item">
                                                <p class="study__table-id">{{(moderation.user)?moderation.user.id:0}}</p>
                                            </div>
                                            <div class="study__table-item">
                                                <p class="study__table-name">{{(moderation.user)?moderation.user.name:'Уже нет такого пользователя'}}</p>
                                            </div>
                                            <div class="study__table-item">
                                                <p class="study__table-description">{{moderation.answer}}</p>
                                            </div>
                                            <div class="study__table-item">
                                                <div class="study__table-controls">
                                                    <button class="study__table-button study__table-button--plus" type="button"></button>
                                                    <button class="study__table-button study__table-button--minus" type="button"></button>
                                                </div>
                                            </div>
                                        </div>
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

                            <template v-if="(test.type == 'complex' && test.picked == 'test')">
                                <div v-for="complex_question in test.complex_question">
                                    <p class="study__block-title">Вопрос: <b>{{ complex_question.text }}</b></p>

                                    <div class="study__block-content" v-if="(complex_question.variants && complex_question.answer.type != 'text')">
                                        <div class="study__item" v-for="variant in complex_question.variants">
                                            <div class="study__item-content">
                                                <div :class="(variant.right)?'study__answer active':'study__answer'">
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
                                            <!--<button class="study__item-button" type="button">Смотреть</button>-->
                                        </div>
                                    </div>
                                    <div class="study__block-content" v-else>
                                        <div class="study__table">
                                            <div class="study__table-block study__table-block--head">
                                                <div class="study__table-item">
                                                    <p class="study__table-title">№ п</p>
                                                </div>
                                                <div class="study__table-item">
                                                    <p class="study__table-title">ID</p>
                                                </div>
                                                <div class="study__table-item">
                                                    <p class="study__table-title">ФИО</p>
                                                </div>
                                                <div class="study__table-item">
                                                    <p class="study__table-title">Ответ</p>
                                                </div>
                                                <div class="study__table-item">
                                                    <div class="study__table-controls--title">
                                                        <p>Принять</p>
                                                        <p>Отклонить</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="study__table-block" v-for="(moderation, key) in moderations">
                                                <div class="study__table-item">
                                                    <p class="study__table-number">{{key}}</p>
                                                </div>
                                                <div class="study__table-item">
                                                    <p class="study__table-id">{{(moderation.user)?moderation.user.id:0}}</p>
                                                </div>
                                                <div class="study__table-item">
                                                    <p class="study__table-name">{{(moderation.user)?moderation.user.name:'Уже нет такого пользователя'}}</p>
                                                </div>
                                                <div class="study__table-item">
                                                    <p class="study__table-description">{{moderation.answer}}</p>
                                                </div>
                                                <div class="study__table-item">
                                                    <div class="study__table-controls">
                                                        <button class="study__table-button study__table-button--plus" type="button"></button>
                                                        <button class="study__table-button study__table-button--minus" type="button"></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    import {TEST, MODERATION} from "../../../api/endpoints"

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
                type: '',
                moderations: []
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
            },
            loadModerations (test_id) {
                this.$get(MODERATION + '/' + test_id).then(response => {
                    console.log(response)
                    this.moderations = response.data
                });
            }
        },
        mounted() {
            //this.loadTest()
            this.loadModerations(2702)
        }
    }
</script>
