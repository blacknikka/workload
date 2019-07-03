<template>
  <v-app>
    <v-toolbar
      dark
      color="primary"
    >
      <v-toolbar-side-icon></v-toolbar-side-icon>
      <v-toolbar-title class="white--text">{{getMyName}}</v-toolbar-title>
      <v-spacer></v-spacer>

      <v-menu
        bottom
        left
      >
        <template v-slot:activator="{ on }">
          <v-btn
            dark
            icon
            v-on="on"
          >
            <v-icon>more_vert</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-tile
            v-for="(item, index) in items"
            :key="index"
          >
            <v-list-tile-title @click="menu_clicked(item.action)">{{ item.title }}</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-menu>
    </v-toolbar>
  </v-app>
</template>

<script>
import Logout from '../components/Logout';

export default {
  components: {
    Logout
  },
  data() {
    return {
      yourname: '',
      items: [{ title: 'Account', action: 'MyAccount' }, { title: 'Logout', action: 'Logout' }]
    };
  },
  methods: {
    menu_clicked(action) {
      switch (action) {
        case 'MyAccount':
          break;
        case 'Logout':
          this.$store.commit('setLoggedIn', { loggedIn: false });
          this.$router.replace({ name: 'top' });
          break;
      }
    }
  },
  computed: {
    getMyName() {
      const user = this.$store.getters.userInfo;
      return `${user.name}@${user.department.departmentName}`;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
