<template>
    <div class="main-articles">

        <div class="article-edit card">

            <router-link :to="{path:'/project/new'}">
                <a class="btn btn-outline-primary btn-block">
                    Создать проект
                </a>
            </router-link>

            <div class="card-body">
                <div class="col">
                    <div class="row">
                        <div v-for="(project) in projectList" v-bind:key="project.id">

                            <div class="col-md-12">
                                <router-link
                                        :to="{name:'viewProject', params: {projectId: project.id}}"
                                >
                                    <div class="rectangle">
                                        <img class="logo__title" src="https://echo.myftp.org/themes/t/assets/imgs/logo.png">
                                        <div class="rectangle__comment">
                                            {{ project.options.title }}
                                        </div>
                                    </div>

                                </router-link>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-outline-primary">
                            Далее
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>                    <!-- main 2-1 -->
</template>
<script>
    //import axios from 'axios';
    export default {
        name: 'ProjectList',
        data() {
            return {
                projectList: this.$store.state.projects,
                test: 123
            }
        },
        computed: {
        },
        mounted() {
            this.$get(process.env.VUE_APP_API_URI + `/project`).then( response => {

                if (response.data.length > 0) {
                    //this.$store.dispatch('loadProjects', response.data)
                    this.projectList = response.data
                }
            })
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
  .rectangle {

      /*width: 258px;*/
      height: 200px;
      margin-bottom: 30px;

      background: #FFFFFF;
      border: 1px solid #E5E6EA;
      box-sizing: border-box;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
      border-radius: 5px;
  }
  .logo__title {
      margin: 1em 1em 1em 1em;
  }
  .rectangle__comment {

      margin: 2em 2em 2em 2em;
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      font-size: 14px;
      line-height: 17px;

      color: #A1A1A1;
  }
</style>
