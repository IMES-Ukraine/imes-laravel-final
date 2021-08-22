<template>
    <div class="app-content">
        <v-header/>
        <router-view/>
    </div>
</template>

<script>

import VHeader from "./templates/Header";
export default {
  name: 'IMES',
  components: {
      VHeader
  },
};
</script>
