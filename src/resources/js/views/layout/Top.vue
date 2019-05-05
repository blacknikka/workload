<template>
  <div
  class="top-container"
  @click="toHome">
    top page
  </div>
</template>

<script>
export default {
    methods: {
        toHome() {
            this.$router.push('home')
        }
    },
};
</script>

<style lang="scss" scoped>
.top-container {
    width: 100vw;
}
</style>

