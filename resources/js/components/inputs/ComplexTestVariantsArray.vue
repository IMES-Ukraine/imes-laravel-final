<template>
    <div>
        <div class="articles_create-block" v-for="(variant, index) in question.variants" v-bind:key="variant.itemId"
             :id="'block-'+index">
            <div class="articles_create-line"></div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'type-'+variant.itemId" v-model="localType" value="variants">
                    <i></i>
                    <p>Готовый <br>ответ</p>
                </div>
                <div class="articles_create__item-content">
                    <div v-if="!isText" class="articles_create__ready_answer">
                        <p class="articles_create__ready_answer-letter">{{ variant.title }}</p>
                        <input v-if="localType === 'variants'" type="text" name="text" v-model="variant.text">
                        <div class="articles_create-checkbox">
                            <input type="checkbox" :id="'right_answer_' + variant.itemId"
                                   v-model="variant.right"
                                   @click="setCorrect(variant.title, variant.right)">
                            <i></i>
                            <p>Правильный ответ</p>
                        </div>
                    </div>
                </div>
                <p :key="errKey" class="errors" >{{ errorsLocal.correct }}</p>
            </div>
            <div v-if="errorsLocal.variants" class="errors">{{ errorsLocal.variants[index] }}</div>
            <div v-if="errorsLocal.variants" class="h20 mb20"></div>

            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'type-'+variant.itemId" v-model="localType" value="text">
                    <i></i>
                    <p>Поле ввода ответа</p>
                </div>
                <div  v-if="isText" class="articles_create__item-content">
                    <textarea v-model.lazy="variant.variant"></textarea>
                </div>
            </div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'type-'+variant.itemId" v-model="localType" value="media">
                    <i></i>
                    <p>Медиа</p>
                </div>
                <div v-if="localType === 'media'" class="articles_create__item-content">
                    <div class="articles_create__media">
                        <div v-for="file in variant.media" v-bind:key="file.itemId" class="articles_create__media-item">
                            <div class="articles_create__media-img">
                                <img :src="file.path" alt="">
                            </div>
                        </div>
                        <div class="articles_create__media-add">
                            <input type="file" name="file" :id="'file-'+variant.itemId"
                                   @change="addMedia(index, variant.itemId, $event)">
                        </div>
                    </div>
                </div>
                <div v-if="errorsLocal.media" class="errors">{{ errorsLocal.media[index] }}</div>
                <div v-if="errorsLocal.media" class="h20 mb20"></div>
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
import {PROJECT_IMAGE, TOKEN} from "../../api/endpoints"
import ProjectMixin from "../../ProjectMixin";

export default {
    name: 'ComplexTestVariantsArray',
    mixins: [ProjectMixin],
    props: ['question', 'toValidate', 'errors', 'i'],
    data() {
        return {
            localType: 'variants',
            errKey: Math.random(),
        }
    },
    mounted() {
        this.localType = this.question.type;
    },
    computed: {
        errorsLocal: {
            get: function () {
                return this.errors;
            },
            set: function (newValue) {
                this.$store.commit('setErrors', newValue);
            }
        }
    },
    watch: {
        localType() {
            this.question.type = this.localType;
            this.errorsLocal = [];
            if (this.localType === 'text') {
                this.isText = true;
                this.typeAnswerText(this.question.variants);
            } else {
                this.isText = false;
                this.typeAnswerOther(this.question.variants);
            }
        },
        toValidate() {
            if (this.question.type === 'variants') {
                this.errorsLocal.variants = [];
                for (const [index, value] of Object.entries(this.question.variants)) {
                    if (!value.text || !value.text.trim()) {
                        this.errorsLocal.variants[index] = 'Текст ответа обязателен';
                    }
                }
            }
            else if (this.question.type === 'media') {
                this.errorsLocal.media = [];
                for (const [index, value] of Object.entries(this.question.variants)) {
                    if (!value.media.length) {
                        this.errorsLocal.media[index] = 'Изображение для  ответа обязательно';
                    }
                }
            }
            this.errorsLocal.correct = '';
            if ((this.question.type !== 'text') && !this.question.correct.length) {
                this.errorsLocal.correct = this.notCorrectAnswer;
            }
            if (this.errorsLocal.length){
                this.$store.dispatch('setTestError', true);
            }
            else {
                this.$store.dispatch('setTestError', false);
            }
            this.errKey = Math.random();
        }
    },
    methods: {
        setCorrect(id, data) {
            if (!data) {
                this.question.correct.push(id);
                this.errorsLocal.correct = '';
            } else {
                this.question.correct = this.question.correct.filter((item) => {
                    return item !== id;
                });
                if (!this.question.correct.length) {
                    this.errorsLocal.correct = this.notCorrectAnswer;
                    this.$store.dispatch('setTestError', true);
                }
            }
        },

      addMedia(index, id, event) {
        let imageForm = new FormData()
        imageForm.append('file', event.target.files[0])

        axios.post(
            PROJECT_IMAGE + 'variant-' + index +'/test',
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
          let q = [...this.question.variants[index].media];
          q.push(file.data.data);
          this.question.variants[index].media = [...q];
          $('#file-' + id).val(null);
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
