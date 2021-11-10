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
                    articleType: 1,
                    type: 1,
                    text: null,
                    tags: [],
                    category: 1,
                    headings: 1,
                    author: null,
                    button: null,
                    text_button: null,
                    recommended: [],
                    user_id: [],
                    chosenRecommended: [],
                    cover: '',
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
                    question: {
                        title: '',
                        text: '',
                        description: '',
                        link: '',
                        button: null,
                        count: null,
                        points: null,
                        media: {
                            cover: null,
                            video: null,
                        },
                        answer: {
                            type: 'variants',
                            correct: []
                        },
                        isComplex: this.isComplex,
                        agreement: null
                    },
                    cover: '',
                    complex_question: [],
                    variants: [],
                    picked: 'test',
                    type: 'easy',
                    count: null,
                    points: null,
                    canRetake: null,

                },

            },
            complex_questionTemplate: {
                itemId: '',
                title: '',
                text: '',
                cover: '',
                answer: {
                    type: 'variants',
                    correct: []
                },
                variants: [],
            },
            variantTemplate: {
                itemId: '',
                title: 'A',
                text: '',
                variant: '',
                isCorrect: false,
                right: false,
                media: []
            },
            lists: {
                categories: [
                    {text: 'ВСЕ', value: 1},
                    {text: 'Дерматология', value: 2},
                    {text: 'Кардиология', value: 3},
                    {text: 'Гастроэнтерология', value: 4},
                ],
                headings: [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
                regions: [
                    {text: 'УСІ', value: 1},
                    {text: 'Схід', value: 2},
                    {text: 'Захід', value: 3},
                    {text: 'Центр', value: 4},
                ],
            },
            errorFile: '',
            errorTitle: '',
            errorArticleTitle: '',
            errorArticleCover: '',
            errorArticleText: '',
            errorNewTest: '',
            errorNewArticle: '',
            errorTestSurveyTitle: '',
            errorTestSurveyText: '',
            add_new_test: false,
            add_new_article: false,
            index_article: 0,
            index_test: 0,
        }
    },
    computed: {
        currentStep() {
            return this.$store.state.currentStep;
        },
    },
    mounted() {
        if (sessionStorage.project) {
            this.$store.dispatch('storeProject', JSON.parse(sessionStorage.project) );
        }
        this.project = {...this.$store.state.project};

    },

    methods: {
        finalStoreProject() {
            axios.post(PROJECT, {
                project: this.project
            }).then((resp) => {
                this.$router.push({name: 'projectList'});
            });
        },
        findByValue(array, id) {
            let res = array.filter(row => row.value == id);
            if (res.length) {
                return res[0].text;
            }
            return '';
        },
        getNewVariant(title) {
            let variant = this.variantTemplate;
            variant.itemId = 'variant-' + getRandomId();
            variant.title = title;
            return variant;
        },
        getNewComplexQuestion() {
            let complex = this.complex_questionTemplate;
            complex.itemId = 'question-' + getRandomId;
            return complex;
        },

        setStep(step) {
            this.$store.dispatch('setStep', step);
        },
        addTag(newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.recommended.push(tag)
            this.chosenRecommended.push(tag)
        },
        reloadBlockSurveyTest() {
            this.$store.state.contentList[this.$store.state.currentContent].test = {};
        },

        /**
         * Adding one more answer variant to question
         */
        addAnswerTest(varIndex, questionIndex) {

            let title = alphabet[varIndex];
            let newItem = {... this.getNewVariant(title) };
            if (!questionIndex) {
                this.test.variants.push(newItem);
            }
            else {
                questionIndex--;
                let q;
                if (this.test.complex_question.length) {
                    q = [...this.test.complex_question[questionIndex].variants];
                }else{
                    q = [];
                }
                q.push(newItem);
                this.test.complex_question[questionIndex].variants = [...q];
            }
        },

    }
}
