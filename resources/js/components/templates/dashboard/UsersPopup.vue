<template>
    <div>

        <button class="dashboard_main__item-button" type="button" data-toggle="modal" :data-target="'#db-users-popup-'+id">Смотреть</button>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" :id="'db-users-popup-'+id" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="study template_box modal-content margin-auto">
                    <p class="template_title">{{ title }}</p>
                    <button class="template_close" type="button" data-dismiss="modal"></button>
                    <div class="study-box">
                        <div class="study__block">
                            <div class="study__block-content">
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
                                    <div class="study__table-block" v-for="(user, key) in users" v-if="user" :key="key">
                                        <div class="study__table-item">
                                            <p class="study__table-number">{{ key + 1 }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-id">{{ user.id }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-title">{{ user.name }}</p>
                                        </div>
                                        <div class="study__table-item">
                                            <p class="study__table-description">{{ user.email }} {{ (user.phone)?','+user.phone:'' }}</p>
                                        </div>
                                    </div>
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
    import {USER_PASSING} from "../../../api/endpoints"

    export default {
        name: "users-popup",
        components: {FragmentCloseItem},
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
                users: []
            }
        },
        methods: {
            toggle () {
                this.isOpen = this.isOpen === false;
            },
            close () {
                this.$router.push({ path: '/' })
            },
            loadUsers () {
                this.$get(USER_PASSING + '/' + this.project_id + '/' + this.index).then(response => {
                    if (response.data) {
                        this.users = response.data.data
                    }
                });
            }
        },
        mounted() {
            this.loadUsers()
        }
    }
</script>
