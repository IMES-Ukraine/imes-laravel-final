<template>
    <div>
        <div class="card-body">

            <fragment-form-text>
                <input class="form-control" type="text" name="title" id="question_title" v-model="question.title">
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
                    <textarea class="form-control" rows="4" v-model="question.text"></textarea>
                    <!--<div class="error" v-if="$v.text.$error">
                        Текст вопроса обязателен
                    </div>-->
                </div>
            </div>

            <SimpleTestVariants v-bind:answer="answer" v-bind:variants="variants"></SimpleTestVariants>

            <div class="row mb-4">
                <div class="article-edit__text col-3">
                    Категории
                </div>
                <div class="col-4">
                    <label class="btn btn-outline-second btn-centered-content is-small">
                        <select class="form-control" v-model="category">
                            <option v-for="item in categoryList" :value="item.id" :key="item.id">
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
        </div>
    </div>
</template>
<script>
import {required} from 'vuelidate/lib/validators'
//import SimpleTestVariant from './../components/inputs/SimpleTestVariant.vue';
import SimpleTestVariants from './../inputs/SimpleTestVariantsArray.vue';
import VContent from "../templates/Content"
import { getRandomId } from '../../utils'
import FragmentFormText from "./text";
import {PROJECT_IMAGE} from "../../api/endpoints";
//import CreateProjectForm from "./CreateProjectForm";
//import { mapActions, mapState } from 'vuex'
export default {
    name: 'TestQuestion',
    props: ['title', 'text', 'link', 'button', 'variants', 'answer', 'question'],
    components: {
        FragmentFormText,
        //SimpleTestVariant,
        SimpleTestVariants,
        VContent
    },

    data() {
        return {
            //tests: this.$store.state.tests,
            coverRandomId: getRandomId(),
            files: {},
            cover: null,
            video: null,
            categoryList: [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
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
        hasDescription: function() {
            return false;
        }
    },
    methods: {
        /**
         * Adding one more answer variant to question
         */
        addAnswerTest() {
            const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ']
            let length = this.variants.length
            let obj = {
                itemId: getRandomId(),
                title: alphabet[length],
                variant: '',
                isCorrect: false,
            };
            this.variants.push(obj)
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
    },
    mounted() {
        if (this.$store.state.statusAddAnswer) {
            this.addAnswerTest()
            this.$store.state.statusAddAnswer = false
        }
    }
}
</script>
