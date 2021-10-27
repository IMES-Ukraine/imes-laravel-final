<template>

    <v-content>

        <template v-slot:sidebar>
            <project-form-sidebar
            :tag="project.tag"/>
        </template>

        <div class="articles">
            <div class="articles_create">
                <project-close/>
                <project-alert-test/>
                <ValidationObserver ref="form" v-slot="{ handleSubmit }">
                    <form class="articles_create-box">
                        <div v-show="getStep == 1">
                            <div class="articles_create-block">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Обложка</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__item-file width-auto buttonAddFile">
                                            <input type="file" name="cover" @change="handleUpload('cover')">
                                            <p><span data-placeholder="Загрузить"></span></p>
                                            <button class="delete_file deleteFile" type="button"></button>
                                        </div>
                                        <div v-if="errorFile" class="errors">{{ errorFile }}</div>
                                    </div>
                                </div>
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Название</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__name">
                                            <div class="articles_create__name-block">
                                                <input type="text" name="name" v-model="options.title">
                                                <p class="articles_create__name-note">50 символов</p>
                                                <div v-if="errorTitle" class="errors">{{ errorTitle }}</div>
                                            </div>
                                            <div class="articles_create__name-block articles_create__name-block--number">
                                                <input type="text" name="tag" v-model="project.tag" placeholder="#">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="articles_create-block">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Аудитория</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__grid width-half column-gap-50">
                                            <div class="articles_create__grid-block">
                                                <button class="articles_create-add_btn" @click="showTargeting" type="button"><span class="icon-left">Добавить</span></button>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__item-file buttonAddFile">
                                                    <input type="file" name="audience" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" @change="handleUpload('audience')">
                                                    <p><span data-placeholder="Загрузить персональную аудиторию"></span></p>
                                                    <button class="delete_file deleteFile" type="button"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="articles_create-block" v-show="targeting">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Таргетинг</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__grid width-half column-gap-50">
                                            <div class="articles_create__grid-block">
                                                <select class="articles_create-select" v-model="options.selected.category">
                                                    <option v-for="item in options.category" :value="item.id" :key="item.id">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__several">
                                                    <div class="articles_create__several-block">
                                                        <select class="articles_create-select" v-model="options.selected.region">
                                                            <option v-for="item in options.region" :value="item.id" :key="item.id">
                                                                {{ item.name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <!--<div class="articles_create__several-block">
                                                        <select class="articles_create-select">
                                                            <option value="1" selected>Вся Украина</option>
                                                            <option value="2">Харьковская обл.</option>
                                                            <option value="3">Киевская обл.</option>
                                                        </select>
                                                        <button class="articles_create-plus"></button>
                                                    </div>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="articles_create-block" v-show="block_content">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title height-47">Контент</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__grid width-third column-gap-25">
                                            <div class="articles_create__grid-block">
                                                <button class="articles_create-add_btn height-47" type="button" @click.prevent="setStep(2)"><span class="icon-right">Создать</span></button>
                                            </div>
                                            <!--<div class="articles_create__grid-block">
                                                <div class="articles_create__study">
                                                    <p class="articles_create__study-title">Исследование 1.1</p>
                                                    <div class="articles_create__study-controls">
                                                        <button class="articles_create__study-button articles_create__study-button--edit"></button>
                                                        <button class="articles_create__study-button articles_create__study-button--delete"></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__study">
                                                    <p class="articles_create__study-title">Исследование 1.2</p>
                                                    <div class="articles_create__study-controls">
                                                        <button class="articles_create__study-button articles_create__study-button--edit"></button>
                                                        <button class="articles_create__study-button articles_create__study-button--delete"></button>
                                                    </div>
                                                </div>
                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--<div class="row mb-4" style="display: none;" id="block_content">
                                <div class="article-edit__text col-3">
                                    Контент
                                </div>
                                <div class="col-3">
                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                        <div @click.prevent="setStep(2)">
                                            <PlusButton label="Створити"></PlusButton>
                                            <input class="form-control" type="hidden" v-model="questions">
                                            <input class="form-control" type="hidden" v-model="articles">
                                        </div>
                                        <span class="errors">{{ errors[0] }}</span>
                                    </ValidationProvider>
                                </div>

                            </div>-->

                            <button class="articles_create-submit button-border" type="button" @click="showContent">
                                <template v-if="finishProject">Сохранить</template>
                                <template v-else>Далее</template>
                            </button>
                        </div>

                        <div v-show="getStep == 2">
                            <project-close/>
                            <p class="articles_create-title">Создание пакета контента</p>
                            <div class="articles_create-box">
                                <div class="articles_create-block">
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title">Выбор шаблона</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__grid width-half column-gap-50">
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__sorting">
                                                        <input type="radio" name="sorting-1" checked="">
                                                        <p><span class="icon-plus">Партнерский пакет №1</span></p>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__sorting">
                                                        <input type="radio" name="sorting-1">
                                                        <p><span class="icon-plus">Уникальный пакет</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="articles_create-block">
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title">Название</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__name-block">
                                                <validation-provider
                                                    rules="required|max:35"
                                                    :custom-messages="{ max: 'Максимальное количество 35 символов' }"
                                                    v-slot="{ errors }">

                                                    <input
                                                        class="form-control"
                                                        type="text"
                                                        v-model="content.title">
                                                    <span class="errors">{{ errors[0] }}</span>
                                                </validation-provider>
                                                <p class="articles_create__name-note">35 символов</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title height-47">Статьи</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__grid width-main-1">
                                                <div class="articles_create__grid-block">
                                                    <button class="articles_create-add_btn height-47" type="button" @click.prevent="setStep(3)"><span class="icon-right">Создать</span></button>
                                                    <div id="add_new_article" v-show="add_new_article">
                                                        <div class="articles_create__study" v-for="article in articles" v-if="article.title">
                                                            <p class="articles_create__study-title">{{ article.title }}</p>
                                                            <div class="articles_create__study-controls">
                                                                <button class="articles_create__study-button articles_create__study-button--edit" @click.prevent="setStep(3)" type="button"></button>
                                                                <button class="articles_create__study-button articles_create__study-button--delete" @click.prevent="reloadBlockArticle" type="button"></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="errorNewArticle" class="errors">{{ errorNewArticle }}</div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Количество</p>
                                                        <validation-provider rules="required" v-slot="{ errors }">
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                name="name"
                                                                v-model="content.count">

                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Баллы</p>
                                                        <validation-provider
                                                            rules="required"
                                                            v-slot="{ errors }">

                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                name="name"
                                                                @change="onChange"
                                                                v-model="content.points">

                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Частота</p>
                                                        <validation-provider
                                                            rules="required"
                                                            v-slot="{ errors }">

                                                            <input
                                                                class="form-control"
                                                                type="number"
                                                                name="name"
                                                                v-model="content.frequency">

                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title height-47">Тесты</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__grid width-main-1">
                                                <div class="articles_create__grid-block">
                                                    <button class="articles_create-add_btn height-47" type="button" @click.prevent="setStep(4)"><span class="icon-right">Создать</span></button>
                                                    <div class="articles_create__study" id="add_new_test" v-show="add_new_test">
                                                        <p class="articles_create__study-title">Статья 1.1. Заголовок статьи</p>
                                                        <div class="articles_create__study-controls">
                                                            <button class="articles_create__study-button articles_create__study-button--edit" type="button" @click.prevent="setStep(4)"></button>
                                                            <button class="articles_create__study-button articles_create__study-button--delete" type="button" @click.prevent="reloadBlockSurveyTest"></button>
                                                        </div>
                                                    </div>
                                                    <div v-if="errorNewTest" class="errors">{{ errorNewTest }}</div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Количество</p>
                                                        <validation-provider
                                                            rules="required"
                                                            v-slot="{ errors }">

                                                            <input
                                                                class="form-control"
                                                                type="number"
                                                                name="name"
                                                                v-model="test.count">
                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <p class="articles_create__field_with_label-label">Баллы</p>
                                                        <validation-provider
                                                            rules="required"
                                                            v-slot="{ errors }">

                                                            <input
                                                                class="form-control"
                                                                type="number"
                                                                name="name"
                                                                @change="onChange"
                                                                v-model="test.points">

                                                            <span class="errors">{{ errors[0] }}</span>
                                                        </validation-provider>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__field_with_label">
                                                        <v-checkbox
                                                            :id="'can-retake-button'"
                                                            :name="'can-retake-button'"
                                                            v-model="test.canRetake"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="articles_create-line" v-show="is_points"></div>
                                <div class="articles_create-note" v-show="is_points">1 пользователь = 1 тест + 1 статья = <p>0</p> баллов</div>
                            </div>

                            <button class="articles_create-submit button-gradient" type="button" @click="showFirstContent" v-show="is_points">сохранить</button>
                        </div>

                        <div v-show="getStep == 3">
                            <p class="articles_create-title">Создание статьи</p>
                            <div class="articles_create-box">
                                <div class="articles_create-block">
                                    <div class="articles_create__item mb37">
                                        <p class="articles_create__item-title">Название</p>
                                        <div class="articles_create__item-content direction-column">
                                            <div class="articles_create__name-block">
                                                <input type="text" name="name" v-model="articles[index_article].title">
                                                <div v-if="errorArticleTitle" class="errors">{{ errorArticleTitle }}</div>
                                            </div>
                                            <div class="articles_create__radio_circle">
                                                <div class="articles_create__radio_circle-block">
                                                    <input type="radio" id="typePublication_1"
                                                           name="typePublication"
                                                           :value="1"
                                                           :checked="(articleType==1)?true:false"
                                                           v-on:update:value="getType">
                                                    <i></i>
                                                    <p>Новости</p>
                                                </div>
                                                <div class="articles_create__radio_circle-block">
                                                    <input type="radio" id="typePublication_2"
                                                           name="typePublication"
                                                           :value="2"
                                                           :checked="(articleType==2)?true:false"
                                                           v-on:update:value="getType">
                                                    <i></i>
                                                    <p>Информация</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles_create__item half">
                                        <p class="articles_create__item-title">Обложка</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__item-file width-auto buttonAddFile">
                                                <input
                                                    type="file"
                                                    id="articleCover"
                                                    name="addCover"
                                                    class="input-file-hidden"
                                                    v-on:change="handleUploadArticle"
                                                    role="button"
                                                >
                                                <p><span data-placeholder="Загрузить">Загрузить</span></p>
                                                <button class="delete_file deleteFile" id="deleteFileArticle" type="button"></button>
                                            </div>
                                            <div v-if="errorArticleCover" class="errors">{{ errorArticleCover }}</div>
                                        </div>
                                    </div>
                                    <div class="articles_create__item half">
                                        <p class="articles_create__item-title">Галерея</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__media">
                                                <SimpleTestMedia :media="articles[index_article].multiples"></SimpleTestMedia>
                                                <div class="articles_create__media-add">
                                                    <input type="file" name="file" id="article_multiples" @change="addMedia($event)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="articles_create__item mb54">
                                        <p class="articles_create__item-title">Текст</p>
                                        <div class="articles_create__item-content">
                                            <textarea
                                                class="form-control"
                                                rows="4"
                                                v-model="articles[index_article].text"
                                            ></textarea>
                                        </div>
                                        <div class="errors" v-if="errorArticleText">{{errorArticleText}}</div>
                                    </div>
                                    <!--<div class="articles_create__item">
                                        <p class="articles_create__item-title">Категории</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__addition">
                                                <div class="articles_create__addition-block">
                                                    <div class="articles_create__addition-select">
                                                        <select class="my-ui-select articles_create-select">
                                                            <option value="1">Значение 1</option>
                                                            <option value="2">Значение 2</option>
                                                            <option value="3">Значение 3</option>
                                                            <option value="4">Значение 4</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="articles_create__addition-block">
                                                    <div class="articles_create__addition-field">
                                                        <input type="text" name="text" class="">
                                                    </div>
                                                    <button class="articles_create__addition-button">Добавить категорию</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    <!--<div class="articles_create__item">
                                        <p class="articles_create__item-title">Рубрики</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__addition">
                                                <div class="articles_create__addition-block">
                                                    <div class="articles_create__addition-select">
                                                        <select class="my-ui-select articles_create-select">
                                                            <option value="1">Значение 1</option>
                                                            <option value="2">Значение 2</option>
                                                            <option value="3">Значение 3</option>
                                                            <option value="4">Значение 4</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="articles_create__addition-block">
                                                    <div class="articles_create__addition-field">
                                                        <input type="text" name="text" class="">
                                                    </div>
                                                    <button class="articles_create__addition-button">Добавить рубрику</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title">Автор</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__addition">
                                                <div class="articles_create__addition-block width-194">
                                                    <div class="articles_create-multiselect">
                                                        <multiselect
                                                            v-model="user_id"
                                                            tag-placeholder="Обрати автора"
                                                            placeholder="Обрати автора"
                                                            label="name"
                                                            track-by="id"
                                                            :searchable="false"
                                                            :close-on-select="true"
                                                            :show-labels="false"
                                                            :options="authors"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="articles_create__addition-block">
                                                    <div class="articles_create__addition-field">
                                                        <input type="text" id="new_user" v-model="new_user" />
                                                    </div>
                                                    <button class="articles_create__addition-button" type="button" @click="AddNewUser">Добавить автора</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <article-form-insert
                                        v-bind:insert.sync="insert"
                                        v-bind:textInsert.sync="textInsert"
                                        @insert="insertStore"
                                    />
                                    <article-form-button
                                        :label="'Статья-ссылка'"
                                        :id="'is-article-button'"
                                        :name="'is-article-button'"
                                        :text_button="text_button"
                                        @update="buttonStore"
                                    />
                                    <article-form-button
                                        :label="'Кнопка “Читати ще”'"
                                        :id="'is-article-more'"
                                        :name="'is-article-more'"
                                        :text_button="text_button"
                                        @update="buttonStore"
                                    />
                                    <article-form-button
                                        :label="'Кнопка “Розпочати дослiдження”'"
                                        :id="'is-article-info'"
                                        :name="'is-article-info'"
                                        :text_button="text_button"
                                        @update="buttonStore"
                                    />
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title">Реком. статьи</p>
                                        <div class="articles_create__item-content">
                                            <multiselect
                                                v-model="chosenRecommended"
                                                tag-placeholder="Додати статтю"
                                                placeholder="Вибрати статтю"
                                                label="title"
                                                track-by="id"
                                                :options="recommended"
                                                :multiple="true"
                                                :taggable="true"
                                                :show-labels="false"
                                                :close-on-select="false"
                                                @tag="addTag"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="articles_create-submit button-gradient" type="button" @click="saveArticle">опубликовать</button>
                        </div>

                        <div v-show="getStep == 4">
                            <p class="articles_create-title">Создание теста</p>
                            <project-close/>

                            <div class="articles_create-block">
                                <div class="articles_create__item">
                                    <p class="articles_create__item-title">Создание</p>
                                    <div class="articles_create__item-content">
                                        <div class="articles_create__grid width-half column-gap-50">
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__sorting">
                                                    <input type="radio" name="radio" id="one" value="test" v-model="picked" @change="onChangePicked($event, index_test)" checked="">
                                                    <p><span class="icon-plus">Теста</span></p>
                                                </div>
                                            </div>
                                            <div class="articles_create__grid-block">
                                                <div class="articles_create__sorting">
                                                    <input type="radio" name="radio" id="two" value="survey" @change="onChangePicked($event, index_test)" v-model="picked">
                                                    <p><span class="icon-plus">Опроса</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-12">
                                <input type="radio" name="radio" id="one" value="test" v-model="picked">
                                <label for="one">Теста</label>
                                <input type="radio" name="radio" id="two" value="survey" v-model="picked">
                                <label for="two">Опроса</label>
                                <br>
                            </div>-->

                            <div v-if="picked === 'test'">
                                <div class="articles_create-block">
                                    <div class="articles_create__item">
                                        <p class="articles_create__item-title">Формат теста</p>
                                        <div class="articles_create__item-content">
                                            <div class="articles_create__grid width-half column-gap-50">
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__sorting">
                                                        <input type="radio" name="radio_type" id="easy" value="easy" v-model="type" checked="">
                                                        <p><span class="icon-plus">Простой</span></p>
                                                    </div>
                                                </div>
                                                <div class="articles_create__grid-block">
                                                    <div class="articles_create__sorting">
                                                        <input type="radio" name="radio_type" id="complex" value="complex" v-model="type">
                                                        <p><span class="icon-plus">Сложный</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="type === 'easy'">
                                    <div v-for="item in questions" v-bind:key="item.title">

                                        <Question
                                            :question="item.question"
                                            :variants="item.variants"
                                            :answer="item.answer"></Question>
                                    </div>
                                    <div class="mb20"></div>
                                    <button class="articles_create-submit button-gradient" type="button" @click="saveTest">сохранить</button>
                                </div>

                                <div v-if="type === 'complex'">
                                    <div v-for="item in questions" v-bind:key="item.title">
                                        <TestComplex :question="item.question"
                                                    :variants="item.variants"
                                                    :answer="item.answer"></TestComplex>
                                    </div>
                                    <div class="mb20"></div>
                                    <button class="articles_create-submit button-gradient" type="button" @click="saveTest">сохранить</button>
                                </div>
                            </div>

                            <div v-if="picked === 'survey'">
                                <div v-for="item in questions" v-bind:key="item.title">
                                    <TestSurvey :question="item.question"
                                                :variants="item.variants"
                                                :errorTestSurveyTitle="errorTestSurveyTitle"
                                                :errorTestSurveyText="errorTestSurveyText"
                                                :answer="item.answer"></TestSurvey>
                                </div>
                                <div class="mb20"></div>
                                <button class="articles_create-submit button-gradient" type="button" @click="saveTestSurvey">сохранить</button>
                            </div>
                        </div>

                        <div v-if="getStep == 5">
                            <div class="row mb-4">
                                <div class="article-edit__text col-3">
                                    Статус запуска проекта
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            Ayдитория
                                        </div>
                                        <div class="col-12">
                                            Пользователи
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 mb-2 smaller-text__article">
                                            1000
                                        </div>
                                        <div class="col-12 smaller-text__article">
                                            700
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-12 mb-2 smaller-text__article">
                                            Количество активности
                                        </div>
                                        <div class="col-12">
                                            1000
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </ValidationObserver>
            </div>
        </div>
    </v-content>
</template>
<script>
    //import {required/*,minLength*/} from 'vuelidate/lib/validators'
    import {ARTICLE, USER, USER_CREATE_NAME, PROJECT, ARTICLE_COVER, TOKEN, PROJECT_IMAGE} from "../api/endpoints";
    import { ValidationProvider, ValidationObserver } from 'vee-validate'
    import PlusButton from './controls/PlusButton'
    import VContent from "./templates/Content"
    import axios from 'axios'
    import ProjectFormSidebar from "./templates/project/form/sidebar"
    import ProjectClose from "./templates/project/Close"
    import ProjectAlertTest from "./templates/project/AlertTest"
    import VCheckbox from "./templates/inputs/checkbox"
    import ArticleInputText from "./templates/article/form/text"
    import FragmentFormText from "./fragmets/text"
    import Multiselect from "vue-multiselect"
    import VButton from "./templates/inputs/button"
    import ArticleFormButton from "./templates/article/form/button"
    import ArticleFormInsert from "./templates/article/form/insert"
    import ArticleMultiple from "./templates/article/form/multiple"
    import Cover from "./fragmets/cover"
    import ArticleInputTitle from "./templates/article/form/title"
    import Question from './fragmets/TestQuestion.vue'
    import TestSurvey from './fragmets/TestSurvey.vue'
    import TestComplex from './fragmets/TestComplex.vue'
    import VRadio from "./templates/inputs/radio"
    import SimpleTestMedia from "./fragmets/SimpleTestMedia"
    import { getRandomId } from './../utils'
    import VTextarea from "./templates/inputs/textarea"

    export default {
        name: 'CreateProjectForm',
        components: {
            VContent,
            ProjectFormSidebar,
            ValidationProvider,
            ValidationObserver,
            PlusButton,
            ProjectClose,
            ProjectAlertTest,
            VCheckbox,
            ArticleInputText,
            FragmentFormText,
            Multiselect,
            VButton,
            ArticleFormButton,
            ArticleFormInsert,
            ArticleMultiple,
            ArticleInputCover: Cover,
            ArticleInputTitle,
            TestComplex,
            Question,
            TestSurvey,
            VRadio,
            SimpleTestMedia,
            VTextarea
        },
        data() {
            return {
                options: {
                    ...this.$store.state.project.options
                },
                ...this.$store.state.articles[0],
                articles: this.$store.state.articles,
                tests: this.$store.state.questions,
                questions: this.$store.state.questions,
                project: {},
                errorFile: '',
                errorTitle: '',
                errorArticleTitle: '',
                errorArticleCover: '',
                errorArticleText: '',
                errorNewTest: '',
                errorNewArticle: '',
                errorTestSurveyTitle: '',
                errorTestSurveyText: '',
                currentStep: 1,
                isComplex: false,
                content: {
                    ...this.$store.state.content
                },
                test: this.$store.state.tests,
                picked: 'test',
                type: 'easy',
                new_user: '',
                targeting: false,
                block_content: false,
                is_points: false,
                add_new_test: false,
                add_new_article: false,
                index_article: 0,
                index_test: 0
            }
        },
        computed: {
            /*getStep() {
                return this.$store.getters.currentStep
            },*/
            getStep() {
                return this.currentStep
            },
            getTitle() {
                console.log(this.$store.state.currentStep)
                return this.$store.state.currentStep
            },
            finishProject() {
                return this.$store.state.currentStep > 4
            },
            getPayload() {
                return {
                    options: this.options,
                    articles: this.articles,
                    tests: this.questions,
                    entity: this.entity,
                }
            }
        },
        props: {
            msg: String
        },
        methods: {
            sendForm() {
                axios.get(`http://jsonplaceholder.typicode.com/posts`).then(
                        response => {
                            this.title = response.data
                        }
                    )

                    let formData = new FormData();
                    formData.append('coverImage', this.files.cover)
                    formData.append('audienceDatabase', this.files.audience)

                    axios.post(`http://jsonplaceholder.typicode.com/posts`, formData).then(
                        response => {
                            this.title = response.data.id
                        }
                    )
            },
            /*showStep(step) {
                this.currentStep++
                this.$store.dispatch('nextStep')
            },*/
            submitForm() {
                this.$refs.form.validate().then( success => {
                    if(!success) {
                        if (this.options.files.cover == null) {
                            this.errorFile = 'Поле обязательно';
                        } else {
                            this.errorFile = '';
                        }

                        return;
                    }

                    let content = {
                        "title": this.content.title,
                        "article": {
                            "count": this.content.count,
                            "points": this.content.points,
                            "frequency": this.content.frequency
                        },
                        "test": {
                            "count": this.test.count,
                            "points": this.test.points,
                            "canRetake": this.test.canRetake
                        }
                    }

                    this.$post(PROJECT, {
                        options: this.options,
                        articles: this.articles,
                        tests: this.questions,
                        content: content
                    })
                        .then((res) => {
                            this.$router.push({ name: 'projectList' });
                        })
                        .catch((error) => {
                            console.log(error)
                        }).finally(() => {
                            console.log('success or error')
                        });

                    /*if ( this.isFinalStep ) {
                        this.$store.dispatch('createEntity', this.getPayload )
                    } else {
                        //this.currentStep++
                        this.$store.dispatch(
                            'storeProject', this.options
                        )
                        this.nextStep()
                    }*/
                })

            },
            handleUploadArticle(event) {
                let imageForm = new FormData()
                imageForm.append('file', event.target.files[0])

                axios.post(
                    ARTICLE_COVER + '/articles',
                    imageForm,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        params: {
                            access_token: TOKEN
                        },
                    }
                ).then((file) => {
                    this.name = event.target.files[0].name
                    //this.articles[0].imeges.push(file.data)
                    this.articles[this.index_article].images = file.data.data.id
                })
            },
            storeProject() {
                this.$store.dispatch('storeProject', this.options )
                //this.$store.dispatch('storeProject', this.project.options)
            },
            handleUpload(fileName) {
                //if ( typeof this.files[fileName] !== 'undefined' ) {
                    if (event.target.files[0].size <= 1024 * 1024 * 1024) {
                        //this.files[fileName] = event.target.files[0].name;
                        this.options.files.cover = event.target.files[0]
                    } else {
                        //this.$set(this.errorFile, 'cover', 'Изображение слишком большое')
                        this.errorFile = 'Изображение слишком большое';
                    }
                //}
            },
            showTargeting() {
                //$('#block_targeting').show()
                this.targeting = true
            },
            validate() {
                this.$refs.form.validate().then( success => {
                    if (!success) {
                        if (this.options.files.cover == null) {
                            this.errorFile = 'Поле обязательно';
                        } else {
                            this.errorFile = '';
                        }

                        return;
                    }
                });
            },
            showContent() {
                this.errorFile = '';
                this.errorTitle = '';

                if (this.options.files.cover == null) {
                    this.errorFile = 'Поле обязательно'
                }

                if (this.options.title === '') {
                    this.errorTitle = 'Требуется указать название'
                }

                if (this.options.title.length > 50) {
                    this.errorTitle = 'Требуется указать название не больше 50 символов'
                }

                if (this.errorTitle === '' && this.errorFile === '') {
                    this.block_content = true;
                }
            },
            showFirstContent() {
                this.errorNewTest = '';
                this.errorNewArticle = '';

                if (!this.add_new_test) {
                    this.errorNewTest = 'Тест обязательный'
                }

                if (!this.add_new_article) {
                    this.errorNewArticle = 'Статья обязательна'
                }

                this.$refs.form.validate().then( success => {
                    if (!success) {
                        return;
                    } else {
                        if (this.add_new_test && this.add_new_article) {
                            this.setStep(1)
                        }
                    }
                });
            },
            nextStep() {
                this.currentStep++
                this.$store.dispatch('nextStep')
            },
            setStep(step) {
                this.currentStep = step
                this.$store.dispatch('nextStep')
            },
            addContent() {
                this.$store.dispatch('addContent')
            },
            back() {
                this.$store.dispatch('projectList')
            },
            addTag (newTag) {
                const tag = {
                    name: newTag,
                    code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
                }
                this.recommended.push(tag)
                this.chosenRecommended.push(tag)
            },
            selectAuthor (user_id) {
                this.user_id = [user_id]
            },
            AddNewUser() {
                this.$get(USER_CREATE_NAME + '/' + this.new_user).then( response => {
                    this.authors = response.data
                })
            },
            buttonStore(value) {
                this.text_button = value
            },
            insertStore(value) {
                this.insert = value
            },
            linkStore(value) {
                this.link = value
            },
            articleTypeStore(value) {
                this.articleType = value
            },
            multiplesStore(value) {
                this.multiples = value
            },
            submitComplex() {
                //this.isComplex = true;
                //this.addComplexQuestion()
            },
            addComplexQuestion() {
                this.questions.push({
                    question: {
                        complex: {
                            title: '',
                            text: '',
                            points: null,
                            media: {
                                cover: null,
                                video: null,
                            },
                        }
                    },
                })
            },
            addQuestion() {
                this.questions.push({
                    question: {
                        title: '',
                        text: '',
                        description: '',
                        link: '',
                        button: null,
                        count: null,
                        points: null,
                        media: {
                            cover: null,
                            video: null,
                        },
                        isComplex: this.isComplex,
                        agreement: null
                    },
                    variants: [
                        {
                            itemId: 'variant-' + Math.random().toString(36).substr(2, 9),
                            title: 'A',
                            variant: '',
                            isCorrect: false,
                            answer: {
                                type: true,
                                right: true,
                                media: []
                            }
                        }
                    ],
                    answer: {
                        correct: [],
                        type: 'text' //answer type (variants | text field)
                    },
                })
            },
            update(value) {
                this.check = value
            },
            saveTestSurvey() {
                this.errorTestSurveyTitle = ''
                this.errorTestSurveyText = ''
                let error = false

                $('#survey-test input').css('border', '1px solid #D9D9D9')

                if (this.questions[0].question.title == '') {
                    this.errorTestSurveyTitle = 'Название обязательно'
                    error = true
                }

                if (this.questions[0].question.text == '') {
                    this.errorTestSurveyText = 'Вопрос обязателен'
                    error = true
                }

                for (const [index, value] of Object.entries(this.questions[0].variants)) {
                    if (value.variant == '') {
                        $('#variant-' + value.title).css('border', '1px solid red')
                        error = true
                    }
                }

                if (!error) {
                    this.add_new_test = true
                    $('#add_new_test .articles_create__study-title').html($('#survey_question_title').val());

                    this.currentStep = 2
                    this.$store.dispatch('nextStep')
                }
            },
            saveTest() {
                this.add_new_test = true
                $('#add_new_test .articles_create__study-title').html($('#question_title').val());

                this.currentStep = 2
                this.$store.dispatch('nextStep')
            },
            saveArticle() {
                this.errorArticleTitle = '';
                this.errorArticleCover = '';
                this.errorArticleText = '';
                let error = false;

                if (this.articles[this.index_article].title == null) {
                    this.errorArticleTitle = 'Поле обязательно'
                    error = true;
                }

                if (this.articles[this.index_article].text == null) {
                    this.errorArticleText = 'Поле обязательно'
                    error = true;
                }

                if (this.articles[this.index_article].images == null) {
                    this.errorArticleCover = 'Поле обязательно'
                    error = true;
                }

                if (!error) {
                    $('#add_new_article').show();
                    //$('#add_new_article .articles_create__study-title').html($('#article_title').val());

                    this.currentStep = 2
                    //this.index_article += 1
                    this.$store.dispatch('nextStep')

                    let obj = {
                        title: null,
                        articleType: 1,
                        type: null,
                        text: null,
                        tags: [],
                        category: 1,
                        headings: 1,
                        author: 1,
                        button: null,
                        text_button: null,
                        recommended: [],
                        authors: [],
                        user_id: [],
                        chosenRecommended: [],
                        images: null,
                        multiples: []
                    }

                    this.articles.push(obj)

                    this.index_article += 1

                    $('#deleteFileArticle').click()
                }
            },
            reloadBlockSurveyTest() {
                for (const [index, value] of Object.entries(this.questions)) {
                    this.questions.splice(index, 1)
                    //return
                }

                this.addQuestion()

                //$('#add_new_test').hide();
                this.add_new_test = false

                //this.currentStep = 4
                //this.$store.dispatch('nextStep')
            },
            reloadBlockTest() {
                for (const [index, value] of Object.entries(this.questions)) {
                    this.questions.splice(index, 1)
                    //return
                }

                this.addQuestion()

                //$('#add_new_test').hide();
                this.add_new_test = false

                //this.currentStep = 4
                //this.$store.dispatch('nextStep')
            },
            reloadBlockArticle() {
                for (const [index, value] of Object.entries(this.articles)) {
                    this.articles.splice(index, 1)
                    //return
                }

                //$('#add_new_article').hide();
            },
            getType (value) {
                this.$emit('update', value);
            },
            onChange() {
                let content_points = parseInt(this.content.points)
                let test_points = parseInt(this.test.points)

                if (content_points && test_points) {
                    this.is_points = true
                    $('.articles_create-note p').html(parseInt(this.content.points) + parseInt(this.test.points))
                }
            },
            addMedia(event) {
                let imageForm = new FormData()
                imageForm.append('file', event.target.files[0])

                axios.post(
                    ARTICLE_COVER + '/articles',
                    imageForm,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        params: {
                            access_token: TOKEN
                        },
                    }
                ).then((file) => {
                    /*this.name = event.target.files[0].name
                    this.articles[0].multiples = file.data.data
                    this.articles[0]['multiples'] = file.data.data.id*/
                    let obj = {
                        itemId: getRandomId(),
                        file: file.data.data.id,
                        name: event.target.files[0].name,
                        data: file.data,
                        path: file.data.data.path
                    };
                    this.articles[this.index_article].multiples.push(obj)
                    $('#article_multiples').val(null);
                })
            },
            onChangePicked(event, key) {
                for (const [index, value] of Object.entries(this.tests[key].variants)) {
                    if (index >= 2) {
                        this.tests[key].variants.splice(index, 1)
                        return
                    }
                }
            }
        },
        mounted() {
            this.addQuestion()
            this.$get(ARTICLE + '?count=12&type=1').then( response => {
                this.recommended = response.data
            })
            this.$get(USER + '?count=12').then( response => {
                this.authors = response.data
            })
        },
        /*validations: {
            title: {
                required
            },
            text: {
                required
            }
        },*/
    }
</script>
<style scoped>
  .input-file-label strong {
      font-size: 1.5em;
  }
  .smaller-text__article {
      font-size: 0.8rem;
  }
</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .multiselect {
        min-height: 35px;
    }

    .multiselect--active:not(.multiselect--above) .multiselect__tags {
        border-radius: 5px 5px 0 0;
    }

    .multiselect--active .multiselect__tags {
        border-radius: 0 0 5px 5px;
    }

    .multiselect--active .multiselect__tags:after {
        -webkit-transform: rotate(-180deg);
        -ms-transform: rotate(-180deg);
        transform: rotate(-180deg);
    }

    .multiselect--active .multiselect__placeholder {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .multiselect--active.multiselect--above {
        border-radius: 0 0 5px 5px;
    }

    .multiselect--active.multiselect--above .multiselect__content-wrapper {
        border-bottom: none;
        border-radius: 5px 5px 0 0;
    }

    .multiselect--above .multiselect__content-wrapper {
        border: 1px solid #D9D9D9;
        top: auto;
        bottom: 100%;
    }

    .multiselect__select {
        display: none;
    }

    .multiselect__tags {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        min-height: 35px;
        font-weight: 500;
        font-size: 11px;
        line-height: 1.2;
        letter-spacing: -0.0024em;
        border: 1px solid #D9D9D9;
        border-radius: 5px;
        padding: 0;
        position: relative;
    }

    .multiselect__tags:after {
        content: "";
        display: block;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        width: 9px;
        height: 6px;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg width='9' height='6' viewBox='0 0 9 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M9 0.951882L4.5 6L-2.2066e-07 0.951882L0.848528 -1.20525e-07L4.5 4.09624L8.15147 -4.39747e-07L9 0.951882Z' fill='%2300B7FF'/%3e%3c/svg%3e ");
        background-size: contain;
        position: absolute;
        top: 14px;
        right: 14px;
        z-index: 0;
        -webkit-transition: .3s;
        -o-transition: .3s;
        transition: .3s;
    }

    .multiselect__tags-wrap {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        width: 100%;
        gap: 5px;
        padding: 5px;
        padding-right: 30px;
    }

    .multiselect__tags input {
        border: none;
        padding: 0 14px;
    }

    .multiselect__tags input:focus {
        border-color: #D9D9D9;
    }

    .multiselect__tag {
        background: #00B7FF;
        padding: 5px 25px 5px 10px;
        margin: 0;
    }

    .multiselect__tag-icon:hover {
        background: #FF608D;
    }

    .multiselect__tag-icon:after {
        font-size: 16px;
        color: #fff;
    }

    .multiselect__placeholder {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        width: 100%;
        min-height: 35px;
        padding: 0 14px;
        padding-right: 30px;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
        position: relative;
        z-index: 1;
    }

    .multiselect__single {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        width: 100%;
        min-height: 35px;
        font-size: 11px;
        line-height: 1.2;
        padding: 0 14px;
        padding-right: 30px;
        margin: 0;
        white-space: pre;
        overflow: hidden;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
        position: relative;
        z-index: 1;
    }

    .multiselect__content {
        overflow: hidden;
    }

    .multiselect__content-wrapper {
        border: 1px solid #D9D9D9;
        border-top: none;
        border-radius: 0 0 5px 5px;
        padding-top: 11px;
        padding-bottom: 14px;
        margin: 0;
        top: 100%;
    }

    .multiselect__content-wrapper::-webkit-scrollbar {
        width: 5px;
        height: 5px;
        background-color: #F2F2F2;
    }

    .multiselect__content-wrapper::-webkit-scrollbar-thumb {
        background-color: #D9D9D9;
    }

    .multiselect__element {
        margin-bottom: 8px;
    }

    .multiselect__element:last-child {
        margin-bottom: 0;
    }

    .multiselect__option {
        min-height: auto;
        font-size: 12px;
        line-height: 1.2;
        color: #4F4F4F;
        white-space: normal;
        padding: 0 14px;
    }

    .multiselect__option--selected {
        font-weight: bold;
        color: #4F4F4F;
        background: transparent;
    }

    .multiselect__option--selected.multiselect__option--highlight {
        font-weight: bold;
        color: #4F4F4F;
        background: transparent;
    }

    .multiselect__option--highlight {
        font-weight: bold;
        color: #4F4F4F;
        background: transparent;
    }
</style>
