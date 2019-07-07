<template>
  <div>
    <v-list-tile
      avatar
      ripple
    >
      <v-list-tile-content>
        <v-list-tile-title>{{ projectId }}</v-list-tile-title>
        <v-list-tile-sub-title>{{ categoryId }}</v-list-tile-sub-title>
      </v-list-tile-content>

      <v-list-tile-action>
        <v-list-tile-action-text>{{ amount }}</v-list-tile-action-text>
      </v-list-tile-action>

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
    projectId: {
      type: Number,
      required: true
    },
    categoryId: {
      type: Number,
      required: true
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
