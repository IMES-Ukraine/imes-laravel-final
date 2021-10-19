<template>
    <div>
        <div v-for="(variant, index) in complex" v-bind:key="variant.itemId">
            <div class="row mb-3">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId + '-field'" value="text">
                        <label v-bind:for="variant.itemId + '-field'" class="custom-checkbox__label"></label>
                    </div>
                    Заголовок
                </div>
                <div class="col-9">
                    <input class="form-control" rows="4" v-model.lazy="variant.variant" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId + '-field'" value="text">
                        <label v-bind:for="variant.itemId + '-field'" class="custom-checkbox__label"></label>
                    </div>
                    Текст
                </div>
                <div class="col-9">
                    <textarea class="form-control" rows="4" v-model.lazy="variant.variant"></textarea>
                </div>
            </div>
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId + '-media'" value="card">
                        <label v-bind:for="variant.itemId + '-media'" class="custom-checkbox__label"></label>
                    </div>
                    IMG
                </div>
                <div class="col-4">
                    <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                            <span class="input-file-label">
                                <span class="icon-is-left icon-is-load-grey"></span><span v-if="variant.file">{{
                                    variant.file.file_name
                                }}</span><span v-else>Завантажити</span>
                            </span>

                        <input type="file" name="testCover" img_type="button" class="input-file-hidden"
                               v-on:change="handleUpload(index, $event)">
                    </label>
                </div>
            </div>
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId + '-media'" value="card">
                        <label v-bind:for="variant.itemId + '-media'" class="custom-checkbox__label"></label>
                    </div>
                    Медiа
                </div>
                <div class="col-4">
                    <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                            <span class="input-file-label">
                                <span class="icon-is-left icon-is-load-grey"></span><span v-if="variant.file">{{
                                    variant.file.file_name
                                }}</span><span v-else>Завантажити</span>
                            </span>

                        <input type="file" name="testCover" img_type="button" class="input-file-hidden"
                               v-on:change="handleUpload(index, $event)">
                    </label>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-3">
                    <span>Количество балов</span>
                </div>
                <div class="col-9">
                    <input class="form-control" rows="4" v-model.lazy="variant.variant" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import {PROJECT_IMAGE} from "../../api/endpoints";

    export default {
        name: 'ComplexTestVariantsArray',

        props: ['complex'],

        methods: {

            /**
             * Handle changing of file input (cover, video, variants)
             * @param event
             */
            handleUpload(index, event) {

                let imageForm = new FormData();
                let input = event.target
                let type = input.getAttribute('img_type')


                imageForm.append('file', input.files[0]);

                this.$post(PROJECT_IMAGE + type,
                    imageForm,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((file) => {
                    this.variants[index].file = file.data
                })
            },
        },
        validations: {
            text: {
                required
            }
        }
    }
</script>

<style>
    .custom-checkbox__input {
        margin-right: 10px;
    }
</style>
