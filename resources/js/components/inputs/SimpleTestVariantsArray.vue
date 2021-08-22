<template>
    <div>
        <div v-for="(variant, index) in variants" v-bind:key="variant.itemId">
            <div className="row mb-4">
                <div className="article-edit__text col-3">
                    <div className="article-edit__btn-pos custom-checkbox">
                        <input className="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId" v-model.lazy="answer.type" value="variants">
                        <label v-bind:for="variant.itemId" className="custom-checkbox__label"></label>
                    </div>
                    Готова вiдповiдь
                </div>
                <div className="col-1">
                    {{ variant.title }}
                </div>
                <div className="col-5">
                    <div className="row">
                        <div className="col-12 mb-2">
                            <input className="form-control" type="text" name="title" placeholder=""
                                   v-model.lazy="variant.variant">
                        </div>
                    </div>
                </div>
                <div className="article-edit__text col-3">
                    <div className="article-edit__btn-pos custom-checkbox">
                        <input className="custom-checkbox__input" type="checkbox" name="right-answer"
                               v-bind:id="variant.itemId + variant.title" v-model.lazy="answer.correct"
                               :value="variant.title">
                        <label v-bind:for="variant.itemId + variant.title" className="custom-checkbox__label"></label>
                    </div>
                    Правильна вiдповiдь
                </div>
            </div>
            <div className="row mb-3">
                <div className="article-edit__text col-3">
                    <div className="article-edit__btn-pos custom-checkbox">
                        <input className="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId + '-field'" v-model.lazy="answer.type" value="text">
                        <label v-bind:for="variant.itemId + '-field'" className="custom-checkbox__label"></label>
                    </div>
                    Поле для вiдповiдi
                </div>
                <div className="col-9">
                    <textarea className="form-control" rows="4" v-model.lazy="variant.variant"></textarea>
                </div>
            </div>
            <div className="row mb-4">
                <div className="article-edit__text col-3">
                    <div className="article-edit__btn-pos custom-checkbox">
                        <input className="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId + '-media'" v-model.lazy="answer.type" value="card">
                        <label v-bind:for="variant.itemId + '-media'" className="custom-checkbox__label"></label>
                    </div>
                    Медiа
                </div>
                <div className="col-4">
                    <label className="btn btn-outline-second btn-centered-content upload-cover is-small">
                            <span className="input-file-label">
                                <span className="icon-is-left icon-is-load-grey"></span><span v-if="variant.file">{{
                                    variant.file.file_name
                                }}</span><span v-else>Завантажити</span>
                            </span>

                        <input type="file" name="testCover" img_type="button" className="input-file-hidden"
                               v-on:change="handleUpload(index, $event)">
                    </label>
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

    props: ['variants', 'answer'],

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
