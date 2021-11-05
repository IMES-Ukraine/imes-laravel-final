<template>
    <div class="articles">
        <div class="articles_create preview">
            <button class="articles_create-close"></button>
            <div class="preview-label">Предпросмотр</div>
            <div class="preview-box">
                <div class="preview__block preview__block--title">
                    <div class="preview__title">
                        <img :src="project.options.files.cover" class="preview__title-img" alt="">
                        <p class="preview__title-title">Проект:</p>
                        <p class="preview__title-data">{{ project.options.title }}</p>
                    </div>
                </div>
                <div class="preview__block">
                    <p class="preview__block-title">Аудитория:</p>
                    <div class="preview__block-wrap">
                        <div class="preview__block-item">
                            <p class="preview__block-file"><span>{{ category }} : {{ region }}</span></p>
                            <button class="preview__block-more">Подробнее</button>
                        </div>
                    </div>
                </div>
                <div class="preview__block">
                    <p class="preview__block-title">Контент:</p>
                    <div class="preview__block-wrap">
                        <div class="preview__block-item" v-for="item in project.content">
                            <div class="preview__study">
                                <p class="preview__study-title">{{item.title}}</p>
                                <div class="preview__study-list">
                                    <p class="preview__study-item">100 тестов</p>
                                    <p class="preview__study-item">100 статей</p>
                                </div>
                            </div>
                            <button class="preview__block-more">Подробнее</button>
                        </div>

                    </div>
                </div>
                <div class="preview__block preview__block--type">
                    <p class="preview__block-title mt0">Тип подачи информации:</p>
                    <div class="preview__block-wrap">
                        <div class="preview__block-item">
                            <p class="preview__block-type"><span>Все сразу</span></p>
                        </div>
                    </div>
                </div>
                <div class="preview__block">
                    <p class="preview__block-note">1 пользователь =  {{contentCount}} теста + {{contentCount}} статьи = {{projectPoints}} баллов </p>
                </div>
            </div>
            <button class="articles_create-submit button-gradient" type="button" @click="finalStoreProject">запуск</button>
        </div>
    </div>
</template>

<script>
import ProjectMixin from "../../../ProjectMixin";
export default {
    name: "project-preview",
    mixins: [ProjectMixin],
    data() {
        return {
        }
    },
    computed: {
        category() {
            return this.findByValue(this.lists.categories, this.project);
        },
        region() {
            return this.findByValue(this.lists.regions, this.project);
        },
        contentCount() {
          return Object.keys(this.project.content).length;
        },
        projectPoints() {
            let sum = 0;
            for (let index in this.project.content) {
                sum = sum + parseInt(this.project.content[index].article.points ? this.project.content[index].article.points : 0)
                    + parseInt(this.project.content[index].test.points ?this.project.content[index].article.points : 0);
                console.log(index, sum, this.project.content[index]);
            }
            return sum;
        }
    }
}
</script>

<style scoped>

</style>
