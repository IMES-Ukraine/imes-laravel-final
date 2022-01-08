<template>
    <input
        type="checkbox"
        :checked="checked"
        :name="name"
        :aria-label="ariaLabel"
        v-model="status"
        @click="update"
    >
</template>

<script>
    export default {
        name: "v-article-checkbox",
        data () {
            return {
                status: this.checked
            }
        },
        props: {
            id: {
                type: String,
                require: true
            },
            name: {
                type: String,
                require: true
            },
            checked: {
                type: Boolean,
                default: false
            },
            ariaLabel: {
                type: String,
                default: ''
            }
        },
        methods: {
            update() {
                this.$emit('update:value', this.status = !this.status);
            }
        }
    }
</script>
