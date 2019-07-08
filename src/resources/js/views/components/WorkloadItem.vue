<template>
  <div>
    <v-list-tile
      avatar
      ripple
    >
      <v-layout
        row
        wrap
      >
        <v-flex
          xs10
          sm10
          md10
        >
          <v-list-tile-content>
            <v-list-tile-title>{{ project }}</v-list-tile-title>
            <v-list-tile-sub-title>{{ category }}</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-flex>
        <v-flex
          xs2
          sm2
          md2
        >
          <v-list-tile-action>
            <v-list-tile-action-text>{{ amount }}</v-list-tile-action-text>
          </v-list-tile-action>
        </v-flex>
      </v-layout>
    </v-list-tile>
    <v-divider></v-divider>
  </div>
</template>

<script>
export default {
  props: {
    date: {
      type: String,
      required: true
    },
    amount: {
      type: Number,
      required: true,
      default: 0
    },
    project: {
      type: Number,
      required: true
    },
    category: {
      type: Number,
      required: true
    }
  },
  mounted() {
    console.log(this.project, this.category);
  }
};
</script>

<style lang="scss" scoped>
</style>
