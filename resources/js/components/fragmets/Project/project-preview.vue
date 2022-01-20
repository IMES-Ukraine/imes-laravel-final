<template>
    <div>
        <div class="preview-label">Предпросмотр</div>
        <div class="preview-box">
            <div class="preview__block preview__block--title">
                <div class="preview__title">
                    <img :src="cover.path" class="preview__title-img" alt="">
                    <p class="preview__title-title">Проект:</p>
                    <p class="preview__title-data">{{ project.options.title }}</p>
                </div>
            </div>
            <div class="preview__block">
                <p class="preview__block-title">Аудитория:</p>
                <div class="preview__block-wrap">
                    <div class="preview__block-item">
                        <p class="preview__block-file"><span>{{ category }} : {{ region }}</span></p>
                        <button class="preview__block-more" type="button" @click="setStep(1)">Подробнее</button>
                    </div>
                </div>
            </div>
            <div class="preview__block">
                <p class="preview__block-title">Контент:</p>
                <div class="preview__block-wrap">
                    <div class="preview__block-item" v-for="item in project.content">
                        <div class="preview__study">
                            <p class="preview__study-title">{{ item.title }}</p>
                            <div class="preview__study-list">
                                <p class="preview__study-item">{{ (item.test.points) ? 1 : 0 }} тест(ов)</p>
                                <p class="preview__study-item">{{ (item.article.points) ? 1 : 0 }} статья(ей)</p>
                            </div>
                        </div>
                        <button class="preview__block-more" type="button" @click="editContent(item.title)">Подробнее
                        </button>
                    </div>

                </div>
            </div>
            <div class="preview__block preview__block--type">
                <p class="preview__block-title mt0">Тип подачи информации:</p>
                <div class="preview__block-wrap">
                    <div class="preview__block-item">
                        <p class="preview__block-type"><span>{{ types[project.options.presentation_type] }}</span></p>
                    </div>
                </div>
            </div>
            <div class="preview__block">
                <p class="preview__block-note">1 пользователь = {{ testCounts }} тест(ов)
                    + {{ articlesCounts }} статья(ей) = {{ projectPoints }} баллов </p>
            </div>
        </div>
        <button id='start' class="articles_create-submit button-gradient" type="button" @click="finalStoreProject">
            запуск
        </button>
    </div>
</template>

<script>
import ProjectMixin from "../../../ProjectMixin";

export default {
    name: "project-preview",
    mixins: [ProjectMixin],
    data() {
        return {
            project: {...this.$store.state.project},
            types: {
                'at_once': 'Все сразу',
                'scheduled': 'По графику',
                'series': 'Последовательность'
            },
            projectPoints: 0,
            testCounts: 0,
            articlesCounts: 0
        }
    },
    computed: {
        cover() {
            return this.project.options.files.cover;
        },
        category() {
            return this.findByValue(this.lists.categories, this.project.options.selected.category);
        },
        region() {
            return this.findByValue(this.lists.regions, this.project.options.selected.region);
        },
        contentCount() {
            return Object.keys(this.project.content).length;
        },
    },
    mounted() {
      this.getTotalCounts();
    },
    methods: {
        getTotalCounts() {
            this.projectPoints = 0;
            this.articlesCounts = 0;
            this.testCounts = 0;
            for (let index in this.project.content) {
                if (this.project.content[index].article.points) {
                    this.articlesCounts++;
                    this.projectPoints += parseInt(this.project.content[index].article.points)
                }
                if (this.project.content[index].test.points) {
                    this.testCounts++;
                    this.projectPoints += parseInt(this.project.content[index].test.points);
                }

            }
        }
    }
}
</script>
