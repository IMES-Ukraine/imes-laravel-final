<template>
    <div>

        <button :class="classButton" type="button" data-toggle="modal" @click="showResults" :data-target="'#db-users-popup-'+id">Смотреть</button>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" :id="'db-users-popup-'+id" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="study template_box modal-content margin-auto">
                    <p class="template_title">{{ title }}</p>
                    <button class="template_close" type="button" data-dismiss="modal"></button>
                    <div class="study-box">
                        <div class="study__block">
                            <div class="study__block-content" v-if="results.data">
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
                                            <p class="study__table-title">Детальная информация</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">Вопрос</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">Ответ</p>
                                        </div>
                                    </div>
                                    <div class="study__table-block" v-for="(result, key) in results.data" v-if="result" :key="key">
                                        <div class="study__table-item">
                                            <p class="study__table-number">{{ key + 1 }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-id">{{ (result.user)?result.user.id:result.id }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">{{ (result.user)?result.user.name:result.name }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-description">{{ (result.user)?result.user.email:result.email }} {{ (result.user || result.phone)?','+((result.user)?result.user.phone:result.phone):'' }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">{{ result.test.question }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">{{ result.answer[0] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="articles_pagination center">
                                    <pagination :data="results" @pagination-change-page="getResults"></pagination>
                                </div>
                            </div>
                            <v-preloader v-else />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FragmentCloseItem from "../../fragmets/close-item"
    import { USER_PASSING, USER_PASSING_TEST_ALL, USER_PASSING_ARTICLE_ALL } from "../../../api/endpoints"
    import VPreloader from "../../fragmets/preloader"

    export default {
        name: "users-popup",
        components: {FragmentCloseItem, VPreloader},
        props: {
            title: {
                type: String,
                default: ''
            },
            id: {
                type: String,
                default: ''
            },
            index: {
                type: String,
                default: ''
            },
            project_id: {
                type: Number,
                default: 0
            },
            classButton: {
                type: String,
                default: 'dashboard_main__item-button'
            },
            content_id: {
                type: Number,
                default: 0
            },
            is_test: {
                type: String,
                default: ''
            },
            is_article: {
                type: String,
                default: ''
            }
        },
        data () {
            return {
                isOpen: false,
                results: {}
            }
        },
        methods: {
            toggle () {
                this.isOpen = this.isOpen === false;
            },
            close () {
                this.$router.push({ path: '/' })
            },
            showResults () {
                this.getResults();
            },
            getResults (page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }

                let url = USER_PASSING + '/' + this.project_id + '/' + this.index;

                if (this.is_test) {
                    url = USER_PASSING_TEST_ALL + '/' + this.content_id + '/' + this.index;
                }

                if (this.is_article) {
                    url = USER_PASSING_ARTICLE_ALL + '/' + this.content_id + '/' + this.index;
                }

                this.$get(url + '?page=' + page)
                    .then(response => {
                        this.results = response.data
                    });
            },
        },
        /*mounted() {
            this.getResults();
        }*/
    }
</script>
