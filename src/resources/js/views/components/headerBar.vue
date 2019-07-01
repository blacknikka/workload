<template>
  <v-app>
    <v-toolbar
      dark
      color="primary"
    >
      <v-toolbar-side-icon></v-toolbar-side-icon>

      <v-toolbar-title class="white--text">Title</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>search</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>apps</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>refresh</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>more_vert</v-icon>
      </v-btn>
    </v-toolbar>
  </v-app>
</template>

<script>
import Logout from '../components/Logout';

export default {
  components: {
    Logout
  }
};
</script>

<style lang="scss" scoped>
</style>
