<template>
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
                <p class="articles_create__item-title height-47">Статья</p>
                <div class="articles_create__item-content">
                    <div class="articles_create__grid width-main-1">
                        <div class="articles_create__grid-block">
                            <button class="articles_create-add_btn height-47" type="button" @click.prevent="setStep(3)"><span
                                class="icon-right">Создать</span></button>
                            <div id="add_new_article" v-show="add_new_article">
                                <div class="articles_create__study" v-if="article.title">
                                    <p class="articles_create__study-title">{{ article.title }}</p>
                                    <div class="articles_create__study-controls">
                                        <button
                                            class="articles_create__study-button articles_create__study-button--edit"
                                            @click.prevent="setStep(3)" type="button"></button>
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
                                        v-model="article_data.count">

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
                                        v-model="article_data.points">

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
                                        v-model="article_data.frequency">

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
                            <button class="articles_create-add_btn height-47" type="button" @click.prevent="setStep(4)"><span
                                class="icon-right">Создать</span></button>
                            <div id="add_new_test" v-show="add_new_test">
                                <div class="articles_create__study"  v-if="test.question">
                                    <p class="articles_create__study-title">{{ test.question.title }}</p>
                                    <div class="articles_create__study-controls">
                                        <button
                                            class="articles_create__study-button articles_create__study-button--edit"
                                            type="button" @click.prevent="setStep(4)"></button>
                                        <button
                                            class="articles_create__study-button articles_create__study-button--delete"
                                            type="button" @click.prevent="reloadBlockSurveyTest"></button>
                                    </div>
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
                                        v-model="test_data.count">
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
                                        v-model="test_data.points">

                                    <span class="errors">{{ errors[0] }}</span>
                                </validation-provider>
                            </div>
                        </div>
                        <div class="articles_create__grid-block">
                            <div class="articles_create__field_with_label">
                                <v-checkbox
                                    :id="'can-retake-button'"
                                    :name="'can-retake-button'"
                                    v-model="test_data.canRetake"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="articles_create-line" v-show="is_points"></div>
        <div class="articles_create-note" v-show="is_points">1 пользователь = 1 тест + 1 статья = <p>0</p> баллов</div>
        <button class="articles_create-submit button-gradient" type="button"
                @click="showFirstContent" v-show="is_points">Зберегти
        </button>
    </div>
</template>

<script>
import ProjectMixin from "../../../ProjectMixin";
import {ValidationProvider} from "vee-validate";
import VCheckbox from "../../templates/inputs/checkbox"

export default {
    name: "content-pack",
    mixins: [ProjectMixin],
    components: {ValidationProvider, VCheckbox},
    data() {
        return {
            test: {},
            article: {},
            test_data: {},
            article_data: {}
        }
    },
    methods: {
        reloadBlockArticle() {
        },
        showFirstContent() {
            this.errorNewTest = '';
            this.errorNewArticle = '';

            if (!this.add_new_test) {
                this.errorNewTest = 'Тест обовʼязковий';
            }

            if (!this.add_new_article) {
                this.errorNewArticle = 'Статья обовʼязкова';
            }

            this.$refs.form.validate().then(success => {
                if (!success) {
                    return;
                } else {
                    if (this.add_new_test && this.add_new_article) {
                        this.setStep(1)
                    }
                }
            });
        },
    },
    mounted() {
        this.test_data = this.$store.state.content.test_data;
        this.test = this.$store.state.content.test;
        this.article_data = this.$store.state.content.article_data;
        this.article = this.$store.state.content.article;
    }
}
</script>

<style scoped>

</style>
