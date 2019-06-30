<template>
  <div class="logout-container">
    <button @click="clicked">Logout</button>
  </div>
</template>

<script>
import axios from '../../Util/axios/axios';

export default {
  methods: {
    clicked() {
      // login情報を消す
      this.$store.commit('setLoggedIn', { loggedIn: false });
      this.$router.replace({ name: 'top' });
    }
  }
};
</script>

<style lang="scss" scoped>
.logout-container {
  width: 100%;
  height: 100%;
  border: thick double #32a1ce;
  border-radius: 12px;
  padding: 3px;

  button {
    width: 100%;
    height: 100%;
    border-radius: 12px;
  }
}
</style>
