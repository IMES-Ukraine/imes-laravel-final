<template>
    <v-content v-if="isLoaded">
        <template v-slot:sidebar>
            <project-list-sidebar :options="project.project.options" :status="project.project.status" :project_id="project.project.id" />
        </template>

        <div class="dashboard">
            <div class="dashboard-head">
                <div class="dashboard__title" v-if="project.project.options">
                    <cover :image="project.project.options.files.cover.path" :title="project.project.options.title" class="dashboard__title-logo"/>
                    <p class="dashboard__title-text">{{ project.project.options.title }}</p>
                </div>
                <div class="dashboard__info" v-if="project.project">
                    <p class="dashboard__info-title">Дата запуска проекта:</p>
                    <p class="dashboard__info-data">{{ project.project.created_at.substr(0, 10) }}</p>
                </div>
            </div>
            <div class="dashboard_main">
                <div class="dashboard_main-head">
                    <p class="dashboard_main-total">Активностей <b>{{ project.all_activities }}</b></p>
                    <div class="dashboard_main__status">
                        <p class="dashboard_main__status-title">Статус активностей</p>
                        <div class="dashboard_main__status-content">
                            <p class="dashboard_main__status-description">{{ percentActive(project.status_active, project.user_total) }}% выполненых</p>
                            <div class="dashboard_main__status-line">
                                <span :style="'width:'+percentActive(project.status_active, project.user_total)+'%;'"></span>
                            </div>
                            <p class="dashboard_main__status-description">{{ project.user_total }} активностей</p>
                        </div>
                    </div>
                </div>
                <div class="dashboard_main-body">
                    <div class="dashboard_main__schedule">
                        <div class="dashboard_main__schedule-block">
                            <canvas id="dashboardCircle" width="150" height="150"></canvas>
                        </div>
                        <p class="dashboard_main__schedule-title">Аудитория</p>
                    </div>
                    <div class="dashboard_main-info">
                        <div class="dashboard_main__item">
                            <div class="dashboard_main__item-wrap">
                                <p class="dashboard_main__item-status dashboard_main__item-status--green"><span>Выполнили активности</span></p>
                                <p class="dashboard_main__item-quantity">{{ project.status_active }}</p>
                                <input type="hidden" id="project_status_active" :value="(project.status_active)?project.status_active:0" />
                            </div>
                            <users-popup :title="'Выполнили активности'" id="status_active" index="1" v-if="project.status_active" :project_id="project.project.id"/>
                        </div>
                        <div class="dashboard_main__item">
                            <div class="dashboard_main__item-wrap">
                                <p class="dashboard_main__item-status dashboard_main__item-status--red"><span>Не выполнили активности</span></p>
                                <p class="dashboard_main__item-quantity">{{ project.status_not_active }}</p>
                                <input type="hidden" id="project_status_not_active" :value="(project.status_not_active)?project.status_not_active:0" />
                            </div>
                            <users-popup :title="'Не выполнили активности'" id="status_not_active" index="0" v-if="project.status_not_active" :project_id="project.project.id"/>
                        </div>
                        <div class="dashboard_main__item">
                            <div class="dashboard_main__item-wrap">
                                <p class="dashboard_main__item-status dashboard_main__item-status--blue"><span>Не участвовали</span></p>
                                <p class="dashboard_main__item-quantity">{{ project.status_not_participate }}</p>
                                <input type="hidden" id="project_status_not_participate" :value="(project.status_not_participate)?project.status_not_participate:0" />
                            </div>
                            <users-popup :title="'Не участвовали'" id="status_not_participate" index="2" v-if="project.status_not_participate" :project_id="project.project.id"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard_study" v-if="project.content">
                <div class="dashboard_study-box" v-for="(content, key) in project.content">
                    <div class="dashboard_study__head">
                        <p class="dashboard_study-title">{{ content.title }}</p>
                        <div class="dashboard_study__head-list">
                            <p class="status">Статус активностей</p>
                            <p>Выполнили активности</p>
                            <p>Не выполнили активности</p>
                            <p>Не участвовали</p>
                        </div>
                    </div>
                    <div class="dashboard_study__block">
                        <div class="dashboard_study__block-head">
                            <p class="dashboard_study__block-title">Тесты</p>
                            <test-popup :id="key" :content_id="content.id" :project_id="project.project.id" :test="content.fullTest" :tests="[content.fullTest]" :passing_tests="project.passing_tests" />
                        </div>
                        <div class="dashboard_study__status">
                            <div class="dashboard_main__status-content width-100">
                                <p class="dashboard_main__status-description">{{ percentActive(content.test_status_active, project.user_total) }}% выполненых</p>
<!--                                <p class="dashboard_main__status-description">{{ percentActive(content.test_status_active, content.test_total) }}% выполненых</p>-->
                                <div class="dashboard_main__status-line">
                                    <span :style="'width:'+percentActive(content.test_status_active, project.user_total)+'%;'"></span>
                                </div>
                                <p class="dashboard_main__status-description">{{ project.user_total}} активностей</p>
                            </div>
                        </div>
                        <div class="dashboard_study__info">
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">{{ content.test_status_active }}</p>
                                <users-popup
                                    :title="'Выполнили активности'"
                                    classButton="dashboard_study__info-button"
                                    :id="'test_status_active' + content.id"
                                    index="1"
                                    v-if="content.test_status_active"
                                    :project_id="project.project.id"
                                    is_test="1"
                                    :content_id="content.id"/>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">{{ content.test_status_not_active }}</p>
                                <users-popup
                                    :title="'Не выполнили активности'"
                                    classButton="dashboard_study__info-button"
                                    :id="'test_status_not_active' + content.id"
                                    index="0"
                                    v-if="content.test_status_not_active"
                                    :project_id="project.project.id"
                                    is_test="1"
                                    :content_id="content.id"/>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">{{ content.test_status_not_participate }}</p>
                                <users-popup
                                    :title="'Не участвовали'"
                                    classButton="dashboard_study__info-button"
                                    :id="'test_status_not_participate' + content.id"
                                    index="2"
                                    v-if="content.test_status_not_participate"
                                    :project_id="project.project.id"
                                    is_test="1"
                                    :content_id="content.id"/>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard_study__block width-full" v-if="content.article">
                        <div class="dashboard_study__block-head space-between">
                            <p class="dashboard_study__block-title">Статьи</p>
                            <a :href="'/admin/api/v1/export-users-article/' + project.project.id + '/' + content.id" class="dashboard_study__block-download"><span>Скачать отчёт пакета</span></a>
                        </div>
                        <div class="dashboard_study__status">
                            <div class="dashboard_main__status-content width-100">
                                <p class="dashboard_main__status-description">{{ percentActive(content.article_status_active, project.user_total) }}% выполненых</p>
                                <div class="dashboard_main__status-line">
                                    <span :style="'width:'+percentActive(content.article_status_active, project.user_total)+'%;'"></span>
                                </div>
                                <p class="dashboard_main__status-description">{{ project.user_total }} активностей</p>
                            </div>
                        </div>
                        <div class="dashboard_study__info">
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">{{ content.article_status_active }}</p>
                                <users-popup
                                    :title="'Выполнили активности'"
                                    classButton="dashboard_study__info-button"
                                    :id="'article_status_active' + content.id"
                                    index="1"
                                    v-if="content.article_status_active"
                                    :project_id="project.project.id"
                                    is_article="1"
                                    :content_id="content.id"/>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">{{ content.article_status_not_active }}</p>
                                <users-popup
                                    :title="'Не выполнили активности'"
                                    classButton="dashboard_study__info-button"
                                    :id="'article_status_not_active' + content.id"
                                    index="0"
                                    v-if="content.article_status_not_active"
                                    :project_id="project.project.id"
                                    is_article="1"
                                    :content_id="content.id"/>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">{{ content.article_status_not_participate }}</p>
                                <users-popup
                                    :title="'Не участвовали'"
                                    classButton="dashboard_study__info-button"
                                    :id="'article_status_not_participate' + content.id"
                                    index="2"
                                    v-if="content.article_status_not_participate"
                                    :project_id="project.project.id"
                                    is_article="1"
                                    :content_id="content.id"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-content>
    <v-preloader v-else />
</template>
<script>
    import VContent from "./templates/Content"
    import ProjectListSidebar from "./templates/project/list/dashboard"
    import {PROJECT} from "../api/endpoints"
    import Cover from "./fragmets/cover-project"
    import UsersPopup from "./templates/dashboard/UsersPopup"
    import TestPopup from "./templates/dashboard/TestPopup"
    import VPreloader from "./fragmets/preloader"
    import { percentDashboard } from './../utils'

    export default {
        name: "Dashboard",
        components: {
            ProjectListSidebar,
            VContent,
            Cover,
            UsersPopup,
            VPreloader,
            TestPopup
        },
        data() {
            return {
                project: {},
                isLoaded: false
            }
        },
        methods: {
            async loadProject() {
                let projectId = this.$route.params.projectId

                this.$get(PROJECT + '/' + projectId + '?all_items=1').then(response => {

                    if (response.data) {
                        this.project = response.data
                        setTimeout(() => {
                            let canvas = document.getElementById("dashboardCircle");
                            let not_participate = this.project.status_not_participate;
                            let active = this.project.status_active;
                            let not_active = this.project.status_not_active;
                            let user_total = this.project.user_total;

                            let per_active = 0;
                            if (active) {
                                per_active = percentDashboard(active, user_total);
                            }

                            let per_not_active = 0;
                            if (not_active) {
                                per_not_active = percentDashboard(not_active, user_total);
                            }

                            let per_not_participate = 0;
                            if (not_participate) {
                                per_not_participate = percentDashboard(not_participate, user_total);
                            }

                            if (canvas) {
                                let ctx = canvas.getContext("2d");
                                let lastend = 0;
                                let data = [parseFloat(per_not_participate), parseFloat(per_active), parseFloat(per_not_active)];
                                let myTotal = 0;
                                let myColor = ['#00B7FF', '#4CF99E', '#FF608D'];

                                for (let e = 0; e < data.length; e++) {
                                    myTotal += data[e];
                                }
                                let off = 0
                                let w = (canvas.width - off) / 2
                                let h = (canvas.height - off) / 2
                                for (let i = 0; i < data.length; i++) {
                                    ctx.fillStyle = myColor[i];
                                    ctx.beginPath();
                                    ctx.moveTo(w, h);
                                    let len = (data[i] / myTotal) * 2 * Math.PI
                                    let r = h - off / 2
                                    ctx.arc(w, h, r, lastend, lastend + len, false);
                                    ctx.fill();
                                    ctx.fillStyle = 'white';
                                    ctx.font = "bold 14px Montserrat";
                                    ctx.textAlign = "center";
                                    ctx.textBaseline = "middle";
                                    let mid = lastend + len / 2
                                    ctx.fillText(data[i] + "%", w + Math.cos(mid) * (r / 2), h + Math.sin(mid) * (r / 2));
                                    lastend += Math.PI * 2 * (data[i] / myTotal);
                                }
                            }
                        }, 2000);
                        this.isLoaded = true;
                    }
                })
            },
            percentActive(status, total) {
                if (status) {
                    let result = status / total;
                    result = Math.ceil(result * 100);
                    return parseInt(result);
                }

                return 0;
            }
        },
        mounted() {
            this.loadProject()
        }
    }
</script>
<style scope>
.dashboard_study-title, .dashboard_study__block-title{
    height: 60px;
}
</style>
