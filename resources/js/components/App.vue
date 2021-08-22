<template>
    <div class="app-content">
        <v-header v-if="$auth.check()"/>
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
