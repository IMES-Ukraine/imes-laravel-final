<template>
    <div>
        <div class="articles_create__item" id="survey-test" v-for="(variant, index) in variants" v-bind:key="variant.itemId">
            <p class="articles_create__item-title">Выбор</p>
            <div class="articles_create__item-content">
                <div class="articles_create__ready_answer full_width">
                    <p class="articles_create__ready_answer-letter">{{ variant.variant }}</p>
                    <input type="text" name="title" placeholder="" :id="'variant-' + variant.variant" v-model.lazy="variant.title">
                    <button class="articles_create__ready_answer-delete" type="button" @click="removeVariant(variant.itemId)"></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'

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
