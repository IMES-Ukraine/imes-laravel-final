<template>
    <v-content>
        <template v-slot:sidebar>
            <project-list-sidebar/>
        </template>

        <div class="dashboard">
            <div class="dashboard-head">
                <div class="dashboard__title">
                    <img src="img/articles-logo-1.png" class="dashboard__title-logo" alt="">
                    <p class="dashboard__title-text">Исследование №1</p>
                </div>
                <div class="dashboard__info">
                    <p class="dashboard__info-title">Дата запуска проекта:</p>
                    <p class="dashboard__info-data">11.11.2020</p>
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
                            <button class="dashboard_main__item-button">Смотреть</button>
                        </div>
                        <div class="dashboard_main__item">
                            <div class="dashboard_main__item-wrap">
                                <p class="dashboard_main__item-status dashboard_main__item-status--red"><span>Не выполнили активности</span></p>
                                <p class="dashboard_main__item-quantity">70</p>
                            </div>
                            <button class="dashboard_main__item-button">Смотреть</button>
                        </div>
                        <div class="dashboard_main__item">
                            <div class="dashboard_main__item-wrap">
                                <p class="dashboard_main__item-status dashboard_main__item-status--blue"><span>Не участвовали</span></p>
                                <p class="dashboard_main__item-quantity">420</p>
                            </div>
                            <button class="dashboard_main__item-button">Смотреть</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard_study">
                <div class="dashboard_study-box">
                    <div class="dashboard_study__head">
                        <p class="dashboard_study-title">Исследование 1.1</p>
                        <div class="dashboard_study__head-list">
                            <p>Статус активностей</p>
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
                    <div class="dashboard_study__block">
                        <div class="dashboard_study__block-head">
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
                            <p>Статус активностей</p>
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
                    <div class="dashboard_study__block">
                        <div class="dashboard_study__block-head">
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
                            <p>Статус активностей</p>
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
                    <div class="dashboard_study__block">
                        <div class="dashboard_study__block-head">
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
    import VContent from "./templates/Content";
    import ProjectListSidebar from "./templates/project/list/dashboard";

    export default {
        name: "Dashboard",
        components: {ProjectListSidebar, VContent},
    }
</script>
