<template>
    <div>
        <div class="articles_create-block" v-for="(variant, index) in test.question.variants" :key="variant.itemId"
             :id="'block-'+index">
            <div class="articles_create-line"></div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio"
                           :name="'answer_'+variant.itemId"
                           value="variants"
                           v-model="localType"/>
                    <i></i>
                    <p>Готовый <br>ответ</p>
                </div>
                <div class="articles_create__item-content">
                    <div v-if="!isText" class="articles_create__ready_answer">

                        <p class="articles_create__ready_answer-letter">{{ variant.variant }}</p>
                        <input v-if="localType === 'variants'" type="text" v-model="variant.title">

                        <div class="articles_create-checkbox">
                            <input type="checkbox" v-model="variant.right"
                                   @change="setCorrect(variant.variant, variant.right)"/>
                            <i></i>
                            <p>Правильный ответ</p>
                        </div>
                    </div>
                </div>
                <p class="errors">{{ errorsLocal.correct }}</p>
            </div>
            <div v-if="errorsLocal.title" class="errors">{{ errorsLocal.title[index] }}</div>
            <div v-if="errorsLocal.title" class="h20 mb20"></div>

            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'answer_'+variant.itemId"
                           value="text"
                           v-model="localType"
                    />
                    <i></i>
                    <p>Поле ввода ответа</p>
                </div>
                <div v-if="isText" class="articles_create__item-content">
                    <textarea v-model.lazy="variant.description"
                              placeholder="Текст правильного ответа для проверки"></textarea>
                </div>
            </div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio"
                           v-model="localType"
                           value="media"
                           :name="'answer_'+variant.itemId"/>
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
                                   :disabled=" localType != 'media'"
                                   @change="addMedia(index, variant.itemId, $event)">
                        </div>
                    </div>
                </div>
                <div v-if="errorsLocal.media" class="errors">{{ errorsLocal.media[index] }}</div>
                <div v-if="errorsLocal.media" class="h20 mb20"></div>
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
import {PROJECT_IMAGE, TOKEN} from "../../api/endpoints";
import store from "../../store";
import ProjectMixin from "../../ProjectMixin";

export default {
    name: 'SimpleTestVariantsArray',
    mixins: [ProjectMixin],
    props: ['test', 'errors'],
    data() {
        return {
            localType: 'variants',
        }
    },
    computed: {
      errorsLocal: {
          get: function() { return this.errors },
          set: function (newValue) {
              this.$store.commit('setErrors', newValue);
          }
      }
    },
    mounted() {
        this.localType = this.test.question.type;
    },
    watch: {
        localType() {
            this.errorsLocal = [];
            this.test.question.type = this.localType;
            if (this.localType === 'text') {
                this.isText = true;
                this.typeAnswerText(this.test.question.variants);
            } else {
                this.isText = false;
                this.typeAnswerOther(this.test.question.variants);
            }
        },
    },
    methods: {

        setCorrect(id, data) {
            if (data) {
                this.test.question.correct.push(id);
                this.errorsLocal.correct = '';
            } else {
                let correct = [...this.test.question.correct];
                this.test.question.correct = correct.filter((item) => {
                    return item !== id;
                });
                if ((this.test.question.type !== 'text') && !this.test.question.correct.length) {
                    this.errorsLocal.correct = this.notCorrectAnswer;
                }
            }
        },


        addMedia(index, id, event) {
            let imageForm = new FormData()
            imageForm.append('file', event.target.files[0])

            axios.post(
                PROJECT_IMAGE + 'img/test',
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
                let q = [...this.test.question.variants[index].media];
                q.push(file.data.data);
                this.test.question.variants[index].media = [...q];
                $('#file-' + id).val(null);
            })
        }
    },
}
</script>

<style>
.hide {
    display: none;
}

.custom-checkbox__input {
    margin-right: 10px;
}
</style>
