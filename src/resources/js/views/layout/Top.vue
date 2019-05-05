<template>
  <div @click="toHome">
    top page
  </div>
</template>

<script>
export default {
    methods: {
        toHome() {
            this.$router.replace('home')
        }
    },
};
</script>

<style lang="sass" scoped>

</style>

