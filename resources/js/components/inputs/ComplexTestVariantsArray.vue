<template>
    <div>
        <div class="articles_create-block" v-for="(variant, index) in question.variants" v-bind:key="variant.itemId"
             :id="'block-'+index+'-'+variant.itemId">
            <div class="articles_create-line"></div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'type-'+variant.itemId" v-model="localType" @click="$emit('input', $event.target.value)" value="variants">
                    <i></i>
                    <p>Готовый <br>ответ</p>
                </div>
                <div class="articles_create__item-content">
                    <div v-if="!question.isText" class="articles_create__ready_answer">
                        <p class="articles_create__ready_answer-letter">{{ variant.variant }}</p>
                        <input v-if="localType === 'variants'" type="text" v-model="variant.title">

                        <div class="articles_create-checkbox">
                            <input type="checkbox" :id="'right_answer_' + variant.itemId"
                                   v-model="variant.right"
                                   @click="setCorrect(variant.variant, variant.right)">
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
                    <input type="radio" :name="'type-'+variant.itemId" @click="$emit('input', $event.target.value)" v-model="localType" value="text">
                    <i></i>
                    <p>Поле ввода ответа</p>
                </div>
                <div  v-if="question.isText" class="articles_create__item-content">
                    <textarea v-model.lazy="variant.description"></textarea>
                </div>
            </div>
            <div class="articles_create__item">
                <div class="articles_create__item-title has_radio">
                    <input type="radio" :name="'type-'+variant.itemId" @click="$emit('input', $event.target.value)" v-model="localType" value="media">
                    <i></i>
                    <p>Медиа</p>
                </div>
                <div v-if="localType === 'media'" class="articles_create__item-content">
                    <div class="articles_create__media">
                        <div class="articles_create__media-add">
                            <file-input :key="JSON.stringify(variant.media)"
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
import {required} from 'vuelidate/lib/validators'
import {PROJECT_IMAGE, TOKEN} from "../../api/endpoints"
import ProjectMixin from "../../ProjectMixin";
import FileInput from "./file-input";

export default {
    name: 'ComplexTestVariantsArray',
    mixins: [ProjectMixin],
    components: {
        FileInput
    },
    props: ['question', 'toValidate', 'errors', 'i'],
    data() {
        return {
            localType: 'variants',
            errKey: Math.random(),
            errorsLocal: {
                variants: [],
                media: [],
                correct: ''
            },
            haveErrors: false
        }
    },
    mounted() {
        this.localType = this.question.type;
    },
    watch: {
        localType() {
            this.question.type = this.localType;
            this.errorsLocal = [];
            if (this.localType === 'text') {
                this.question.isText = true;
                this.typeComplexAnswerText(this.question.variants);
            } else {
                this.question.isText = false;
                this.typeComplexAnswerOther(this.question.variants);
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
        setMedia(media, index){
            this.question.variants[index].media  = [];
            this.question.variants[index].media.push(media);
        }

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
