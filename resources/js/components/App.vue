<template>
    <div>
        <router-view class="app-content" />
<!--        <component v-bind:is="currentForm"></component>-->
    </div>
</template>

<script>
// import CreateProjectForm from './CreateProjectForm.vue';
// import CreateContentForm from './CreateContentForm.vue';
// import CreateArticleForm from './CreateArticleForm.vue';
// import ProjectList from './ProjectList.vue';
// import CreateTestPage from './CreateTestPage.vue';

export default {
  name: 'IMES',

  components: {
    // CreateProjectForm,
    // CreateContentForm,
    // CreateArticleForm,
    // ProjectList,
    // CreateTestPage
  },

  data: () => ({
    //
  }),
  computed: {
  //   currentForm : function() {
  //
  //     if (this.$store.state.currentForm === ProjectList.name) {
  //       return ProjectList
  //     }
  //     if (this.$store.state.currentForm === CreateProjectForm.name) {
  //       return CreateProjectForm
  //     }
  //     if (this.$store.state.currentForm === CreateArticleForm.name) {
  //       return CreateArticleForm
  //     }
  //     if (this.$store.state.currentForm === CreateContentForm.name) {
  //       return CreateContentForm
  //     }
  //     if (this.$store.state.currentForm === CreateTestPage.name) {
  //       return CreateTestPage
  //     }  else
  //       return null
  //
  //
  //   }
  }
};
</script>
