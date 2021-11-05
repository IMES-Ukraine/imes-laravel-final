<template>
    <div>
        <div class="articles_create-box">
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Название</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__name-block">
                            <input type="text" name="title" id="question_title" v-model="question.title">
                            <div v-if="errors.title" class="errors">{{ errors.title }}</div>
                        </div>
                    </div>
                </div>
                <div class="articles_create__item half">
                    <p class="articles_create__item-title">Обложка</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__item-file width-auto buttonAddFile">
                            <input type="file" name="name" v-on:change="handleUpload">
                            <p><span data-placeholder="Загрузить"></span></p>
                            <button class="delete_file deleteFile"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Вопрос</p>
                    <div class="articles_create__item-content">
                        <textarea v-model="question.text"></textarea>
                        <div v-if="errors.text" class="errors">{{ errors.text }}</div>
                    </div>
                </div>

                <div class="articles_create__item half">
                    <div class="articles_create__item-title has_radio">
                        <input type="checkbox" name="checkbox_file" v-model="isCheckedFile">
                        <i></i>
                        <p>Изображения</p>
                    </div>
                    <div class="articles_create__item-content">
                        <div class="articles_create__item-file width-auto buttonAddFile" :class = "(!isCheckedFile)?'fileDisabled':''">
                            <input type="file" name="name" :disabled="!isCheckedFile">
                            <p><span data-placeholder="Загрузить"></span></p>
                            <button class="delete_file deleteFile" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="articles_create__item half"></div>
                <div class="articles_create__item half">
                    <div class="articles_create__item-title has_radio">
                        <input type="checkbox" name="checkbox_video" v-model="isCheckedVideo" >
                        <i></i>
                        <p>Видео</p>
                    </div>
                    <div class="articles_create__item-content">
                        <div class="articles_create__item-file width-auto buttonAddFile" :class = "(!isCheckedVideo)?'fileDisabled':''">
                            <input type="file" name="name" :disabled="!isCheckedVideo">
                            <p><span data-placeholder="Загрузить"></span></p>
                            <button class="delete_file deleteFile" type="button"></button>
                        </div>
                    </div>
                </div>
            </div>
            <SimpleTestVariants :variants="variants"></SimpleTestVariants>

            <button class="articles_create-submit button-border mtb20" type="button" @click="addAnswerTest">добавить ответ</button>
            <div class="articles_create-line"></div>

            <div class="articles_create-block">
                <div class="articles_create__item">
                    <div class="articles_create__item-title has_radio">
                        <input type="radio" name="title_radio">
                        <i></i>
                        <p>Изучить</p>
                    </div>
                    <div class="articles_create__item-content">
                        <div class="articles_create__name-block">
                            <input type="text" name="name" placeholder="http//">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--<div class="card-body">

            <fragment-form-text>
                <input class="form-control" type="text" name="title" >
            </fragment-form-text>

            <template v-if="question.isComplex"></template>

            <template v-else>
                <div class="row mb-3">
                    <div class="article-edit__text col-3">
                        Узгодження
                    </div>
                    <div class="col-9">
                        <textarea class="form-control" rows="4" v-model="question.agreement"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="article-edit__text col-3">
                        Опис
                    </div>
                    <div class="col-9">
                        <textarea class="form-control" rows="4" v-model="question.description"></textarea>
                    </div>
                </div>
            </template>


            <div class="row mb-4">
                <template v-if="question.isComplex">
                    <div class="article-edit__text col-3">
                        Вiдео
                    </div>
                    <div class="col-4">
                        <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                          <span class="input-file-label">
                            <span class="icon-is-left icon-is-load-grey"></span><span v-if="question.media.video">{{ question.media.video.file_name }}</span><span v-else>Завантажити</span>
                          </span>
                            <input type="file" name="testCover" ref="testCover" v-on:change="handleUpload" img_type="video" class="input-file-hidden">
                        </label>
                    </div>
                </template>
                <template v-else>
                    <div class="article-edit__text col-3">
                        Обложка
                    </div>
                    <div class="col-4">
                        <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                          <span class="input-file-label">
                            <span class="icon-is-left icon-is-load-grey"></span><span v-if="question.media.cover">{{ question.media.cover.file_name }}</span><span v-else>Завантажити</span>
                          </span>
                            <input type="file" name="testCover" ref="testCover" v-on:change="handleUpload" img_type="cover" class="input-file-hidden">
                        </label>
                    </div>
                </template>
            </div>
            <div class="row mb-3">
                <div class="article-edit__text col-3">
                    Питання
                </div>
                <div class="col-9">
                    <textarea class="form-control" name="text" rows="4" ></textarea>
                </div>
            </div>

            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    Категории
                </div>
                <div class="col-4">
                    <label class="btn btn-outline-second btn-centered-content is-small">
                        <select class="form-control" v-model="category">
                            <option v-for="item in lists.categories" :value="item.id" :key="item.id">
                                {{ item.name }}
                            </option>
                        </select>
                    </label>
                </div>

            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-outline-primary" @click="addAnswerTest">
                        Додати вiдповiдь
                    </button>
                </div>
            </div>
            <br>

            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    <div class="article-edit__btn-pos custom-checkbox">
                        <input class="custom-checkbox__input" type="checkbox" name="is-article-link" id="is-article-link">
                        <label for="is-article-link" class="custom-checkbox__label"></label>
                    </div>
                    Вивчити:
                </div>
                <div class="col-9">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="input-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text is-text"></span>
                                </div>
                                <input type="text" class="form-control input-is-form is-text" placeholder="" aria-label="теги" v-model="question.link" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</template>
<script>
import {required} from 'vuelidate/lib/validators'

import SimpleTestVariants from './../inputs/SimpleTestVariantsArray.vue';
import VContent from "../templates/Content"
import { getRandomId, alphabet } from '../../utils'
import FragmentFormText from "./text";
import {PROJECT_IMAGE} from "../../api/endpoints";
import ProjectMixin from "../../ProjectMixin";

export default {
    name: 'TestQuestion',
    props: ['title', 'text', 'link', 'button', 'variants', 'question', 'errors'],
    mixins: [ProjectMixin],
    components: {
        FragmentFormText,
        //SimpleTestVariant,
        SimpleTestVariants,
        VContent
    },

    data() {
        return {
            files: {},
            cover: null,
            video: null,

            isCheckedFile: false,
            isCheckedVideo: false
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
    computed: {
    },
    methods: {
        /**
         * Adding one more answer variant to question
         */
        addAnswerTest() {
            let title = alphabet[this.variants.length];
            let newItem = {... this.getNewVariant(title) };
            this.variants.push(newItem);
        },
        /**
         * Handle changing of file input (cover, video, variants)
         * @param event
         */
        handleUpload( event) {

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
                //this[type] = file.data
                this.question.media[type] = file.data
                //this.options.files[input.dataset.ref] = file.data
            })
        },
        checkboxChange() {
            if (this.isCheckedFile) {
                this.isCheckedFile = false
            } else {
                this.isCheckedFile = true
            }
        },
        checkboxChangeVideo() {
            if (this.isCheckedVideo) {
                this.isCheckedVideo = false
            } else {
                this.isCheckedVideo = true
            }
        }
    },
    mounted() {
        if (! this.variants.length) {
            this.addAnswerTest();
            this.addAnswerTest();
        }
    }
}
</script>
