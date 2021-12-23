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
                                v-model="content.title" @change="setShowFull">
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
                                        type="button" @click.prevent="newArticle"><span
                                    class="icon-right">Создать</span></button>
                                <div id="add_new_article" v-else>
                                    <div class="articles_create__study">
                                        <p class="articles_create__study-title">{{ content.article.title }}</p>
                                        <div class="articles_create__study-controls">
                                            <button
                                                class="articles_create__study-button articles_create__study-button--edit"
                                                @click.prevent="editArticle" type="button"></button>
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
                                            @keypress="isNumber($event)"
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
                                            @keypress="isNumber($event)"
                                            class="form-control"
                                            name="name"
                                            v-model="content.article.points">
                                        <span class="errors">{{ errors[0] }}</span>
                                    </validation-provider>
                                </div>
                            </div>
<!--                            <div class="articles_create__grid-block">-->
<!--                                <div class="articles_create__field_with_label">-->
<!--                                    <p class="articles_create__field_with_label-label">Частота</p>-->
<!--                                    <validation-provider-->
<!--                                        rules="required"-->
<!--                                        v-slot="{ errors }">-->
<!--                                        <input-->
<!--                                            class="form-control"-->
<!--                                            type="number"-->
<!--                                            @keypress="isNumber($event)"-->
<!--                                            name="name"-->
<!--                                            v-model="content.article.frequency">-->
<!--                                        <span class="errors">{{ errors[0] }}</span>-->
<!--                                    </validation-provider>-->
<!--                                </div>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="articles_create__item">
                    <p class="articles_create__item-title height-47">Тесты</p>
                    <div class="articles_create__item-content">
                        <div class="articles_create__grid width-main-1">
                            <div class="articles_create__grid-block">
                                <button v-if="! content.test.title" class="articles_create-add_btn height-47"
                                        type="button" @click.prevent="newTest"><span
                                    class="icon-right">Создать</span>
                                </button>
                                <div id="add_new_test" v-else>
                                    <div class="articles_create__study">
                                        <p class="articles_create__study-title">{{ content.test.title }}</p>
                                        <div class="articles_create__study-controls">
                                            <button
                                                class="articles_create__study-button articles_create__study-button--edit"
                                                type="button" @click.prevent="editTest"></button>
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
                                                @keypress="isNumber($event)"
                                                name="name"
                                                v-model="content.test.count">
                                            <span class="errors">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                </div>
                                <div class="articles_create__grid-block">
                                    <div class="articles_create__field_with_label">
                                        <p class="articles_create__field_with_label-label">Баллы</p>
                                        <validation-provider rules="required" v-slot="{ errors }">
                                            <input
                                                class="form-control"
                                                type="number"
                                                @keypress="isNumber($event)"
                                                name="name"
                                                v-model="content.test.points"/>

                                            <span class="errors">{{ errors[0] }}</span>
                                        </validation-provider>
                                    </div>
                                </div>
<!--                                <div class="articles_create__grid-block">-->
<!--                                    <div class="articles_create__field_with_label">-->
<!--                                        <v-checkbox :value.sync="content.test.canRetake"/>-->
<!--                                    </div>-->
<!--                                </div>-->
                        </div>
                    </div>
                </div>
            </span>
            <span v-else class="w-100">
            <button data-v-45a244f4="" type="button"
                    class="articles_create-submit button-border"
                    @click="showContent">Далее</button>
            </span>
        </div>
        <div class="articles_create-line" v-show="is_points"></div>
        <div class="articles_create-block" v-show="is_points">
            <div class="articles_create-note">1 пользователь = 1 тест + 1 статья = <p>{{ pointsSum }}</p>
                баллов
            </div>
        </div>
        <button class="articles_create-submit button-gradient" type="button"
                @click="showFirstStep" v-show="is_points">
            Сохранить
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
            content: {},
            loaded: false,
            showFull: false
        }
    },

    mounted() {
        if (this.$store.state.currentAction === 'create') {
            this.content = this.contentTemplate;
        } else if (this.$store.state.currentAction === 'edit') {
            this.$store.dispatch('setCurrentContent', this.$store.state.currentContentTitle).then(() => {
                this.content = this.$store.state.content;
                this.showFull = true;
            });
        } else {
            this.content = this.$store.state.content;
            this.content.title = this.$store.state.currentContentTitle;
            this.showFull = true;
        }
        this.loaded = true;
    },
    computed: {
        haveArticle() {
            return this.content ? this.content.article ? !!this.content.article.title : false : false;
        },
        haveTest() {
            return this.content ? this.content.test ? !!this.content.test.title : false : false;
        },
        pointsSum() {
            let sum = 0;
            if (this.content.article) {
                sum += parseInt(this.content.article.points) || 0;
            }
            if (this.content.test) {
                sum += parseInt(this.content.test.points) || 0;
            }
            return sum;
        },
        is_points() {
            // Сохраняться можно если есть тест или статья - причем с данными
            let res = this.haveArticle || this.haveTest;
            if (this.haveTest) {
                res &= !!(parseInt(this.content.test.count))
                    && !!(parseInt(this.content.test.points))
            }
            if (this.haveArticle) {
                res &= !!(parseInt(this.content.article.count))
                    && !!(parseInt(this.content.article.points))
                    && !!(parseInt(this.content.article.frequency));
            }
            return res;


            // return this.haveTest && this.content.test.count && this.content.test.points
            //     && this.haveArticle && this.content.article.title && this.content.article.count
            //     && this.content.article.points && this.content.article.frequency;

        }

    },
    methods: {
        setShowFull() {
            if (this.content.title) {
                this.showFull = true;
                this.$store.commit('setCurrentAction', 'other');
                this.$store.dispatch('setContent', this.content);
                this.$store.dispatch('setCurrentContentTitle', this.content.title);
            } else {
                this.showFull = false;
            }
        },
        newTest() {
            this.$store.dispatch('storeTest', this.contentTemplate.test);
            this.setStep(4);
        },
        newArticle() {
            this.$store.dispatch('storeArticle', this.contentTemplate.article);
            this.setStep(3);
        },
        editArticle() {
            this.$store.dispatch('setCurrentArticle');
            this.setStep(3);
        },
        editTest() {
            this.$store.dispatch('setCurrentTest');
            this.setStep(4);
        },


        showContent() {
            this.$refs['packName'].validate().then((res) => {
                if (res.valid) {
                    this.showFull = true;
                }
            });
        },
        showFirstStep() {
            this.errorNewTest = '';
            this.errorNewArticle = '';
            let error = false;

            // if (!this.content.test.title) {
            //     this.errorNewTest = 'Тест обовʼязковий';
            //     error = true;
            // }
            //
            // if (!this.content.article.title) {
            //     this.errorNewArticle = 'Статья обовʼязкова';
            //     error = true;
            // }
            if (!error) {
                this.$store.dispatch('saveContent', this.content);
                this.setStep(1)
            }

        },
    },
}
</script>

<style scoped>

</style>
