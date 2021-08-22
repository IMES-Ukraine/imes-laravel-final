<template>
    <div class="row mb-4">
        <div class="article-edit__text col-3">
                <v-checkbox
                    :name="name"
                    :id="id"
                    v-bind:value.sync="isChecked"
                    v-on:update:value="getText('')"
                />
            {{label}}
        </div>
        <div class="col-9">
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="input-group input-group">
                        <v-input-text
                            :isDisabled="!isChecked"
                            :name="name"
                            :classes="'input-is-form is-text'"
                            :aria-label="'теги'"
                            v-on:update:value="getText"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VCheckbox from "../../inputs/checkbox";
import VInputText from "../../inputs/text";
export default {
    name: "article-form-button",
    components: {VInputText, VCheckbox},
    data() {
        return {
            isChecked: false
        }
    },
    props: {
        button: {
            type: String,
            require: true
        },
        label: {
            type: String,
            require: true
        },
        id: {
            type: String,
            require: true
        },
        name: {
            type: String,
            require: true
        }
    },
    methods: {
        getText (value) {
            this.$emit('update:value', value);
        }
    }
}
</script>
<style>
    label {
        width: 100%;
    }
</style>
