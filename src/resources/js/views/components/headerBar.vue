<template>
  <div class="header-container">
    <div class="logout-component-container">
      <logout></logout>
    </div>
  </div>
</template>

<script>
import Logout from '../components/Logout';

export default {
  components: {
    Logout,
  }
}
</script>

<style lang="scss" scoped>
.header-container {
  width: 100vw;
  height: 10vh;
  background-color: beige;
}

.logout-component-container {
  position: relative;
  margin: 0 0 0 auto;
  right: 2vw;
  top: 0px;
  height: 10vh;
  width: 30%;
}
</style>
