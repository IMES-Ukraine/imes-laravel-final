import axios from 'axios';
export default {
    data() {
        return {
            tests: this.$store.state.questions,
            content: {
                ...this.$store.state.content
            },
            article: {},
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
            is_points: false,
        }
    },
    computed: {
        currentStep() {
            return this.$store.state.currentStep;
        },
        finishProject() {
            return this.currentStep > 4
        },
    },
    methods: {
        multiplesStore(value) {
            this.multiples = value
        },
        setStep(step) {
            this.$store.state.currentStep = step;
        },
        nextStep() {
            this.$store.state.currentStep++;
        },
        showContent() {
            this.errorFile = '';
            this.errorTitle = '';

            if (this.options.files.cover == null) {
                this.errorFile = 'Поле обязательно'
            }

            if (this.options.title === '') {
                this.errorTitle = 'Требуется указать название'
            }

            if (this.options.title.length > 50) {
                this.errorTitle = 'Требуется указать название не больше 50 символов'
            }

            if (this.errorTitle === '' && this.errorFile === '') {
                this.block_content = true;
            }
            sessionStorage.options = JSON.stringify(this.options);
        },


        addContent() {
            this.$store.dispatch('addContent')
        },
        back() {
            this.$store.dispatch('projectList')
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
            for (const [index, value] of Object.entries(this.questions)) {
                this.questions.splice(index, 1)
            }

            this.addQuestion()
            this.add_new_test = false
        },
        reloadBlockTest() {
            for (const [index, value] of Object.entries(this.questions)) {
                this.questions.splice(index, 1)
            }

            this.addQuestion()
            this.add_new_test = false
        },
        onChange() {
            let content_points = parseInt(this.content.points)
            let test_points = parseInt(this.test.points)

            if (content_points && test_points) {
                this.is_points = true
                $('.articles_create-note p').html(parseInt(this.content.points) + parseInt(this.test.points))
            }
        },
    }
}
