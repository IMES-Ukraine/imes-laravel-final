import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "projectList",
            component: () => import("../components/ProjectList.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: "/login",
            name: "loginPage",
            component: () => import("../components/Login.vue"),
            meta: {
                auth: false
            }
        },
        {
            path: "/project/new",
            name: "createProject",
            component: () => import("../components/ProjectForm.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/project/view/:projectId',
            name: "viewProject",
            component: () => import("../components/ProjectForm.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/project/content',
            name: "createContent",
            component: () => import("../components/ContentForm.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/project/content/:contentId',
            name: "editContent",
            component: () => import("../components/CreateContentForm.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/articles',
            name: "articleList",
            component: () => import("../components/ArticleList.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/article/new',
            name: "createArticle",
            component: () => import("../components/ArticleForm.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/article/:articleId',
            name: "articleById",
            component: () => import("../components/CreateArticleForm.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/content-plan/new',
            name: "createContentPlan",
            component: () => import("../components/CreateContentPlan.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/test',
            name: "test",
            component: () => import("../components/TestForm.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/test/:testId',
            name: "testById",
            component: () => import("../components/TestForm.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/clients',
            name: 'clients',
            component: () => import("../components/ClientList.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/exchange',
            name: 'exchange',
            component: () => import("../components/ClientList.vue"),
            meta: {
                auth: true
            }
        },
        {
            path: '/requests',
            name: 'requests',
            component: () => import("../components/ClientList.vue"),
            meta: {
                auth: true
            }
        },

    ]

});
