<template>
    <div>
        <button class="dashboard_study__block-more" type="button" data-toggle="modal" :data-target="'#db-test-popup-'+id">Подробней</button>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" :id="'db-test-popup-'+id" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="study template_box modal-content margin-auto" v-if="test">
                    <p class="template_title">{{ test.title }}</p>
                    <button class="template_close" type="button" data-dismiss="modal"></button>
                    <a :href="'/admin/api/v1/export-users-test/' + project_id + '/' + content_id" class="study-download"><span>Скачать отчёт пакета</span></a>
                    <div class="study-box">
                        <div class="study__block">
                            <template v-if="(test.type == 'easy' && test.picked == 'test')">
                                <p class="study__block-title">Вопрос: <b>{{ test.text }}</b></p>

                                <div class="study__block-content" v-if="(test.question.variants && test.question.type != 'text')">
                                    <div class="study__item" v-for="variant in test.question.variants">
                                        <div class="study__item-content">
                                            <div :class="(variant.right)?'study__answer active':'study__answer'">
                                                <p class="study__answer-letter">{{ variant.variant }}</p>
                                                <div class="study__answer-text" v-if="variant.media[0]">
                                                    <img :src="variant.media[0].path" alt="" />
                                                </div>
                                                <p class="study__answer-text" v-else>{{ variant.title }}</p>
                                            </div>
                                            <div class="study__info">
                                                <div class="study__info-block">
                                                    <p class="study__info-data">{{ percent(getStaticTest(variant.variant, tests[0]['id']), totalQuestionVariants(test.question.variants, tests[0]['id'])) }}%</p>
                                                    <div class="dashboard_main__status-line">
                                                        <span :style="'width:'+percent(getStaticTest(variant.variant, tests[0]['id']), totalQuestionVariants(test.question.variants, tests[0]['id']))+'%;'"></span>
                                                    </div>
                                                </div>
                                                <p class="study__info-quantity">{{ getStaticTest(variant.variant, tests[0]['id']) }}</p>
                                            </div>
                                        </div>
                                        <test-users-popup
                                            :title="test.title + ' - ' + variant.variant"
                                            :id="tests[0]['id']"
                                            :variant="variant.variant"
                                            v-if="getStaticTest(variant.variant, tests[0]['id'])"/>
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
                                        <template v-if="moderations">
                                            <div class="study__table-block" v-for="(moderation, key) in moderations.data">
                                                <div class="study__table-item">
                                                    <p class="study__table-number">{{key + 1}}</p>
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
                                                        <button :class="(moderation.status=='accept')?class_plus + ' active':class_plus" type="button" :disabled="(moderation.status=='cancel')?true:false" @click="accept(moderation.id)"></button>
                                                        <button :class="(moderation.status=='cancel')?class_minus + ' active':class_minus" type="button" :disabled="(moderation.status=='accept')?true:false" @click="decline(moderation.id)"></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="articles_pagination center">
                                                <pagination :data="moderations" @pagination-change-page="getResults"></pagination>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                            </template>

                            <template v-if="test.picked == 'survey'">
                                <p class="study__block-title">Вопрос: <b>{{ test.text }}</b></p>
                                <div class="study__block-content" v-if="test.question.variants">
                                    <div class="study__item" v-for="variant in test.question.variants">
                                        <div class="study__item-content">
                                            <div class="study__answer">
                                                <p class="study__answer-letter">{{ variant.variant }}</p>
                                                <p class="study__answer-text">{{ variant.title }}</p>
                                            </div>
                                            <div class="study__info">
                                                <div class="study__info-block">
                                                    <p class="study__info-data">{{ percent(getStaticTest(variant.variant, tests[0]['id']), totalQuestionVariants(test.question.variants, tests[0]['id'])) }}%</p>
                                                    <div class="dashboard_main__status-line">
                                                        <span :style="'width:'+percent(getStaticTest(variant.variant, tests[0]['id']), totalQuestionVariants(test.question.variants, tests[0]['id']))+'%;'"></span>
                                                    </div>
                                                </div>
                                                <p class="study__info-quantity">{{ getStaticTest(variant.variant, tests[0]['id']) }}</p>
                                            </div>
                                        </div>
                                        <test-users-popup
                                            :title="test.title + ' - ' + variant.variant"
                                            :id="tests[0]['id']"
                                            :variant="variant.variant"
                                            v-if="getStaticTest(variant.variant, tests[0]['id'])"/>
                                    </div>
                                </div>
                            </template>

                            <template v-if="(test.type == 'complex' && test.picked == 'test')">
                                <div class="study__block-content" v-for="complex_question in test.complex_question" style="padding-bottom: 30px;">
                                    <p class="study__block-title">Вопрос: <b>{{ complex_question.text }}</b></p>

                                    <div v-if="(complex_question.variants && complex_question.type != 'text')">
                                        <div class="study__item" v-for="variant in complex_question.variants">
                                            <div class="study__item-content">
                                                <div :class="(variant.right)?'study__answer active':'study__answer'">
                                                    <p class="study__answer-letter">{{ variant.variant }}</p>
                                                    <div class="study__answer-text" v-if="complex_question.type == 'media'">
                                                        <img :src="variant.media[0]['path']" alt="" />
                                                    </div>
                                                    <p class="study__answer-text" v-else>{{ variant.title }}</p>
                                                </div>
                                                <div class="study__info">
                                                    <div class="study__info-block">
                                                        <p class="study__info-data">{{ percent(getStaticTest(variant.variant, tests[0]['id']), totalQuestionVariants(test.question.variants, tests[0]['id'])) }}%</p>
                                                        <div class="dashboard_main__status-line">
                                                            <span :style="'width:'+percent(getStaticTest(variant.variant, tests[0]['id']), totalQuestionVariants(test.question.variants, tests[0]['id']))+'%;'"></span>
                                                        </div>
                                                    </div>
                                                    <p class="study__info-quantity">{{ getStaticTest(variant.variant, tests[0]['id']) }}</p>
                                                </div>
                                            </div>
                                            <test-users-popup
                                                :title="test.title + ' - ' + variant.variant"
                                                :id="tests[0]['id']"
                                                :variant="variant.variant"
                                                v-if="getStaticTest(variant.variant, tests[0]['id'])"/>
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
                                            <template v-if="moderations">
                                                <div class="study__table-block" v-for="(moderation, key) in moderations.data">
                                                    <div class="study__table-item">
                                                        <p class="study__table-number">{{key + 1}}</p>
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
                                                            <button :class="(moderation.status=='accept')?class_plus + ' active':class_plus" type="button" :disabled="(moderation.status=='cancel')?true:false" @click="accept(moderation.id)"></button>
                                                            <button :class="(moderation.status=='cancel')?class_minus + ' active':class_minus" type="button" :disabled="(moderation.status=='accept')?true:false" @click="decline(moderation.id)"></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="articles_pagination center">
                                                    <pagination :data="moderations" @pagination-change-page="getResults"></pagination>
                                                </div>
                                            </template>
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
    import {TEST, MODERATION, TEST_CONFIRMATION, TEST_DECLINE} from "../../../api/endpoints"
    import TestUsersPopup from "../../templates/dashboard/TestUsersPopup"

    export default {
        name: "test-popup",
        components: {FragmentCloseItem, TestUsersPopup},
        props: {
            id: {
                type: Number,
                default: 0
            },
            test: {
                type: Object,
                default: {}
            },
            tests: {
                type: Array,
                default: []
            },
            passing_tests: {
                type: Array,
                default: []
            },
            content_id: {
                type: Number,
                default: 0
            },
            project_id: {
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
                test_type: '',
                type: '',
                moderations: {},
                total_test: {},
                class_plus: 'study__table-button study__table-button--plus',
                class_minus: 'study__table-button study__table-button--minus'
            }
        },
        methods: {
            getStaticTest (title, test_id) {
                if (this.passing_tests[test_id] && this.passing_tests[test_id][title]) {
                    return this.passing_tests[test_id][title].length;
                }

                return 0;
            },
            toggle () {
                this.isOpen = this.isOpen === false;
            },
            close () {
                this.$router.push({ path: '/' })
            },
            getResults (page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }

                this.$get(MODERATION + '/' + this.tests[0]['id'] + '?page=' + page)
                    .then(response => {
                        this.moderations = response.data;
                    });
            },
            totalQuestionVariants (variants, test_id) {
                let total = 0;
                let total_test = 0;
                if (variants) {
                    for (let variant in variants) {
                        total = this.getStaticTest(variants[variant].variant, test_id);

                        if (total) {
                            total_test += total;
                        }
                    }
                }

                return total_test;
            },
            percent(status, total) {
                return status?parseInt(status * 100 / total):0
            },
            async accept(id) {
                this.$get(TEST_CONFIRMATION + "/" + id).then();

                for (const [index, value] of Object.entries(this.moderations.data)) {
                    if (value.id === id) {
                        value.status = 'accept'
                        return
                    }
                }
            },
            async decline(id) {
                this.$get(TEST_DECLINE + "/" + id).then();

                for (const [index, value] of Object.entries(this.moderations.data)) {
                    if (value.id === id) {
                        value.status = 'cancel'
                        return
                    }
                }
            },
        },
        mounted() {
            this.getResults(1);
        },
    }
</script>
