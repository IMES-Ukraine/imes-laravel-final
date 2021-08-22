import Vue from 'vue'
import Vuex from 'vuex'
import CreateContentForm from '../components/CreateContentForm.vue'//CreateProjectForm
import CreateArticleForm from '../components/CreateArticleForm.vue'
import CreateProjectForm from '../components/CreateProjectForm.vue'
import ProjectList from '../components/ProjectList.vue'
import CreateTestPage from '../components/CreateTestPage.vue'
import axios from 'axios'
//import { variantTemplate } from '../utils'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    currentStep: 1,
    current: {
        project: 0
    },
    //currentForm: 'CreateProjectForm',
    currentForm: 'ProjectList',
    contentTitle: 'Название пакета',
    attachments: {},
    project: null,
    articles: [],
    tests: [],
    /*articles: [{

      title: 'Заголовок статьи',
      articleType: 1,
      text: 'Тестовый текст статьи',
      tags: [],
      category : 1,
      headings : 1,
      author : 1,
      button: null,
      recommended : [],
      authors: [],
      user_id: [],
      chosenRecommended: [],
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
    }],
    tests: [variantTemplate],*/
    testIds: [],
    articleIds: [],
    projectList: [
        { options: {
            title: 'Имя проекта',
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
        }} ],
    projects: [],
    content: []
  },
  getters: {
    currentStep : state => {
      return state.currentStep
    }
  },
  mutations: {
    changeForm(state, form) {
      state.currentForm = form
    },
    nextStep(state) {
      state.currentStep++
    },
    addPoints(state, data) {
        state.articles[0].points = data[0];
        state.tests[0].question.points = data[1];
    },
    addFiles(state, file) {
        state.attachments[file.itemId] = file.id
    },
    submitArticle(state, form) {
        state.projectList[state.current.project].options.article_id = form.id
        state.articles[0] = form
        state.currentForm = CreateProjectForm.name
    },
    addArticleId(state, id) {
        state.articleIds.push(id)
    },
    submitContent(state, form) {

      state.contentTitle = form.contentTitle
      state.currentForm = CreateProjectForm.name
    },
    submitTest(state, form) {
      state.projectList[state.current.project].options.test_id = form.id
      state.currentForm = CreateProjectForm.name
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
    saveProject(state, projects) {

        this.state.projectList.push(projects)
    },
    storeProject(state, project) {
        state.project = project
    },
    saveEntity(state, entity, data) {
        this.state[entity] = data
    }
  },
  actions: {
    addFiles(context, formData){
     let imageForm = new FormData();
     imageForm.append('file', formData.file)

         axios.post(process.env.VUE_APP_API_URI + '/project/image/test',
             imageForm,
         {
             headers: {
                 'Content-Type': 'multipart/form-data'
             }
         })
         .then((response) => {
             console.log(response);
             context.commit('addFiles', {itemId: formData.id, id: response.data.data.id})
         })
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
    submitArticle(context, formData) {

        context;

        let imageForm = new FormData();
        imageForm.append('file', formData.articleCover);

            axios.post(process.env.VUE_APP_API_URI + '/project/image/article',
            imageForm,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        ).then((file) => {

            formData.cover_image = file.data.data;

            let form = {'articles':[formData]};
            axios.post(process.env.VUE_APP_API_URI + '/projects/submit',
                form,
            ).then((resp) => {
                    formData.id = resp.data.data.project.id

                    context.commit('submitArticle', formData)

                    context.commit('addArticleId', resp.data.data.project.id)

                //context.commit('submitArticle', formData)
                    console.log(resp.data.data.project.id);
                });

        })
    },
    submitContent(context, form) {

      context.commit('submitContent', form)
    },
    submitTest({commit, state}, formData) {

        let form = {'tests':formData, 'attachments': state.attachments};

        axios.post(process.env.VUE_APP_API_URI + '/projects/submit',
            form,

        ).then((resp) => {
            formData.id = resp.data.data.project.id
            commit('submitTest', formData)
            commit('addTestId', resp.data.data.project.id)
            //console.log(resp);
        });

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
        console.log(data.articles)
        axios.post(process.env.VUE_APP_API_URI + '/' + data.entity , data).then((resp) => {
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
    addArticle(context) {
      context.commit('changeForm', CreateArticleForm.name)
    },
    addTest(context) {
      context.commit('changeForm', CreateTestPage.name)
    }
  },
  modules: {
  }
})
