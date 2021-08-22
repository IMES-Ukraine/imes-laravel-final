<template>
    <div class="article-edit__radio form-check form-check-inline">
        <input
            type="radio"
            :name="name"
            :id="id"
            :value="value"
            :checked="checked"
            @click="update"
        >
        <label
            class="article-edit__label article-edit__radio form-check-label"
            :for="id"
        >
            <slot>
                Label for radio
            </slot>
        </label>
    </div>
</template>

<script>
export default {
    name: "v-radio",
    props: {
        id: String,
        name: String,
        value: Number,
        checked: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        update() {
            this.$emit('update:value', this.value);
        }
    }
}
</script>
