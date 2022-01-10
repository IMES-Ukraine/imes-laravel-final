<template>
    <div class="row mb-4">
        <div class="article-edit__text col-3">
            {{label}}
        </div>
        <div class="col-4">
            <label
                class="btn btn-outline-second btn-centered-content is-small"
                :name="name"
            >
                <v-select
                    :name="name"
                    :options="options"
                    :current="current"
                    :option-key="'id'"
                    :option-value="'id'"
                    :option-view="'name'"
                    v-on:update:value="update"
                />
            </label>
        </div>

    </div>
</template>

<script>
import VSelect from "../../inputs/select";
export default {
    name: "article-form-select",
    components: {VSelect},
    props: {
        /*options: {
            type: Array,
            require: true
        },*/
        name: {
            type: String,
            default: 'select'
        },
        label: {
            type: String,
            require: true
        },
        current: {
            default: ''
        }
    },
    methods: {
        update (value) {
            this.$emit('update:value', value);
        }
    },
    data() {
        return {
            options: this.$store.state.lists[this.name]
        }
    }
}
</script>
