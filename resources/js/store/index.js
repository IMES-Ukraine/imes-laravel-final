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
                category: [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
                region: [
                    {name: 'ВСЯ УКРАИНА', id: 1},
                    {name: 'Одесская', id: 2},
                    {name: 'Киевская', id: 3},
                    {name: 'Черниговская', id: 4},
                    {name: 'Житомирская', id: 5},
                ],
                selected: {
                    category: 1,
                    region: 1
                },
                files: {
                    cover: null,
                    audience: null
                }
            }
        },
        content: {
            title: null,
            article: {
                count: null,
                points: null,
                frequency: null,
            },
            test: {
                count: null,
                points: null,
                canRetake: null,
            }
        },
        articles: [
            {
                title: null,
                articleType: 1,
                type: null,
                text: null,
                tags: [],
                category: 1,
                headings: 1,
                author: 1,
                button: null,
                recommended: [],
                authors: [],
                user_id: [],
                chosenRecommended: [],
                images: null,
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
                //link: 'http://imes.pro/',
                //link: 'http://imes-laravel.local/',
                link: 'https://laravel-dev-final.imes.pro/',
                count: null,
                points: null,
                frequency: null,
                textInsert: false,
                categoryList: [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
                headingsList: [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
                authorList: [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
            }
        ],
        questions: [
            {
                points: null
            }
        ],
        tests: [],
        projects: [],
        lists: {
            categories: [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
            headings: [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
            authors: [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
        },
        current: {
            projectId: null
        },
        isEdit: false,
        inEdit: false,
        checkbox: {
            0: 'Разблокувати',
            1: 'Заблокувати'
        }
    },
    getters: {
        currentStep: state => {
            return state.currentStep
        }
    },
    mutations: {
        startEdit(state) {
            state.inEdit = true
        },
        nextStep(state) {
            state.currentStep++
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
        storeContent(state, content) {
            console.log('into storeContent')
            console.log(content)

            state.content = content
            state.articles[0].points = content.article.points
            //state.questions[0].question.points = content.test.points
            state.questions[0].points = content.test.points
        },
        storeTestContent(){
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
        addContent(context) {
            context.commit('changeForm', CreateContentForm.name)
        },
        projectForm(context) {
            context.commit('changeForm', CreateProjectForm.name)
        },
        projectList(context) {
            context.commit('changeForm', ProjectList.name)
        },
        selectProject(context, id) {
            context.commit('selectProject', id)
        },
        createEntity({state}, data) {

            axios.post(PROJECT, {...data, content: state.content}).then((resp) => {
                resp
                //commit('saveEntity', data.entity, {...data, ...state.content})
            })
        },
        updateEntity({state}, data) {
            axios.post(PROJECT + '/' + state.current.projectId, {
                ...data,
                content: state.content,
                current: state.current}).then((resp) => {
                resp
                //context.commit('saveEntity', 's', data)
            })
        },

        storeProject(context, project) {
            context.commit('storeProject', project)
        },

        storeContent(context, content) {

            return new Promise((resolve, reject) => {

                console.log('into promise')
                context.commit('storeContent', content)

                let redirect = {name: 'createProject'}
                if (context.state.current.projectId) {
                    redirect = {
                        name: 'viewProject',
                        params: {
                            projectId: context.state.current.projectId
                        }
                    }
                }
                resolve(redirect)

                //reject()
            })
        },
        saveProject(context, project) {
            //context.commit('saveProject', project)
            axios.post(
                process.env.VUE_APP_API_URI + '/project',
                {options: project}).then((resp) => {
                resp
                context.commit('saveProject', project)
            })
        },
        updateProject(context, project) {
            //context.commit('saveProject', project)
            axios.post(
                PROJECT,
                {options: project}).then((resp) => {
                resp
                context.commit('saveProject', project)
            })
        },
    },
    modules: {}
})
