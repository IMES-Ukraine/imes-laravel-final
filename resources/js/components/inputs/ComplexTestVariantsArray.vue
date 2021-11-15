<template>
    <div>
        <div class="articles_create-block" v-for="(variant, index) in question.variants" v-bind:key="variant.itemId"
             :id="'block-'+variant.itemId">
            <div class="articles_create-line"></div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'type-'+variant.itemId" v-model="localType" value="variants">
                    <i></i>
                    <p>Готовый <br>ответ</p>
                </div>
                <div class="articles_create__item-content">
                    <div class="articles_create__ready_answer">
                        <p class="articles_create__ready_answer-letter">{{ variant.title }}</p>
                        <input type="text" name="text" v-model="variant.text">
                        <div class="articles_create-checkbox">
                            <input type="checkbox" :id="'right_answer_' + variant.itemId"
                                   v-model="variant.right"
                                   @click="setCorrect(variant.title, variant.right)">
                            <i></i>
                            <p>Правильный ответ</p>
                        </div>
                    </div>
                </div>
                <p :key="errKey" class="errors" >{{ errors.correct }}</p>
            </div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'type-'+variant.itemId" v-model="localType" value="text">
                    <i></i>
                    <p>Поле ввода ответа</p>
                </div>
                <div class="articles_create__item-content">
                    <textarea v-model.lazy="variant.variant"></textarea>
                </div>
            </div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'type-'+variant.itemId" v-model="localType" value="media">
                    <i></i>
                    <p>Медиа</p>
                </div>
                <div class="articles_create__item-content">
                    <div class="articles_create__media">
                        <SimpleTestMedia :media="variant.media"></SimpleTestMedia>
                        <div class="articles_create__media-add">
                            <input type="file" name="file" :id="'file-'+variant.itemId"
                                   @change="addMedia(index, variant.itemId, $event)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--<div v-for="(variant, index) in complex" v-bind:key="variant.itemId">
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
        </div>-->
    </div>
</template>

<script>
import {required} from 'vuelidate/lib/validators'
import {ARTICLE_COVER, PROJECT_IMAGE, TOKEN} from "../../api/endpoints"
import SimpleTestMedia from "../fragmets/SimpleTestMedia"
import {getRandomId} from "../../utils";

export default {
    name: 'ComplexTestVariantsArray',
    components: {
        SimpleTestMedia
    },
    props: ['question', 'toValidate'],
    data() {
        return {
            localType: 'variants',
            errKey: Math.random(),
            errors: {}
        }
    },
    mounted() {
        this.localType = this.question.type;
    },
    watch: {
        localType() {
            this.question.type = this.localType;
        },
        toValidate() {
            this.errors.correct = '';
            console.log('answer correct: ', this.question.answer.correct, this.question.answer.correct.length);
            if (!this.question.answer.correct.length) {
                this.errors.correct = 'Має бути вказана принаймні одна правильна відповідь';
                this.$store.dispatch('setTestError', true);
            }
            this.errKey = Math.random();
        }
    },
    methods: {
        setCorrect(id, data) {
            if (!data) {
                this.question.correct.push(id);
                this.errors.correct = '';
            } else {
                this.question.correct = this.question.correct.filter((item) => {
                    return item !== id;
                });
                if (!this.question.correct.length) {
                    this.errors.correct = 'Має бути вказана принаймні одна правильна відповідь';
                    this.$store.dispatch('setTestError', true);
                }
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

          let obj = {
            itemId: 'media-' + getRandomId(),
            file: file.data.data.id,
            name: event.target.files[0].name,
            data: file.data,
            path: file.data.data.path
          };
          let q = [...this.question.variants[index].media];
          q.push(obj);
          this.question.variants[index].media = [...q];
          $('#file-' + id).val(null);
        })
      },

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
                this.question.variants[index].file = file.data
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
