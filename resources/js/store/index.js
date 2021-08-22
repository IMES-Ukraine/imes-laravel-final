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
                category : [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
                region : [
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
                category : 1,
                headings : 1,
                author : 1,
                button: null,
                recommended : [],
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
                link: 'http://imes.pro/',
                count: null,
                points: null,
                frequency: null,
                textInsert: false,
                categoryList : [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
                headingsList : [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
                authorList : [
                    {name: 'ВСЕ', id: 1},
                    {name: 'Дерматология', id: 2},
                    {name: 'Кардиология', id: 3},
                    {name: 'Гастроэнтерология', id: 4},
                ],
            }
        ],
        questions: [],
        tests: [],
        projects: [],
        lists: {
            categories : [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
            headings : [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
            authors : [
                {name: 'ВСЕ', id: 1},
                {name: 'Дерматология', id: 2},
                {name: 'Кардиология', id: 3},
                {name: 'Гастроэнтерология', id: 4},
            ],
        }
    },
    getters: {
        currentStep : state => {
            return state.currentStep
        }
    },
    mutations: {
        nextStep(state) {
            state.currentStep++
        },
        submitArticle(state, form) {
            state.articles[0] = form
        },
        addArticleId(state, id) {
            state.articleIds.push(id)
        },
        submitTest(state, item) {

            state.questions.push(item)
        },
        addTestId(state, id) {
            state.testIds.push(id)
        },
        selectProject(state, id) {
            state.current.project = id
        },
        loadProjects(state, projects) {
            state.projectList = projects
        },
        saveProject(state, project) {
            //console.log(this)
            //this.replaceState(project)
            state.project.options = project.options
            state.tests = project.tests
            state.articles = project.articles
            state.content = project.content
        },
        storeProject(state, project) {
            state.project.options = project
        },
        storeContent(state, content) {
            state.content = content
        },
        saveEntity(state, entity, data) {
            this.state[entity] = data
        }
    },
    actions: {
        loadProject(context, data) {
            context.commit('saveProject', data)
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

            }).catch( reject => {

                reject()
            })
        },
        submitContent(context, form) {

            context.commit('submitContent', form)
        },
        submitTest({commit/*, state*/}, questions) {

            questions.forEach((item) => commit('submitTest', item))


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
        loadProjects(context, projects) {
            context.commit('loadProjects', projects)
        },
        createEntity(context, data) {

            axios.post(PROJECT, data).then((resp) => {
                resp
                context.commit('saveEntity', data.entity, data)
            })
        },
        updateEntity(context, data) {
            axios.post(process.env.VUE_APP_API_URI + '/' + data.entity + '/' + data.id, data ).then((resp) =>{
                resp
                //context.commit('saveEntity', 's', data)
            })
        },
        storeProject( context, project) {
            context.commit('storeProject', project)
        },
        storeContent( context, content) {

            return new Promise(resolve => {
                context.commit('storeContent', content)
                resolve()
            }).catch(reject => {
                reject()
            })

        },
        saveProject(context, project) {
            //context.commit('saveProject', project)
            axios.post(process.env.VUE_APP_API_URI + '/project', {options:project}).then((resp) => {
                resp
                context.commit('saveProject', project)
            })
        },
        updateProject(context, project) {
            //context.commit('saveProject', project)
            axios.post(process.env.VUE_APP_API_URI + '/project', {options:project}).then((resp) => {
                resp
                context.commit('saveProject', project)
            })
        },
    },
    modules: {
    }
})
