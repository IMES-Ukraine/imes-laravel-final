<template>
    <div>
        <div class="articles_create__item" id="survey-test" v-for="(variant, index) in variants" v-bind:key="variant.itemId">
            <p class="articles_create__item-title">Выбор</p>
            <div class="articles_create__item-content">
                <div class="articles_create__ready_answer full_width">
                    <p class="articles_create__ready_answer-letter">{{ variant.title }}</p>
                    <input type="text" name="title" placeholder="" :id="'variant-' + variant.title" v-model.lazy="variant.variant">
                    <button class="articles_create__ready_answer-delete" type="button" @click="removeVariant(variant.itemId)"></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import {PROJECT_IMAGE} from "../../api/endpoints";

    export default {
        name: 'SimpleTestVariantsArray',

        props: ['variants'],

        methods: {
            removeVariant(id) {
                for (const [index, value] of Object.entries(this.variants)) {
                    if (value.itemId === id) {
                        this.variants.splice(index, 1)
                        return
                    }
                }
            }
        },
        validations: {
            text: {
                required
            }
        }
    }
</script>
