<template>
    <div>
        <div class="articles_create-box">
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Название</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__name-block">
                            <input type="text" name="title" id="question_title" v-model="test.title">
                            <div v-if="errors.testTitle" class="errors">{{ errors.testTitle }}</div>
                        </div>
                    </div>
                </div>
                <div class="articles_create__item half">
                    <p class="articles_create__item-title">Обложка</p>
                    <file-input :key="test.title + '-cover'"
                                :value="test.cover"
                                :error="coverError"
                                @fileInput="test.cover = $event"
                                type="image"/>
                </div>
            </div>
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Вопрос</p>
                    <div class="articles_create__item-content">
                        <textarea v-model="test.text"></textarea>
                        <div v-if="errors.testText" class="errors">{{ errors.testText }}</div>
                    </div>
                </div>

                <div class="articles_create__item half">
                    <div class="articles_create__item-title has_radio">
                        <input type="radio" name="attach" value="image" v-model="test.question.fileType" @click="test.question.file = null">
                        <i></i>
                        <p>Изображение</p>
                    </div>
                    <div class="articles_create__item-content" style="margin-top: 35px;">
                        <file-input :key="JSON.stringify(test.question.file)"
                                    :value="test.question.file"
                                    @fileInput="test.question.file = $event"
                                    :type="test.question.fileType"
                                    :disabled="!test.question.fileType"/>
                    </div>
                </div>
                <div class="articles_create__item half"></div>
                <div class="articles_create__item half" style="margin-top: -35px;">
                    <div class="articles_create__item-title has_radio">
                        <input type="radio" name="attach" value="video" v-model="test.question.fileType" @click="test.question.file = null">
                        <i></i>
                        <p>Видео</p>
                    </div>
                </div>
            </div>
            <SimpleTestVariants :test.sync="test"
                                :errors.sync="errors"
                                @input="getRadioType" />

            <button class="articles_create-submit button-border mtb20" v-if="isCheckedVariant" type="button"
                    @click="addAnswerTest(test.question.variants.length)">добавить ответ
            </button>
            <div class="articles_create-line"></div>

            <div class="articles_create-block">
                <div class="articles_create__item">
                    <div class="articles_create__item-title has_radio">
                        <input type="checkbox" name="title_radio" v-model="toLearn">
                        <i></i>
                        <p>Изучить</p>
                    </div>
                    <div v-if="toLearn" class="articles_create__item-content">
                        <div class="articles_create__name-block">
                            <input type="text" name="name" placeholder="http//" v-model="test.external_learn_url">
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <button class="articles_create-submit button-gradient" type="button"
              @click="$emit('input', test)">сохранить
      </button>
    </div>
</template>
<script>
import {required} from 'vuelidate/lib/validators'

import SimpleTestVariants from './../inputs/SimpleTestVariantsArray.vue';
import VContent from "../templates/Content"
import FragmentFormText from "./text";
import ProjectMixin from "../../ProjectMixin";
import FileInput from "../inputs/file-input";

export default {
    name: 'TestQuestion',
    props: ['test', 'errors', 'toValidate'],
    mixins: [ProjectMixin],
    components: {
        FragmentFormText,
        SimpleTestVariants,
        FileInput,
        VContent
    },

    data() {
        return {
            files: {},
            video: null,

            toLearn: !!this.test.external_learn_url,


            isCheckedFile: false,
            isCheckedVideo: false,
            isCheckedVariant: true,
            coverError: ''
        }
    },
    validations: {
        text: {
            required
        },
        link: {
            required
        },
        button: {
            required
        },
    },
    computed: {},
    methods: {
        getRadioType (value) {
            this.isCheckedVariant = true;

            if (value == 'text') {
                this.isCheckedVariant = false;
            }
        }
    },
    mounted() {
        if (!this.test.question.variants.length) {
            this.addAnswerTest(0);
            this.addAnswerTest(1);
        }
    }
}
</script>
