<template>
    <div class="articles_list2__block">
        <div class="articles_list2__block-overlay articleOverlay"></div>
        <div class="articles_list2__block-controls">
            <button class="articles_list2__block-button articles_list2__block-button--statistics articleStatisticsBtn" type="button"></button>
            <button class="articles_list2__block-button articles_list2__block-button--edit" type="button"></button>
            <button class="articles_list2__block-button articles_list2__block-button--delete articleDeleteBtn" type="button"></button>
        </div>
        <p class="articles_list2__block-title">{{ title }}</p>
        <div class="articles_list2__block-content articles_list2__block-content--statistics">
            <div class="articles_list2__block-info">
                <div>
                    <p>Количество просмотров</p>
                    <p><b>35</b></p>
                </div>
                <div>
                    <p>Количество выполнения целей (нажатия кнопки)</p>
                    <p><b>10</b></p>
                </div>
            </div>
        </div>
        <div class="articles_list2__block-content articles_list2__block-content--delete">
            <p>Удалить опубликованную статью?</p>
            <div>
                <button class="yes" type="button" @click="removeArticle"></button>
                <button class="no articleCloseBtn" type="button"></button>
            </div>
        </div>
    </div>
</template>

<script>
    import { ARTICLE_DESTROY } from "../../../../api/endpoints"

    export default {
        name: "article-list-card",
        components: {ARTICLE_DESTROY},
        data () {
            return {
                routeName: 'viewArticle'
            }
        },
        props: {
            id: {
                type: Number,
                require: true
            },
            title: {
                type: String,
                require: true
            }
        },
        methods: {
            removeArticle() {
                this.$delete(ARTICLE_DESTROY + this.id).then()

                for (const [index, value] of Object.entries(this.$store.state.articles)) {
                    if (value.id == this.id) {
                        this.$store.state.articles.splice(index, 1)
                    }
                }
            }
        }
    }
</script>
