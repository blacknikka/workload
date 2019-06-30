<template>
  <div class="logout-container">
    <button @click="clicked">Logout</button>
  </div>
</template>

<script>
import axios from '../../Util/axios/axios';

export default {
  methods: {
    async clicked() {
      // console.log('logout clicked');
      // const result = await axios.post(
      //     'logout', {});
      // console.log(result);

      this.$router.replace({name: 'login'});
    },
  },
};
</script>

<style lang="scss" scoped>
.logout-container {
  width: 30%;
  border: thick double #32a1ce;
  border-radius: 12px;
  padding: 3px;

  button {
    width: 100%;
    border-radius: 12px;
  }
}
</style>
