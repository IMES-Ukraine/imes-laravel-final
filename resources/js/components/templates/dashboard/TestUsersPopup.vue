<template>
    <div>
        <button class="study__item-button" type="button" data-toggle="modal" @click="showResults" :data-target="'#db-users-popup-'+id">Смотреть</button>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" :id="'db-users-popup-'+id" aria-hidden="true" >
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="study template_box modal-content margin-auto">
                    <p class="template_title">{{ title }}</p>
                    <button class="template_close" type="button" @click="hidePopup(id)"></button>
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
                                    </div>
                                    <div class="study__table-block" v-for="(result, key) in results.data" v-if="result" :key="key">
                                        <div class="study__table-item">
                                            <p class="study__table-number">{{ key + 1 }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-id">{{ result.user.id }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">{{ result.user.name }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-description">{{ result.user.email }} {{ (result.user.phone)?','+result.user.phone:'' }}</p>
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
    import { USER_PASSING_TEST } from "../../../api/endpoints"
    import VPreloader from "../../fragmets/preloader"

    export default {
        name: "test-users-popup",
        components: {FragmentCloseItem, VPreloader},
        props: {
            title: {
                type: String,
                default: ''
            },
            id: {
                type: Number,
                default: 0
            },
            variant: {
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
            hidePopup (id) {
                $('#db-users-popup-'+id).modal('hide')
            },
            showResults () {
                this.getResults();
            },
            getResults (page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }

                let url = USER_PASSING_TEST + '/' + this.id + '/' + this.variant;
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
