import axios from 'axios';
import {alphabet, getRandomId} from "./utils";
import {PROJECT} from "./api/endpoints";

export default {
    data() {
        return {
            contentTemplate: {
                title: null,
                scheduled_date: '',
                scheduled_time: '',
                article: {
                    title: null,
                    type: 1,
                    text: null,
                    tags: [],
                    category: 1,
                    headings: 1,
                    author: null,
                    author2: null,
                    button: null,
                    text_button: null,
                    recommended: [],
                    user_id: [],
                    chosenRecommended: [],
                    cover: null,
                    featured_images: [],
                    media: {
                        cover: null
                    },
                    content: [
                        {
                            type: 'text',
                            title: '',
                            content: ''
                        }
                    ],
                    multiples: [],
                    insert: [
                        {
                            type: 'insert',
                            icon: 'alert',
                            title: null,
                            content: null,
                        },
                        {
                            type: 'text',
                            icon: 'alert',
                            title: null,
                            content: null,
                        }
                    ],
                    link: '',
                    count: null,
                    points: null,
                    frequency: null,
                    textInsert: false,
                },
                test: {
                    title: '',
                    text: '',
                    external_learn_url: '',
                    question: {
                        link: '',
                        button: null,
                        count: null,
                        points: null,
                        cover: null,
                        file: null,
                        fileType: null,
                        media: [],
                        type: 'variants',
                        correct: [],
                        isComplex: this.isComplex,
                        variants: [],
                    },
                    cover: null,
                    complex_question: [],
                    picked: 'test',
                    type: 'easy',
                    count: null,
                    points: null,
                    canRetake: false,
                },
            },
            complex_questionTemplate: {
                itemId: '',
                title: '',
                text: '',
                cover: null,
                file: null,
                fileType: null,
                type: 'variants',
                correct: [],
                variants: [],
                media: {
                    img: null,
                    video: null,
                },
                isCheckedVariant: true
            },
            variantTemplate: {
                itemId: '',
                variant: 'A',
                title: '',
                description: '',
                isCorrect: false,
                right: false,
                media: []
            },
            lists: {
                categories: [
                    {text: 'УСІ', value: 1},
                ],
                headings: [
                    {name: 'УСІ', id: 1},
                ],
                regions: [
                    {text: 'УСІ', value: 1},
                ],
            },
            errorFile: '',
            errorTitle: '',
            errorArticleTitle: '',
            errorArticleCover: '',
            errorArticleText: '',
            errorNewTest: '',
            errorNewArticle: '',
            add_new_test: false,
            add_new_article: false,
            index_article: 0,
            index_test: 0,
            isText: false,
            requiredErrorText: "Поле обязательное",
            lengthErrorText: "Длина текста должна быть не менее 1000 символов",
            notImageText: "Файл должен иметь одно из расширений  'jpg', 'jpeg', 'png', 'tiff', 'gif' ",
            notCorrectAnswer: "Должен быть указан по крайней мере один правильный ответ"
        }
    },
    computed: {
        currentStep() {
            return this.$store.state.currentStep;
        },
    },
    mounted() {
        if (sessionStorage.project) {
            this.$store.dispatch('storeProject', JSON.parse(sessionStorage.project));
        }
        this.project = {...this.$store.state.project};

    },

    methods: {
        isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },
        typeAnswerText(variants) {
            for (const index in variants) {
                if (index > 0) {
                    document.getElementById('block-' + index).classList.add('hide');
                }
            }
        },
        typeComplexAnswerText(variants) {
            for (const index in variants) {
                if (index > 0) {
                    document.getElementById('block-' + index + '-' + variants[index].itemId).classList.add('hide');
                }
            }
        },
        typeAnswerOther(variants) {
            for (const index in variants) {
                document.getElementById('block-' + index).classList.remove('hide');
            }
        },
        typeComplexAnswerOther(variants) {
            for (const index in variants) {
                document.getElementById('block-' + index + '-' + variants[index].itemId).classList.remove('hide');
            }
        },

        editContent(title) {
            this.contentTitle = title;
            this.$store.commit('setCurrentAction', 'edit');
            this.$store.commit('setCurrentContentTitle', title);
            this.setStep(2);
        },
        finalStoreProject() {
            document.getElementById('start').setAttribute('disabled', 'disabled');
            axios.post(PROJECT, {
                project: this.project
            }).then((resp) => {
                this.$router.push({name: 'projectList'});
            });
        },

        //служебная функция - поиск в массиве по полу value
        findByValue(array, id) {
            let res = array.filter(row => row.value == id);
            if (res.length) {
                return res[0].text;
            }
            return '';
        },
        getNewVariant(variant) {
            let variantObject = this.variantTemplate;
            variantObject.itemId = 'variant-' + getRandomId();
            variantObject.variant = variant;
            return variantObject;
        },
        getNewComplexQuestion() {
            let complex = this.complex_questionTemplate;
            complex.itemId = 'question-' + getRandomId;
            return complex;
        },

        setStep(step) {
            this.$store.commit('setStep', step);
        },
        addTag(newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.recommended.push(tag)
            this.chosenRecommended.push(tag)
        },

        /**
         * Adding one more answer variant to question
         */
        addAnswerTest(varIndex, questionIndex) {
            let test = {...this.$store.state.test};
            let letter = alphabet[varIndex];
            let newItem = {...this.getNewVariant(letter)};
            let q = [];
            if (!questionIndex) {
                if (test.question.variants.length) {
                    q = [...test.question.variants];
                }
                q.push(newItem);
                test.question.variants = [...q];
            } else {
                questionIndex--;
                if (test.complex_question.length) {
                    q = [...test.complex_question[questionIndex].variants];
                }
                q.push(newItem);
                test.complex_question[questionIndex].variants = [...q];
            }
            this.$store.commit('storeTest', test);
        },


    }
}
