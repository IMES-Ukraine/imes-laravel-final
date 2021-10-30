<template>
    <div class="width-full">

        <a class="sidebar_nav-button" data-toggle="modal" data-target="#db-stop-project"><span>Запустить</span></a>

        <!-- modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="db-stop-project" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered db-modal__dialog full-width" role="document">
                <div class="stop_project modal-content margin-auto">
                    <div class="stop_project-close" type="button" data-dismiss="modal"></div>
                    <p class="stop_project-title">Восстановить проект?</p>
                    <div class="stop_project-controls">
                        <a class="sidebar_nav-button red radius-5"><span>Да</span></a>
                        <a class="sidebar_nav-button radius-5" data-dismiss="modal"><span>Отмена</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FragmentCloseItem from "../../fragmets/close-item"

    export default {
        name: "project-start",
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
            }
        }
    }
</script>
