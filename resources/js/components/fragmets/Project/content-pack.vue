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
        <div v-if="loaded" class="articles_create-block">
            <div class="articles_create__item">
                <p class="articles_create__item-title">Название</p>
                <div class="articles_create__item-content">
                    <div class="articles_create__name-block">
                        <validation-provider
                            ref="packName"
                            rules="required|max:35"
                            :custom-messages="{ max: 'Максимальное количество 35 символов' }"
                            v-slot="{ errors }">

                            <input
                                class="form-control"
                                type="text"
                                v-model="contentTitle">
                            <span class="errors">{{ errors[0] }}</span>
                        </validation-provider>
                        <p class="articles_create__name-note">35 символов</p>
                    </div>
                </div>
            </div>
            <span v-if="showFull">
                <div class="articles_create__item">
                    <p class="articles_create__item-title height-47">Статья</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__grid width-main-1">
                            <div class="articles_create__grid-block">
                                <button v-if="! content.article.title" class="articles_create-add_btn height-47"
                                        type="button" @click.prevent="setStep(3)"><span
                                    class="icon-right">Создать</span></button>
                                <div id="add_new_article" v-else>
                                    <div class="articles_create__study">
                                        <p class="articles_create__study-title">{{ content.article.title }}</p>
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
                                            v-model="content.article.count">
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
                                            v-model="content.article.points">
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
                                            v-model="content.article.frequency">
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
                                <button v-if="! content.test.question.title" class="articles_create-add_btn height-47"
                                        type="button" @click.prevent="setStep(4)"><span
                                    class="icon-right">Создать</span>
                                </button>
                                <div id="add_new_test" v-else>
                                    <div class="articles_create__study">
                                        <p class="articles_create__study-title">{{ content.test.question.title }}</p>
                                        <div class="articles_create__study-controls">
                                            <button
                                                class="articles_create__study-button articles_create__study-button--edit"
                                                type="button" @click.prevent="setStep(4)"></button>
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
                                                v-model="content.test.count">
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
                                                v-model="content.test.points">

                                            <span class="errors">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                </div>
                                <div class="articles_create__grid-block">
                                    <div class="articles_create__field_with_label">
                                        <v-checkbox
                                            :id="'can-retake-button'"
                                            :name="'can-retake-button'"
                                            v-model="content.test.canRetake"
                                        />
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </span>
            <span v-else>
                        <button class="articles_create-submit button-gradient" type="button"
                                @click="showContent">
            Далі
        </button>
            </span>
        </div>
        <div class="articles_create-line" v-show="is_points"></div>
        <div class="articles_create-note" v-show="is_points">1 пользователь = 1 тест + 1 статья = <p>{{pointsSum}}</p> баллов</div>
        <button class="articles_create-submit button-gradient" type="button"
                @click="showFirstContent" v-show="is_points">
            Зберегти
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
    props: {
        name: {
            type: String,
            required: false
        }
    },
    data() {
        return {
            contentTitle: this.$store.state.currentContent,
            content: {},
            loaded: false,
            showFull: false
        }
    },

    mounted() {
        this.content.title = this.$store.state.currentContent;
        this.loadContent(this.content.title);

        this.loaded = true;
        if (this.content.title) {
            this.showFull = true;
        }
    },
    computed: {
        pointsSum() {
            if (this.content.article && this.content.test) {
                return parseInt(this.content.article.points) + parseInt(this.content.test.points);
            }
        },
        is_points() {
            if (this.contentTitle && this.content.article) {
                return this.content.article.count && this.content.article.points && this.content.article.frequency
                    && this.content.test.count && this.content.test.points;
            }
            return false;
        }

    },
    methods: {
        showContent() {
            this.$refs['packName'].validate().then((res) => {
                if (res.valid) {
                    if (undefined === this.$store.state.project.content[this.contentTitle]) {
                        this.content.title = this.contentTitle;
                        this.storeContent();
                    } else {
                        this.loadContent();
                    }
                    this.showFull = true;
                }
            });
        },
        storeContent() {
            this.$store.dispatch('storeContent', this.content);
        },
        loadContent() {
            if (this.contentTitle) {
                this.$store.commit('loadContent', this.contentTitle);
                this.content = this.$store.state.project.content[this.contentTitle];
                console.log('component' + this.content);
            } else {
                this.content = this.contentTemplate;
            }
        },
        showFirstContent() {
            this.errorNewTest = '';
            this.errorNewArticle = '';

            if (!this.content.test.title) {
                this.errorNewTest = 'Тест обовʼязковий';
            }

            if (!this.content.article.title) {
                this.errorNewArticle = 'Статья обовʼязкова';
            }

            this.$store.state.project.content[this.content.title] = this.content;
            sessionStorage.project = JSON.stringify(this.$store.state.project);
            this.setStep(1)

        },
    },
}
</script>

<style scoped>

</style>
