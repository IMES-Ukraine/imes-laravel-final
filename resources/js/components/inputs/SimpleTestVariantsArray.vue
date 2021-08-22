<template>
    <div>
        <div v-for="variant in variants" v-bind:key="variant.itemId">
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId" v-bind:id="variant.itemId" v-model.lazy="answer.type" value="variants">
                        <label v-bind:for="variant.itemId" class="custom-checkbox__label"></label>
                    </div>
                    Готовый ответ
                </div>
                <div class="col-1">
                    {{ variant.title }}
                </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <input class="form-control" type="text" name="title" placeholder="" v-model.lazy="variant.variant">
                        </div>
                    </div>
                </div>
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="checkbox" name="right-answer" v-bind:id="variant.itemId + variant.title" v-model.lazy="answer.correct" :value="variant.title" >
                        <label v-bind:for="variant.itemId + variant.title" class="custom-checkbox__label"></label>
                    </div>
                    Правильный ответ
                </div>
            </div>
            <div class="row mb-3">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId" v-bind:id="variant.itemId + '-field'" v-model.lazy="answer.type" value="text">
                        <label v-bind:for="variant.itemId + '-field'" class="custom-checkbox__label"></label>
                    </div>
                    Поле ввода ответа
                </div>
                <div class="col-9">
                    <textarea class="form-control" rows="4" v-model.lazy="variant.variant"></textarea>
                </div>
            </div>
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId" v-bind:id="variant.itemId + '-media'" v-model.lazy="answer.type" value="card">
                        <label v-bind:for="variant.itemId + '-media'" class="custom-checkbox__label"></label>
                    </div>
                    Медиа
                </div>
                <div class="col-4">
                    <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                            <span class="input-file-label">
                                <span class="icon-is-left icon-is-load-grey"></span>Выбрать
                            </span>
                        <input type="file" name="testCover" v-bind:variant_name="variant.itemId" v-bind:ref="variant.itemId + '-testCover'" class="input-file-hidden" v-on:change="handleFileChange">
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'

    export default {
        name: 'SimpleTestVariantsArray',

        props: ['variants', 'answer'],

        /*data() {
            return {
                correct: 1313
            }
        },*/

        methods: {
            handleFileChange( e){
                /*console.log( e)
                console.log( e.target.files[0])*/
                //this.$emit('file_сhange', e)
                let element = e.target
                this.$store.dispatch('addFiles', {file: element.files[0], id: element.getAttribute('variant_name')})
            }
        },
        validations: {
            text: {
                required
            }
        }
    }
</script>