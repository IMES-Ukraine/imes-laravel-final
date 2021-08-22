<template>
    <v-content>

        <template v-slot:sidebar>
            <project-list-sidebar/>
        </template>

        <div class="col">
            <div class="col-sm-12">
                <div class="row" v-if="hasAnswers()">
                    <answer-list-card
                        v-for="(answer) in answerList"
                        v-bind:key="answer.id"
                        :id="answer.id"
                        :answer="answer.answer"
                        :question="answer.question.question"
                        @process="process"
                    />
                </div>
                <v-preloader v-else />
            </div>
        </div>

    </v-content>
</template>
<script>
    import VContent from "./templates/Content";
    import ProjectListSidebar from "./templates/answer/list/sidebar";
    import {MODERATION} from "../api/endpoints"
    import AnswerListCard from "./templates/answer/list/card";
    import VPreloader from "./fragmets/preloader";
    export default {
        name: 'ModerationList',
        components: {VPreloader, AnswerListCard, ProjectListSidebar, VContent},
        data() {
            return {
                answerList: []
            }
        },
        computed: {
        },
        methods: {
            hasAnswers () {
                return !!Object.keys(this.answerList).length;
            },
            async loadProjects () {
                if (this.hasAnswers()) {
                    return
                }

                this.$get(MODERATION).then( response => {

                    if (response.data && response.data.length > 0) {

                        this.answerList = response.data
                    }
                })
            },
            process( status, id ) {
                this.answerList.splice(id)
            },
        },
        mounted() {
            this.loadProjects()
        }
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
