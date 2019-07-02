<template>
  <v-parallax
    dark
    src="https://cdn.vuetifyjs.com/images/backgrounds/vbanner.jpg"
    @click="clicked"
  >
    <v-layout
      align-center
      column
      justify-center
    >
      <h1 class="display-2 font-weight-thin mb-3">Hello!</h1>
      <h4 class="subheading">I hope you have a good day!</h4>
    </v-layout>
  </v-parallax>
</template>

<script>
export default {
  methods: {
    clicked() {
      this.$router.replace({ name: 'home' });
    },
  },
};
</script>

<style lang="scss" scoped>
.top-container {
  width: 100vw;
}
</style>
