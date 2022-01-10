<template>
    <div class="articles_list2__block">
        <div class="articles_list2__block-overlay articleOverlay"></div>
        <div class="articles_list2__block-controls">
            <button class="articles_list2__block-button articles_list2__block-button--statistics articleStatisticsBtn" type="button"></button>
            <a class="articles_list2__block-button articles_list2__block-button--edit" :href="'article/' + id"></a>
            <button class="articles_list2__block-button articles_list2__block-button--delete articleDeleteBtn" type="button"></button>
        </div>
        <p class="articles_list2__block-title">{{ title }}</p>
        <div class="articles_list2__block-content articles_list2__block-content--statistics">
            <div class="articles_list2__block-info">
                <div>
                    <p>Количество просмотров</p>
                    <p><b>{{ views }}</b></p>
                </div>
                <div>
                    <p>Количество выполнения целей (нажатия кнопки)</p>
                    <p><b>{{ callbacks }}</b></p>
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
    export default {
        name: "article-list-card",
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
            },
            callbacks: {
                type: Number,
                default: 0
            },
            views: {
                type: Number,
                default: 0
            }
        },
        methods: {
            removeArticle(value) {
                this.$emit('update', this.id);
            }
        }
    }
</script>
