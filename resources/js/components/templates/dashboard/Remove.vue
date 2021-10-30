<template>
    <div class="close-modal width-full">

        <a class="sidebar_nav-button red" @click="toggle"><span class="icon-delete">Удалить проект</span></a>

        <!-- modal-window -->
        <vue-window
            v-if="isOpen"
            @close="toggle"
        >

            <template slot="header">
                <h2 class="text-center font-weight-bold h2-title mb-4 mt-3">Удалить проект?</h2>
            </template>

            <template slot="body">
                <div class="text-center mb-3">
                    <p>при удалении проекта,<br>
                        его не восстановить</p>
                </div>
            </template>

            <template slot="footer">
                <div class="d-flex justify-content-center ml-auto mr-auto buttons mb-1">
                    <button class="btn btn-outline-primary mr-4" type="button" @click="destroy">
                        Да
                    </button>
                    <button class="btn btn-outline-primary" type="button" @click="toggle">
                        Нет
                    </button>
                </div>
            </template>

        </vue-window>
        <!-- end-modal-window -->
    </div>
</template>

<script>
    import VueWindow from "../Window"
    import FragmentCloseItem from "../../fragmets/close-item"
    import { PROJECT_DESTROY } from "../../../api/endpoints"

    export default {
        name: "project-remove",
        components: {FragmentCloseItem, VueWindow},
        data () {
            return {
                isOpen: false,
            }
        },
        methods: {
            toggle () {
                this.isOpen = this.isOpen === false;
            },
            destroy () {
                this.$delete(PROJECT_DESTROY + this.$route.params.projectId).then()
                this.$router.push({ path: '/' })
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
