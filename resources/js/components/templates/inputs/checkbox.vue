<template>
    <div class="articles_create__field_with_label-checkbox">
        <input
            type="checkbox"
            :checked="checked"
            :name="name"
            :aria-label="ariaLabel"
            v-model="status"
            @click="update"
        >
        <!--<label
            :for="id"
            class="custom-checkbox__label">
        </label>-->
        <i></i>
        <p>Повторное прохождение</p>
    </div>
</template>

<script>
export default {
    name: "v-checkbox",
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
