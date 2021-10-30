<template>
    <v-content v-if="project">
        <template v-slot:sidebar>
            <project-list-sidebar :options="project.options"/>
        </template>

        <div class="dashboard">
            <div class="dashboard-head">
                <div class="dashboard__title" v-if="project.options">
                    <cover :image="project.options.files.cover" :title="project.options.title" class="dashboard__title-logo"/>
                    <p class="dashboard__title-text">{{ project.options.title }}</p>
                </div>
                <div class="dashboard__info" v-if="project.item">
                    <p class="dashboard__info-title">Дата запуска проекта:</p>
                    <p class="dashboard__info-data">{{ project.item.created_at.substr(0, 10) }}</p>
                </div>
            </div>
            <div class="dashboard_main">
                <div class="dashboard_main-head">
                    <p class="dashboard_main-total">Активностей <b>700</b></p>
                    <div class="dashboard_main__status">
                        <p class="dashboard_main__status-title">Статус активностей</p>
                        <div class="dashboard_main__status-content">
                            <p class="dashboard_main__status-description">30% выполненых</p>
                            <div class="dashboard_main__status-line">
                                <span style="width:30%;"></span>
                            </div>
                            <p class="dashboard_main__status-description">210 активностей</p>
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
                                <p class="dashboard_main__item-quantity">210</p>
                            </div>
                            <users-popup :title="'Выполнили активности'"/>
                        </div>
                        <div class="dashboard_main__item">
                            <div class="dashboard_main__item-wrap">
                                <p class="dashboard_main__item-status dashboard_main__item-status--red"><span>Не выполнили активности</span></p>
                                <p class="dashboard_main__item-quantity">70</p>
                            </div>
                            <button class="dashboard_main__item-button" type="button">Смотреть</button>
                        </div>
                        <div class="dashboard_main__item">
                            <div class="dashboard_main__item-wrap">
                                <p class="dashboard_main__item-status dashboard_main__item-status--blue"><span>Не участвовали</span></p>
                                <p class="dashboard_main__item-quantity">420</p>
                            </div>
                            <button class="dashboard_main__item-button" type="button">Смотреть</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard_study">
                <div class="dashboard_study-box">
                    <div class="dashboard_study__head">
                        <p class="dashboard_study-title">Исследование 1.1</p>
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
                            <button class="dashboard_study__block-more">Подробней</button>
                        </div>
                        <div class="dashboard_study__status">
                            <div class="dashboard_main__status-content width-100">
                                <p class="dashboard_main__status-description">30% выполненых</p>
                                <div class="dashboard_main__status-line">
                                    <span style="width:30%;"></span>
                                </div>
                                <p class="dashboard_main__status-description">210 активностей</p>
                            </div>
                        </div>
                        <div class="dashboard_study__info">
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">210</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">23</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">73</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard_study__block width-full">
                        <div class="dashboard_study__block-head space-between">
                            <p class="dashboard_study__block-title">Статьи</p>
                            <button class="dashboard_study__block-download"><span>Скачать отчёт пакета</span></button>
                        </div>
                        <div class="dashboard_study__status">
                            <div class="dashboard_main__status-content width-100">
                                <p class="dashboard_main__status-description">30% выполненых</p>
                                <div class="dashboard_main__status-line">
                                    <span style="width:30%;"></span>
                                </div>
                                <p class="dashboard_main__status-description">210 активностей</p>
                            </div>
                        </div>
                        <div class="dashboard_study__info">
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">210</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">23</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">73</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard_study-box">
                    <div class="dashboard_study__head">
                        <p class="dashboard_study-title">Исследование 1.2</p>
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
                            <button class="dashboard_study__block-more">Подробней</button>
                        </div>
                        <div class="dashboard_study__status">
                            <div class="dashboard_main__status-content width-100">
                                <p class="dashboard_main__status-description">30% выполненых</p>
                                <div class="dashboard_main__status-line">
                                    <span style="width:30%;"></span>
                                </div>
                                <p class="dashboard_main__status-description">210 активностей</p>
                            </div>
                        </div>
                        <div class="dashboard_study__info">
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">210</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">23</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">73</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard_study__block width-full">
                        <div class="dashboard_study__block-head space-between">
                            <p class="dashboard_study__block-title">Статьи</p>
                            <button class="dashboard_study__block-download"><span>Скачать отчёт пакета</span></button>
                        </div>
                        <div class="dashboard_study__status">
                            <div class="dashboard_main__status-content width-100">
                                <p class="dashboard_main__status-description">30% выполненых</p>
                                <div class="dashboard_main__status-line">
                                    <span style="width:30%;"></span>
                                </div>
                                <p class="dashboard_main__status-description">210 активностей</p>
                            </div>
                        </div>
                        <div class="dashboard_study__info">
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">210</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">23</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">73</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard_study-box">
                    <div class="dashboard_study__head">
                        <p class="dashboard_study-title">Исследование 1.3</p>
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
                            <button class="dashboard_study__block-moderation">Модерация</button>
                        </div>
                        <div class="dashboard_study__status">
                            <div class="dashboard_main__status-content width-100">
                                <p class="dashboard_main__status-description">30% выполненых</p>
                                <div class="dashboard_main__status-line">
                                    <span style="width:30%;"></span>
                                </div>
                                <p class="dashboard_main__status-description">210 активностей</p>
                            </div>
                        </div>
                        <div class="dashboard_study__info">
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">210</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">23</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">73</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard_study__block width-full">
                        <div class="dashboard_study__block-head space-between">
                            <p class="dashboard_study__block-title">Статьи</p>
                            <button class="dashboard_study__block-download"><span>Скачать отчёт пакета</span></button>
                        </div>
                        <div class="dashboard_study__status">
                            <div class="dashboard_main__status-content width-100">
                                <p class="dashboard_main__status-description">30% выполненых</p>
                                <div class="dashboard_main__status-line">
                                    <span style="width:30%;"></span>
                                </div>
                                <p class="dashboard_main__status-description">210 активностей</p>
                            </div>
                        </div>
                        <div class="dashboard_study__info">
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">210</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">23</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                            <div class="dashboard_study__info-block">
                                <p class="dashboard_study__info-data">73</p>
                                <button class="dashboard_study__info-button">Смотреть</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-content>
</template>
<script>
    import VContent from "./templates/Content"
    import ProjectListSidebar from "./templates/project/list/dashboard"
    import {PROJECT} from "../api/endpoints"
    import Cover from "./fragmets/cover-project"
    import UsersPopup from "./templates/dashboard/UsersPopup"

    export default {
        name: "Dashboard",
        components: {
            ProjectListSidebar,
            VContent,
            Cover,
            UsersPopup
        },
        data() {
            return {
                project: {},
            }
        },
        methods: {
            async loadProject() {
                let projectId = this.$route.params.projectId

                this.$get(PROJECT + '/' + projectId).then(response => {

                    if (response.data) {
                        this.project = response.data
                    }
                })
            }
        },
        mounted() {
            this.loadProject()
        }
    }

    $(document).ready(function() {
        let canvas = document.getElementById("dashboardCircle");

        if (canvas) {
            let ctx = canvas.getContext("2d");
            let lastend = 0;
            let data = [60, 30, 10];
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
    });
</script>
