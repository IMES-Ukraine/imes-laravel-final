<template>
    <div>
        <div class="articles_create-block" v-for="(variant, index) in test.question.variants"
             :id="'block-'+index">
            <div class="articles_create-line"></div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio"
                           :name="'answer_'+variant.itemId"
                           value="variants"
                           @click="$emit('input', $event.target.value)"
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
                           @click="$emit('input', $event.target.value)"
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
                           @click="$emit('input', $event.target.value)"
                           :name="'answer_'+variant.itemId"/>
                    <i></i>
                    <p>Медиа</p>
                </div>
                <div v-if="localType === 'media'" class="articles_create__item-content">
                    <div class="articles_create__media" :key="JSON.stringify(variant.media)">
                        <div class="articles_create__media-add">
                            <file-input :key="JSON.stringify(test.question.variants[index].media)"
                                        :value="variant.media[0]"
                                        @fileInput="setMedia($event, index)"
                                        type="image"
                                        />
                        </div>
                    </div>
                </div>
                <div v-if="errorsLocal.media" class="errors">{{ errorsLocal.media[index] }}</div>
                <div v-if="errorsLocal.media" class="h20 mb20"></div>
            </div>
        </div>

    </div>
</template>

<script>
import {PROJECT_IMAGE, TOKEN} from "../../api/endpoints";
import ProjectMixin from "../../ProjectMixin";
import FileInput from "./file-input";

export default {
    name: 'SimpleTestVariantsArray',
    mixins: [ProjectMixin],
    components: {
        FileInput
    },
    props: ['test', 'errors', 'toValidate'],
    data() {
        return {
            localType: 'variants',
            question: this.test.question,
        }
    },
    computed: {
      errorsLocal: {
          get: function() { return this.errors || {}},
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
            this.question.type = this.localType;
            if (this.localType === 'text') {
                this.isText = true;
                this.typeAnswerText(this.test.question.variants);
            } else {
                this.isText = false;
                this.typeAnswerOther(this.test.question.variants);
            }
        },
        toValidate() {
            this.haveErrors = false;
            if (this.question.type === 'variants') {
                this.errorsLocal.variants = [];
                for (const [index, value] of Object.entries(this.question.variants)) {

                    if (!value.title || !value.title.trim()) {
                        this.errorsLocal.variants[index] = 'Текст ответа обязателен';
                        this.haveErrors = true;
                    }
                }
            }
            else if (this.question.type === 'media') {
                this.errorsLocal.media = [];
                for (const [index, value] of Object.entries(this.question.variants)) {
                    if (!value.media.length || !value.media[0]) {
                        this.errorsLocal.media[index] = 'Изображение для  ответа обязательно';
                        this.haveErrors = true;
                    }
                }
            }
            this.errorsLocal.correct = '';
            if ((this.question.type !== 'text') && !this.question.correct.length) {
                this.errorsLocal.correct = this.notCorrectAnswer;
                this.haveErrors = true;
            }
            if (this.haveErrors){
                this.$store.commit('setTestError', true);
            }
            this.errKey = Math.random();
        }
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
        setMedia(media, index){
            this.test.question.variants[index].media  = [];
            this.test.question.variants[index].media.push(media);
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
