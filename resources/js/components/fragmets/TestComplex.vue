<template>
    <div>
        <div class="articles_create-box">
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Название</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__name-block">
                            <input type="text" name="title" id="question_title" v-model="test.title">
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
                    <file-input :value="test.cover"
                                :error="coverError"
                                @fileInput="test.cover = $event"
                                type="cover"/>
                </div>
            </div>
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Описание</p>
                    <div class="articles_create__item-content">
                        <textarea v-model="test.text"></textarea>
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

            <div class="mb20"></div>
            <ComplexTestQuestion
                :toValidate="toValidate"
                :complex_question.sync="test.complex_question"/>
            <div class="mb20"></div>
            <button class="articles_create-submit button-border" type="button" @click="addBlockComplex">Добавить блок
            </button>
        </div>
        <div class="mb20"></div>
        <button class="articles_create-submit button-gradient" type="button"
                @click="$emit('input', test)">сохранить
        </button>
    </div>
</template>
<script>
import {required} from 'vuelidate/lib/validators'
import ComplexTestVariants from './../inputs/ComplexTestVariantsArray.vue'
import ComplexTestQuestion from './../inputs/ComplexTestQuestionArray.vue'
import VContent from "../templates/Content"
import {getRandomId} from '../../utils'
import FragmentFormText from "./text";
import ProjectMixin from "../../ProjectMixin";
import FileInput from "../inputs/file-input";

export default {
    name: 'TestComplex',
    props: ['test', 'errors', 'toValidate'],
    mixins: [ProjectMixin],
    components: {
        FragmentFormText,
        //SimpleTestVariant,
        ComplexTestVariants,
        ComplexTestQuestion,
        FileInput,
        VContent
    },

    data() {
        return {
            complexQ: [...this.test.complex_question],
            files: {},
            cover: null,
            video: null,
            categoryList: [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
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
    methods: {

        addBlockComplex() {
            let newBlock = {...this.complex_questionTemplate};
            let id = getRandomId();
            newBlock.itemId = 'complex-' + id;
            this.complexQ.push(newBlock);

            this.test.complex_question = [...this.complexQ];
            let index = this.test.complex_question.length;
            this.$store.commit('storeTest', this.test);
            this.addAnswerTest(0, index);
            this.addAnswerTest(1, index);
        },
    },
}
</script>
