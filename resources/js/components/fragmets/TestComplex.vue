<template>
    <div>
        <div class="articles_create-box">
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Название</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__name-block">
                            <input type="text" name="title" id="question_title" v-model="test.title">
                            <div v-if="errors.testText" class="errors">{{ errors.testTitle }}</div>
                        </div>
                    </div>
                </div>
<!--                <div class="articles_create__item">-->
<!--                    <p class="articles_create__item-title">Категория</p>-->
<!--                    <div class="articles_create__item-content">-->
<!--                        <select class="my-ui-select articles_create-select">-->
<!--                            <option value="1">Категория 1</option>-->
<!--                            <option value="2">Категория 2</option>-->
<!--                            <option value="3">Категория 3</option>-->
<!--                            <option value="4">Категория 4</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="articles_create__item half">
                    <p class="articles_create__item-title">Обложка</p>
                    <file-input :value="test.cover"
                                :error="coverError"
                                @fileInput="test.cover = $event"
                                type="image"/>
                </div>
            </div>
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <p class="articles_create__item-title">Описание</p>
                    <div class="articles_create__item-content">
                        <textarea v-model="test.text"></textarea>
                        <div v-if="errors.testText" class="errors">{{ errors.testText }}</div>
                    </div>
                </div>
            </div>
            <div class="articles_create-block">
                <div class="articles_create__item">
                    <div class="articles_create__item-title has_radio">
                        <input type="checkbox" v-model="toLearn">
                        <i></i>
                        <p>Изучить</p>
                    </div>
                    <div class="articles_create__item-content">
                        <div class="articles_create__name-block">
                            <input type="text" name="name" placeholder="http//" v-model="test.external_learn_url">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb20"></div>
            <ComplexTestQuestion
                :toValidate="toValidate"
                :errorsParent="errors"
                :complex_question.sync="test.complex_question"/>
            <div class="mb20"></div>
            <div v-if="errors.complex" class="errors mb20">{{ errors.complex }}</div>
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
            toLearn: this.test.external_learn_url,
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
            newBlock.correct = [];
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
