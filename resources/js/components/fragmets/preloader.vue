<template>
    <div class="preloader-main">
        <loading
            :active.sync="isLoading"
            :can-cancel="canCancel"
            :on-cancel="onCancel"
            :is-full-page="isFullPage"
            :backgroundColor="'inherit'"
        />
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "v-preloader",
    components: {Loading},
    props: {
        isLoading: {
            type: Boolean,
            default: true
        },
        canCancel: {
            type: Boolean,
            default: false
        },
        isFullPage: {
            type: Boolean,
            default: false
        },

    },
    methods: {
        onCancel() {
            this.$emit('onCancel');
        }
    }
}
</script>

<style>
    .preloader-main {
        height: 50vh;
        background-color: inherit;
    }
</style>
