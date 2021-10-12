<template>
    <div>
        <div class="card-body">

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
        name: 'TestComplex',
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
            addAnswer() {
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
            this.addAnswer();
        }
    }
</script>
