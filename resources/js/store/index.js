import Vue from 'vue'
import Vuex from 'vuex'
import CreateContentForm from '../components/CreateContentForm.vue' //CreateProjectForm
import CreateArticleForm from '../components/CreateArticleForm.vue'
import CreateProjectForm from '../components/CreateProjectForm.vue'
import ProjectList from '../components/ProjectList.vue'
import CreateTestPage from '../components/CreateTestPage.vue'
import axios from 'axios'
import {PROJECT} from "../api/endpoints";
//import { variantTemplate } from '@/utils'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        project: {
            options: {
                title: '',
                agreement: '',
                presentation_type: 'at_once',   // {'at_once', 'scheduled', 'series' }
                selected: {
                    category: null,
                    region: null
                },
                files: {
                    cover: {
                        file_name: ''
                    },
                    audience: null,
                }
            },
            tag: '',
            content: {},
        },

        content: {},
        test: {},
        projects: [],

        current: {
            projectId: null
        },
        currentStep: 1,
        isEdit: false,
        inEdit: false,
        checkbox: {
            0: 'Розблокувати',
            1: 'Заблокувати'
        },
        statusAddAnswer: true,

        responseData: {},
        modalData: {},
        showUserModal: false,
        filterId: null,
        clients: {},
        cards: {},

        errors: {},
        numberTest: 0,
        numberArticle: 0,
        testErrors: false,
        currentContentTitle: '',
        currentAction: '',
    },
    getters: {},
    mutations: {
        setFilter(state, data) {
            state.filterId = data;
        },
        setModalData(state, data) {
            state.modalData = data;
        },
        setShowUserModal(state, data) {
            state.showUserModal = data;
        },

        setContent(state, data) {
            state.content = data;
            state.formKey = Math.random();
        },
        resetContent(state, title) {
            Vue.delete(state.project.content, title);
            sessionStorage.project = JSON.stringify(state.project);
        },

        setQuestionType(state, type) {
            state.test.question.type = type;
            console.log(state.test.question.type, type);
        },

        setStep(state, step) {
            state.currentStep = step;
        },

        addCorrect(state, data) {
            state.test.answer.correct.push(data);
        },
        removeCorrect(state, data) {
            state.test.answer.correct = state.test.answer.correct.filter((item) => {
                return item !== data;
            });
        },

        submitArticle(state, form) {
            state.articles[0] = form
        },
        submitQuestion(state, item) {

            state.questions.push(item)
        },
        submitTest(state, questions) {
            state.questions = questions
        },
        addTestId(state, id) {
            state.testIds.push(id)
        },
        selectProject(state, id) {
            state.current.projectId = id
        },
        saveProject(state, project) {
            state.project.options = project.options
            state.tests = project.tests
            state.questions = project.tests
            state.articles = project.articles
            state.content = project.content
            state.current = project.current
        },
        loadProject(state, project) {
            state.project.options = project.options
            state.tests = project.tests
            state.questions = project.tests
            state.articles = project.articles
            state.content = project.content
            state.current = project.current
        },

        storeProject(state, project) {
            state.project = project;
            sessionStorage.project = JSON.stringify(project);
        },

        setCurrentContent(state, title) {
            state.content = state.project.content[title];
            state.content.currentContentTitle = title;
        },
        setCurrentArticle(state) {
            state.article = state.content.article;
        },
        setCurrentTest(state,) {
            state.test = state.content.test;
        },

        storeTest(state, data) {
            state.test = data;
        },
        storeArticle(state, data) {
            state.article = data;
        },


        saveArticle(state, article) {
            state.content.article = article;
            state.currentAction = null;
            state.numberArticle = 1;
        },
        saveTest(state, test) {
            state.content.test = test;
            state.currentAction = null;
            state.numberTest = 1;
        },
        storeContent(state, content) {
            state.content = content;
        },
        saveContent(state, content) {
            state.project.content[content.title] = content;
            sessionStorage.project = JSON.stringify(state.project);
        },

        storeTestContent() {
            state.questions[0].points = content
        },

        setTestError(state, error) {
            this.state.testErrors = error;
        },

        setErrors(state, error) {
            this.state.errors = error;
        },
        setCurrentAction(state, action) {
            this.state.currentAction = action;
        },
        setCurrentContentTitle(state, title) {
            this.state.currentContentTitle = title;
        },
        setClients(state, data) {
            state.clients = data;
        },
        setCards(state, data) {
            state.cards = data;
        },
        setResponseData(state, data) {
            state.responseData = data;
        }


    },
    actions: {
        setFilter(context, data) {
            context.commit('setFilter', data);
        },

        setModalData(context, data) {
            context.commit('setModalData', data);
        },
        setShowUserModal(context, data) {
            context.commit('setShowUserModal', data)
        },


        addCorrect(context, data) {
            context.commit('addCorrect', data)
        },
        removeCorrect(context, data) {
            context.commit('removeCorrect', data)
        },

        setContent(context, data) {
            context.commit('setContent', data);
        },

        loadProject(context, data) {
            context.commit('loadProject', data)
            context.commit('startEdit')
        },
        setStep(context, step) {
            context.commit('setStep', step)
        },
        nextStep(context) {
            context.commit('nextStep')
        },
        addPoints(context, data) {
            context.commit('addPoints', data)
        },
        submitForm(context) {

            context.$v.$touch()
            if (context.$v.$invalid) {
                //
            } else
                context.commit('nextStep')
        },
        submitArticle(context, article) {

            return new Promise(resolve => {

                context.commit('submitArticle', article)
                resolve()

            }).catch(reject => {

                reject()
            })
        },

        selectProject(context, id) {
            context.commit('selectProject', id)
        },
        createEntity({state}, data) {
            axios.post(PROJECT, {...data, content: state.content}).then((resp) => {
                //commit('saveEntity', data.entity, {...data, ...state.content})
            })
        },
        updateEntity({state}, data) {
            axios.post(PROJECT + '/' + state.current.projectId, {
                ...data,
                content: state.content,
                current: state.current
            }).then((resp) => {

                //context.commit('saveEntity', 's', data)
            })
        },


        saveProject(context, project) {
            //context.commit('saveProject', project)
            axios.post(PROJECT, {options: project}).then((resp) => {
                context.commit('saveProject', project)
            })
        },
        updateProject(context, project) {
            //context.commit('saveProject', project)
            axios.post(
                PROJECT,
                {options: project}).then((resp) => {
                context.commit('saveProject', project)
            })
        },

        saveArticle(context, article){
            context.commit('saveArticle', article);
        },


        storeProject(context, project) {
            context.commit('storeProject', project)
        },
        storeArticle(context, article) {
            context.commit('storeArticle', article);
        },
        storeTest(context, test) {
            context.commit('storeTest', test);
        },

        saveContent(context, content) {
            context.commit('saveContent', content);
        },
        storeContent(context, content) {
            context.commit('storeContent', content);
        },
        loadContent(context, title) {
            context.commit('loadContent', title);
        },


        setCurrentContent(context, title) {
            context.commit('setCurrentContent', title);
        },
        setCurrentArticle(context, data) {
            context.commit('setCurrentArticle', data);
        },
        setCurrentTest(context, data) {
            context.commit('setCurrentTest', data);
        },

        editArticle(context) {
            context.commit('setCurrentArticle');
            context.commit('setStep', 2);
        },
        editTest(context) {
            context.commit('setCurrentTest');
            context.commit('setStep', 3);
        },


        deleteContent(context, title) {
            context.commit('resetContent', title);
            context.commit('setStep', 1);
        },

        setQuestionType(context, type) {
            context.commit('setQuestionType', type);
        },

        setTestError(context, error) {
            context.commit('setTestError', error);
        },
        setErrors(context, error) {
            context.commit('setErrors', error);
        },

        setCurrentAction(context, action) {
            context.commit('setCurrentAction', action);
        },
        setCurrentContentTitle(context, title) {
            context.commit('setCurrentContentTitle', title);
        },

        setClients(context, data) {
            context.commit('setClients', data);
        },
        setCards(context, data) {
            context.commit('setCards', data);
        },
        setResponseData(context, data) {
            context.commit('setResponseData', data);
        }
    },
    modules: {}
})
