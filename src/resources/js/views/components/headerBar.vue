<template>
  <v-toolbar
    dark
    color="primary"
  >
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
          <v-icon>fas fa-list</v-icon>
        </v-btn>
      </template>

      <v-list>
        <v-list-tile
          v-for="(item, index) in menuItems"
          :key="index"
        >
          <v-list-tile-title @click="menu_clicked(item.action)">{{ item.title }}</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>
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
          v-for="(item, index) in utilityItems"
          :key="index"
        >
          <v-list-tile-title @click="menu_clicked(item.action)">{{ item.title }}</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>
  </v-toolbar>
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
      utilityItems: [{ title: 'Account', action: 'MyAccount' }, { title: 'Logout', action: 'Logout' }],
      menuItems: [{ title: '工数', action: 'Workload' }, { title: 'Report', action: 'Report' }]
    };
  },
  methods: {
    utility_clicked(action) {
      switch (action) {
        case 'MyAccount':
          break;
        case 'Logout':
          this.$store.commit('setLoggedIn', { loggedIn: false });
          this.$router.replace({ name: 'top' });
          break;
      }
    },
    menu_clicked(action) {
      switch (action) {
        case 'Workload':
          this.$router.push({ name: 'home' });
          break;
        case 'Report':
          this.$router.push({ name: 'report' });
          break;
      }
    }
  },
  computed: {
    getMyName() {
      const user = this.$store.getters.userInfo;
      return user !== null ? `${user.name}@${user.department.departmentName}` : '';
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
