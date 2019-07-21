<template>
  <v-layout
    wrap
  >
    <v-flex
      xs3
      sm3
      md2
      class="px-auto py-3"
    >
      <v-chip
        label
        outline
        color="gray"
      >{{project}}</v-chip>
    </v-flex>

    <v-flex
      xs3
      sm3
      md2
      class="px-auto py-3"
    >
      <v-chip
        label
        outline
        color="gray"
      >{{category}}</v-chip>
    </v-flex>

    <v-flex
      xs3
      sm3
      md2
      class="px-auto py-3"
    >
      <v-chip
        label
        outline
        color="gray"
      >{{amount}}</v-chip>
    </v-flex>

    <v-flex
      xs3
      sm3
      md6
    >
      <v-text-field
        label="内容"
        v-model="detail"
        type="string"
      ></v-text-field>
    </v-flex>

  </v-layout>

</template>

<script>
export default {
  data() {
    return {
      detail: null
    };
  },
  props: {
    workload: {
      type: Object,
      required: true
    }
  },
  computed: {
    project() {
      return this.workload.project.name;
    },
    category() {
      return this.workload.category.name;
    },
    amount() {
      return this.workload.amount;
    }
  }
};
</script>

<style lang="scss" scoped>
.job-item-style-common {
  width: 100%;
}
</style>

