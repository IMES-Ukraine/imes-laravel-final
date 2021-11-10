<template>
    <div class="schedule template_box">
        <p class="template_title">График</p>
        <button class="template_close"></button>
        <div class="schedule__table">
            <div class="schedule__table-block schedule__table-block--head">
                <div class="schedule__table-item">
                    <p class="schedule__table-title">Название исследования</p>
                </div>
                <div class="schedule__table-item">
                    <p class="schedule__table-title">Время подачи информации</p>
                </div>
            </div>
            <div v-for="content in project.content" class="schedule__table-block">
                <div class="schedule__table-item">
                    <p class="schedule__table-name">{{content.title}}</p>
                </div>
                <div class="schedule__table-item">
                    <div class="schedule__table-form">
                        <div class="schedule__table-date">
                            <i></i>
                            <input type="date" name="text" placeholder="01.01.2000" v-model="content.scheduled_date">
                        </div>
                        <div class="schedule__table-time">
                            <b-form-input  type="time" v-model="content.scheduled_time"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="articles_create-line"></div>
            <button class="articles_create-submit button-gradient" type="button"
                    @click="storeSchedule">
                Сохранить
            </button>

        </div>
    </div>
</template>

<script>
import ProjectMixin from "../../../ProjectMixin";

export default {
    name: "schedule",
    mixins: [ProjectMixin],
    data() {
        return {
            project: {... this.$store.state.project}
        }
    },
    methods: {
        storeSchedule() {
            this.project.options.presentation_type = 'scheduled';
            this.$store.dispatch('storeProject', this.project);
            this.setStep(5);
        }
    }
}
</script>

<style scoped>
.schedule__table {
    padding-bottom: 15px;
}
.schedule__table-form input {
    padding-left: 2px;
    padding-right: 2px;
    width: 100px;
}
</style>
