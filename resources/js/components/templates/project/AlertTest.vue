<template>
    <!-- modal -->
    <div class="modal db-modal fade" id="modalAlertTest" tabindex="-1" role="dialog" aria-modal="true" style="display: none;"><!-- 224 -->
        <div class="modal-dialog modal-dialog-centered db-edit-modal__dialog" role="document">
            <div class="db-edit-modal__content modal-content">
                <button class="articles_create-close" data-dismiss="modal"></button>
                <div class="modal-body p-0">
                    <h2 class="text-center font-weight-bold h2-title mb-4 mt-3">Предупреждение</h2>

                    <div class="text-center mb-3">
                        <p>Обязательно должен быть<br>
                            выбран хотя бы 1 вариант</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueWindow from "../Window";
    import FragmentCloseItem from "../../fragmets/close-item";
    export default {
        name: "project-alert-test",
        components: {FragmentCloseItem, VueWindow},
        data () {
            return {
                isOpen: false,
            }
        },
        props: {
            parentPath: {
                type: String,
                default: '/'
            }
        },
        methods: {
            toggle () {
                this.isOpen = this.isOpen === false;
            },
            close () {
                this.$router.push({ path: this.parentPath })
            }
        }
    }
</script>

<style>
    .h2-title {
        font-size: 17px;
        color: #333333;
    }
    .buttons .btn {
        border-radius: 5px;
        width: 163px;
        height: 40px;
    }
</style>
