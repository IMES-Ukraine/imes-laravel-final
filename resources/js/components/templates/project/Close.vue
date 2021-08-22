<template>
    <div class="close-modal">

        <!-- close-icon -->
        <fragment-close-item
            @click="toggle"
        />
        <!-- end-close-icon -->

        <!-- modal-window -->
        <vue-window
            v-if="isOpen"
            @close="toggle"
        >

            <template slot="header">
                <h2 class="text-center font-weight-bold h2-title mb-4 mt-3">Закрыть проект?</h2>
            </template>

            <template slot="body">
                <div class="text-center mb-3">
                    <p>при закрытии проекта,<br>
                    все внесенные настойки не сохраняются</p>
                </div>
            </template>

            <template slot="footer">
                <div class="d-flex justify-content-center ml-auto mr-auto buttons mb-1">
                    <button class="btn btn-outline-primary mr-4" @click="close">
                        Да
                    </button>
                    <button class="btn btn-outline-primary" @click="toggle">
                        Нет
                    </button>
                </div>
            </template>

        </vue-window>
        <!-- end-modal-window -->
    </div>
</template>

<script>
import VueWindow from "../Window";
import FragmentCloseItem from "../../fragmets/close-item";
export default {
    name: "project-close",
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
