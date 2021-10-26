<template>
    <textarea
        class="form-control"
        :rows="rows"
        v-model="value"
        @change="update"
    >
    </textarea>
</template>

<script>
export default {
    name: "v-textarea",
    data() {
        return {
            value: this.text
        }
    },
    props: {
        rows: {
            type: Number,
            require: false,
            default: 1
        },
        text: {
            type: String,
            default: ''
        },
    },
    methods: {
        update() {
            this.$emit('update:text', this.value);
        }
    }
}
</script>
