<template>
    <div>
        <div class="articles_create-block" v-for="(variant, index) in variants" v-bind:key="variant.itemId" :id="'block-'+variant.itemId">
            <div class="articles_create-line"></div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="checkbox" v-model="variant.answer.type" :id="'type-'+variant.itemId" :checked="variant.answer.type" @change="hasActiveCheckbox(variant.itemId, index)">
                    <i></i>
                    <p>Готовый <br>ответ</p>
                </div>
                <div class="articles_create__item-content">
                    <div class="articles_create__ready_answer">
                        <p class="articles_create__ready_answer-letter">{{ variant.title }}</p>
                        <input type="text" name="text">
                        <div class="articles_create-checkbox">
                            <input type="checkbox" :id="'right_answer_' + variant.itemId" v-model="variant.answer.right">
                            <i></i>
                            <p>Правильный ответ</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="checkbox" v-bind:name="'answer_'+variant.itemId" @change="hasActiveCheckbox(variant.itemId, index)">
                    <i></i>
                    <p>Поле ввода ответа</p>
                </div>
                <div class="articles_create__item-content">
                    <textarea v-model.lazy="variant.variant"></textarea>
                </div>
            </div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="checkbox" v-bind:name="'media_'+variant.itemId" @change="hasActiveCheckbox(variant.itemId, index)">
                    <i></i>
                    <p>Медиа</p>
                </div>
                <div class="articles_create__item-content">
                    <div class="articles_create__media">
                        <SimpleTestMedia :media="variant.answer.media"></SimpleTestMedia>
                        <div class="articles_create__media-add">
                            <input type="file" name="file" :id="'file-'+variant.itemId" @change="addMedia(index, variant.itemId, $event)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div v-for="(variant, index) in variants" v-bind:key="variant.itemId">
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId" v-model.lazy="answer.type" value="variants">
                        <label v-bind:for="variant.itemId" class="custom-checkbox__label"></label>
                        Готова вiдповiдь
                    </div>
                </div>
                <div class="col-1">
                    {{ variant.title }}
                </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <input class="form-control" type="text" name="title" placeholder=""
                                   v-model.lazy="variant.variant">
                        </div>
                    </div>
                </div>
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="checkbox" :name="'right-answer' + variant.itemId"
                               v-bind:id="variant.itemId + variant.title" v-model.lazy="'right-answer' + variant.itemId"
                               :value="variant.variant">
                        <label v-bind:for="variant.itemId + variant.title" class="custom-checkbox__label"></label>
                    </div>
                    Правильна вiдповiдь
                </div>
            </div>
            <div class="row mb-3">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId + '-field'" v-model.lazy="answer.type" value="text">
                        <label v-bind:for="variant.itemId + '-field'" class="custom-checkbox__label"></label>
                    </div>
                    Поле для вiдповiдi
                </div>
                <div class="col-9">
                    <textarea class="form-control" rows="4" v-model.lazy="variant.variant"></textarea>
                </div>
            </div>
            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                               v-bind:id="variant.itemId + '-media'" v-model.lazy="answer.type" value="card">
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
        </div>-->
    </div>
</template>

<script>
import {required} from 'vuelidate/lib/validators'
import {PROJECT_IMAGE, TOKEN, ARTICLE_COVER} from "../../api/endpoints";
import SimpleTestMedia from "../fragmets/SimpleTestMedia"
import { getRandomId } from '../../utils'

export default {
    name: 'SimpleTestVariantsArray',
    components: {
        SimpleTestMedia
    },
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
        hasActiveCheckbox(id, index) {
            var sList = [];
            $('#block-'+id+' input[type=checkbox]').each(function () {
                if (this.id != 'right_answer_' + id) {
                    var sThisVal = (this.checked ? 1 : 0);
                    sList.push(sThisVal)
                }
            });

            if (!sList.includes(1)) {
                this.$set(this.variants[index].answer, 'type', true)
                $('#modalAlertTest').modal('show')
                $('#type-' + id).prop('checked', true)
            }
        },
        addMedia(index, id, event) {
            let imageForm = new FormData()
            imageForm.append('file', event.target.files[0])

            axios.post(
                ARTICLE_COVER + '/test',
                imageForm,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    params: {
                        access_token: TOKEN
                    },
                }
            ).then((file) => {
                /*this.name = event.target.files[0].name
                this.articles.imeges = file.data
                this.articles[0]['images'] = file.data.data.id*/
                let obj = {
                    itemId: getRandomId(),
                    file: file.data.data.id,
                    name: event.target.files[0].name,
                    data: file.data,
                    path: file.data.data.path
                };
                this.variants[index].answer.media.push(obj)
                $('#file-' + id).val(null);
            })
        }
    },
    validations: {
        text: {
            required
        }
    },
    mounted() {
        //if (this.$store.state.statusAddAnswer) {
            //this.addMedia()
            //this.$store.state.statusAddAnswer = false
        //}
    }
}
</script>

<style>
    .custom-checkbox__input {
        margin-right: 10px;
    }
</style>
