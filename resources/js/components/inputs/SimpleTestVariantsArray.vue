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
                            <file-input :key="JSON.stringify(variant.media)"
                                        :value="variant.media[0]"
                                        @fileInput="variant.media[0] = $event"
                                        type="image"
                                        />
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
                this.test.question.variants[index].media[0] = file.data.data;
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
