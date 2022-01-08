<template>
    <div class="width-full">

        <a class="sidebar_nav-button" data-toggle="modal" data-target="#db-stop-project"><span>Остановить</span></a>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="db-stop-project" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="stop_project modal-content margin-auto">
                    <div class="stop_project-close" type="button" data-dismiss="modal"></div>
                    <p class="stop_project-title">Остановить проект?</p>
                    <div class="stop_project__block">
                        <p class="stop_project__block-title">Внимание!</p>
                        <p class="stop_project__block-description">При скрытии проекта, все активности пользователей исчезнут. Все результаты проекта будут сохранены. Вы сможете продолжить проект в любой момент.</p>
                    </div>
                    <div class="stop_project-controls">
                        <a class="sidebar_nav-button red radius-5" @click="stopped" data-dismiss = "modal"><span>Остановить</span></a>
                        <a class="sidebar_nav-button radius-5" data-dismiss="modal"><span>Отмена</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FragmentCloseItem from "../../fragmets/close-item"
    import { PROJECT_STOP, TOKEN } from "../../../api/endpoints"

    export default {
        name: "project-stop",
        components: {FragmentCloseItem},
        props: {
            title: {
                type: String,
                default: ''
            }
        },
        data () {
            return {
                isOpen: false,
            }
        },
        methods: {
            toggle () {
                this.isOpen = this.isOpen === false;
            },
            close () {
                this.$router.push({ path: '/' })
            },
            stopped () {
                this.$post(PROJECT_STOP + this.$route.params.projectId, {
                    id: this.$route.params.projectId
                }, {
                    params: {
                        access_token: TOKEN
                    },
                }).then(response => {
                    this.$emit('update', response.message.status);
                })
                $('#db-start-project').modal('hide')
            }
        }
    }
</script>
