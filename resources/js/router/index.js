import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "projectList",
            component: () => import("../components/ProjectList.vue")
        },
        {
            path: "/project/new",
            name: "Create project",
            component: () => import("../components/ProjectForm.vue")
        },
        {
            path: '/project/view/:projectId',
            name: "viewProject",
            component: () => import("../components/ProjectForm.vue")
        },
        {
            path: '/project/content',
            name: "Create content",
            component: () => import("../components/ContentForm.vue")
        },
        {
            path: '/project/content/:contentId',
            name: "Edit content",
            component: () => import("../components/ContentForm.vue")
        },
        {
            path: '/article',
            name: "article",
            component: () => import("../components/CreateArticleForm.vue")
        },
        {
            path: '/article/:articleId',
            name: "articleById",
            component: () => import("../components/CreateArticleForm.vue")
        },
        {
            path: '/test',
            name: "test",
            component: () => import("../components/CreateTestPage.vue")
        },
        {
            path: '/test/:testId',
            name: "testById",
            component: () => import("../components/CreateTestPage.vue")
        },


    ]

});
