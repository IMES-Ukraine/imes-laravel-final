<template>
    <div>
        <div class="articles_create-box">
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Название</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__name-block">
                            <input type="text" name="title" id="question_title" v-model="question.title">
                            <div v-if="errors.text" class="errors">{{ errors.text }}</div>
                        </div>
                    </div>
                </div>
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Категория</p>
                    <div class="articles_create__item-content">
                        <select class="my-ui-select articles_create-select">
                            <option value="1">Категория 1</option>
                            <option value="2">Категория 2</option>
                            <option value="3">Категория 3</option>
                            <option value="4">Категория 4</option>
                        </select>
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
                    <p class="articles_create__item-title">Описание</p>
                    <div class="articles_create__item-content">
                        <textarea v-model="question.text"></textarea>
                        <div v-if="errors.text" class="errors">{{ errors.text }}</div>
                    </div>
                </div>
            </div>
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
        <!--<div class="card-body">

            <fragment-form-text>
                <input class="form-control" type="text" name="title" id="question_title" v-model="question.title">
            </fragment-form-text>

            <template v-if="question.isComplex"></template>

            <div v-else>
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
                </div>

                <div class="row mb-3">
                    <div class="article-edit__text col-3">
                        Опис
                    </div>
                    <div class="col-9">
                        <textarea class="form-control" rows="4" v-model="question.description"></textarea>
                    </div>
                </div>
            </div>

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
        </div>-->

            <!--<ComplexTestVariants @file_сhange="fileChange" :complex="complex" v-bind:answer="answer" v-bind:variants="variants"></ComplexTestVariants>-->
            <div class="mb20"></div>
            <ComplexTestQuestion :complex="complex" :variants="variants" v-bind:complex_question="complex_question"></ComplexTestQuestion>
            <div class="mb20"></div>
            <button class="articles_create-submit button-border" type="button" @click="addBlockComplex">Добавить блок</button>
        </div>
    </div>
</template>
<script>
    import {required} from 'vuelidate/lib/validators'
    import ComplexTestVariants from './../inputs/ComplexTestVariantsArray.vue'
    import ComplexTestQuestion from './../inputs/ComplexTestQuestionArray.vue'
    import VContent from "../templates/Content"
    import { getRandomId } from '../../utils'
    import FragmentFormText from "./text";
    import {PROJECT_IMAGE} from "../../api/endpoints";
    export default {
        name: 'TestComplex',
        props: ['title', 'text', 'link', 'button', 'variants', 'question', 'complex_question', 'errors'],
        components: {
            FragmentFormText,
            //SimpleTestVariant,
            ComplexTestVariants,
            ComplexTestQuestion,
            VContent
        },

        data() {
            return {
                //tests: this.$store.state.tests,
                coverRandomId: getRandomId(),
                files: {},
                cover: null,
                video: null,
                complex: [],
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
            /*addAnswer() {
                const alphabet = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZ']
                let length = this.variants.length
                let obj = {
                    itemId: getRandomId(),
                    title: alphabet[length],
                    variant: '',
                    isCorrect: false,
                };
                this.variants.push(obj)
            },*/
            addBlockComplex() {
                let obj = {
                    itemId: getRandomId(),
                    title: '',
                    text: '',
                    variants: [
                        {
                            itemId: getRandomId(),
                            title: 'A',
                            variant: '',
                            isCorrect: false,
                            answer: {
                                type: true,
                                right: false,
                                media: []
                            }
                        },
                        {
                            itemId: getRandomId(),
                            title: 'B',
                            variant: '',
                            isCorrect: false,
                            answer: {
                                type: true,
                                right: false,
                                media: []
                            }
                        }
                    ]
                };
                this.complex_question.push(obj)
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
            //this.addComplex();
        }
    }
</script>
