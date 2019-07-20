<template>
  <v-layout
    row
    wrap
  >
    <v-flex
      xs3
      sm3
      md3
    >
      <v-chip label>{{project}}</v-chip>
    </v-flex>

    <v-flex
      xs3
      sm3
      md3
    >
      <v-chip label>{{category}}</v-chip>
    </v-flex>

    <v-flex
      xs2
      sm2
      md2
    >
      <v-chip label>{{amount}}</v-chip>
    </v-flex>

    <v-flex
      xs4
      sm4
      md4
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
      const projectId = this.workload.projectId;
      return this.$store.getters.getProjectFromId(projectId);
    },
    category() {
      const categoryId = this.workload.categoryId;
      return this.$store.getters.getCategoryFromId(categoryId);
    },
    amount() {
      return this.workload.amount;
    },
  }
};
</script>
