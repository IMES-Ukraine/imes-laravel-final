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
            path: "/moderation",
            name: "moderationList",
            component: () => import("../components/ModerationList.vue")
        },
        {
            path: "/withdrawal",
            name: "withdrawalRequest",
            component: () => import("../components/Withdrawal")
        },
        {
            path: "/notification",
            name: "sendNotification",
            component: () => import("../components/Notification.vue")
        },
        {
            path: "/chat",
            name: "chat",
            component: () => import("../components/Chat.vue")
        },
        {
            path: "/verification",
            name: "accountsVerification",
            component: () => import("../components/Verification")
        },
        {
            path: "/login",
            name: "loginPage",
            component: () => import("../components/Login.vue")
        },
        /*{
            path: "/project/new",
            name: "createProject",
            component: () => import("../components/ProjectForm.vue")
        },*/
        {
            path: "/project/new",
            name: "createProject",
            component: () => import("../components/CreateProjectForm.vue")
        },
        {
            path: '/project/view/:projectId',
            name: "dashboard",
            component: () => import("../components/Dashboard.vue")
            //component: () => import("../components/ProjectForm.vue")
        },
        {
            path: '/project/content',
            name: "createContent",
            component: () => import("../components/ContentForm.vue")
        },
        {
            path: '/project/content/:contentId',
            name: "editContent",
            component: () => import("../components/ContentForm.vue")
        },
        {
            path: '/articles',
            name: "articleList",
            component: () => import("../components/ArticleList.vue")
        },
        {
            path: '/article/new',
            name: "createArticle",
            component: () => import("../components/ArticleForm.vue")
        },
        {
            path: '/article/:articleId',
            name: "articleById",
            component: () => import("../components/ArticleFormUpdate.vue")
        },
        {
            path: '/content-plan/new',
            name: "createContentPlan",
            component: () => import("../components/CreateContentPlan.vue")
        },
        {
            path: '/test',
            name: "test",
            component: () => import("../components/TestForm.vue")
        },
        {
            path: '/test/:testId',
            name: "testById",
            component: () => import("../components/TestForm.vue")
        },

        {
            path: '/exchange',
            name: 'exchange',
            component: () => import("../components/ClientList.vue")
        },
        {
            path: '/requests',
            name: 'requests',
            component: () => import("../components/Request.vue")
        },
        {
            path: "/client/new",
            name: "createClient",
            component: () => import("../components/CreateClientForm.vue")
        },
        {
            path: '/clients',
            name: 'clients',
            component: () => import("../components/ClientList.vue")
        },
        {
            path: '/cards',
            name: 'cards',
            component: () => import("../components/CardList.vue")
        },
        {
            path: '/banner/new',
            name: 'BannerForm',
            component: () => import("../components/BannerForm.vue")
        },

    ]

});
