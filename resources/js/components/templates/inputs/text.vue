<template>
    <input
        class="form-control"
        type="text"
        :class="classes"
        :name="name"
        :disabled="isDisabled"
        :placeholder="placeholder"
        :value="value"
        @change="update"
    >
</template>

<script>
export default {
    name: "v-input-text",
    props: {
        name: {
            type: String,
            require: true
        },
        value: {
            type: String,
            default: ''
        },
        isDisabled: {
            type: Boolean,
            default: false
        },
        classes: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: ''
        }
    },
    methods: {
        update(event) {
            this.$emit('update:value', event.target.value);
        }
    }
}
</script>
