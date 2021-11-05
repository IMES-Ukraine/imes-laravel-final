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
                selected: {
                    category: 1,
                    region: 1
                },
                files: {
                    cover: null,
                    audience: null,
                    article_cover: null,
                    article_gallery: []
                }
            },
            tag: '',
            content: {}

        },
        contentList: {},
        test: {},
        projects: [],

        current: {
            projectId: null
        },
        currentStep: 1,
        currentContent: '',
        isEdit: false,
        inEdit: false,
        checkbox: {
            0: 'Розблокувати',
            1: 'Заблокувати'
        },
        statusAddAnswer: true,

        modalData: {},
        showUserModal: false,
        filterId: null,

        clients: {},
        cards: {},
    },
    getters: {
        contentPack(state, title) {
            return state.project.content[title];
        }
    },
    mutations: {
        startEdit(state) {
            state.inEdit = true
        },
        setStep(state, step) {
            state.currentStep = step;
        },

        resetContent(state, title) {
            state.currentContent = null;
            Vue.delete(state.project.content, title);
            sessionStorage.project = JSON.stringify(state.project);
        },


        submitArticle(state, form) {
            state.articles[0] = form
        },
        addArticleId(state, id) {
            state.articleIds.push(id)
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
            state.project.options = project
        },

        setCurrentContent(state, title) {
           state.currentContent = title;
        },
        storeArticle(state, article) {
            state.project.content[state.currentContent].article = article;
            sessionStorage.project = JSON.stringify(state.project);
        },
        storeContent(state, content) {
            state.project.content[content.title] = content;
            sessionStorage.project = JSON.stringify(state.project);
        },
        loadContent(state, title) {
            state.currentContent = title;
            state.project = JSON.parse(sessionStorage.project);
        },

        storeTestContent() {
            state.questions[0].points = content
        },
        saveEntity(state, entity, data) {
            this.state[entity] = data
        }
    },
    actions: {
        startEdit(context) {
            context.commit('startEdit')
        },
        loadForm(context, data) {
            context.commit('saveProject', data)
        },
        loadProject(context, data) {
            context.commit('loadProject', data)
            context.commit('startEdit')
        },
        submitProject(context) {
            context.state.currentForm = 'NEXT_STEP'
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
        submitContent(context, form) {

            context.commit('submitContent', form)
        },
        submitTest({commit/*, state*/}, questions) {

            //questions.forEach((item) => commit('submitQuestion', item))
            commit('submitTest', questions)


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



        storeProject(context, project) {
            context.commit('storeProject', project)
        },
        storeArticle(context, article) {
            context.commit('storeArticle', article);
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
        editContent(context, title) {
            context.commit('loadContent', title);
            context.commit('setStep', 2);
        },
        deleteContent(context, title) {
            context.commit('resetContent', title);
            context.commit('setStep', 1);
        }
    },
    modules: {}
})
