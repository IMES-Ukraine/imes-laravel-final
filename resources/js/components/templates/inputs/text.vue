<template>
    <label>
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
    </label>
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
        update(value) {
            this.$emit('update:value', value);
        }
    }
}
</script>
