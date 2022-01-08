<template>
    <div class="articles_create__item">
        <div class="articles_create__item-title has_radio">
            <input type="checkbox"
                name="button"
                v-model="isChecked"
                   :checked="isChecked"
                @input="getText('')"
            />
            <i></i>
            <p>{{label}}</p>
        </div>
        <div class="articles_create__item-content">
            <div class="articles_create__name-block">
                <v-input-text
                    :isDisabled="!isChecked"
                    name="text_button"
                    v-model="text_button"
                    :classes="'input-is-form is-text'"
                    :aria-label="'теги'"
                    placeholder="http//"
                    v-on:update:value="getText"
                />
            </div>
        </div>
    </div>
</template>

<script>
import VCheckbox from "./checkbox";
import VInputText from "../../inputs/text";
export default {
    name: "article-form-button",
    components: {VInputText, VCheckbox},
    props: {
        v: {
            type: Object,
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
        text_button: {
            type: String,
            default: ''
        }
    },
    data() {
        return{
            isChecked: false
        }
    },
    methods: {
        getText (value) {
            this.$emit('update', value);
        }
    },
    watch: {
        text_button() {
            this.isChecked =  !!this.text_button;
        }
    },
}
</script>
<style>
    label {
        width: 100%;
    }
</style>
