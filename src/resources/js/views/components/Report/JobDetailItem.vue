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
      <v-overflow-btn
        :items="getProjectList"
        v-model="project"
        label="プロジェクト"
      >
      </v-overflow-btn>
    </v-flex>

    <v-flex
      xs3
      sm3
      md3
    >
      <v-overflow-btn
        :items="getCategoryList"
        v-model="category"
        label="作業"
      >
      </v-overflow-btn>
    </v-flex>

    <v-flex
      xs2
      sm2
      md2
    >
      <v-text-field
        label="工数"
        v-model="amount"
        type="number"
      ></v-text-field>
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
      project: null,
      category: null,
      amount: null,
      detail: null
    };
  },
  props: {
    workload: {
      type: Object,
      required: true
    }
  },
  mounted() {
    // projectをpropsのworkloadから取得する
    const projectId = this.workload.project_id;
    this.project = this.$store.getters.getProjectFromId(projectId);

    // categoryをpropsのworkloadから取得する
    const categoryId = this.workload.category_id;
    this.category = this.$store.getters.getCategoryFromId(projectId);

    // amountをpropsのworkloadから取得する
    this.amount = this.workload.amount;
  },
  computed: {
    getProjectList() {
      const project = this.$store.state.project;
      return Array.from(project).map(item => {
        return item.name;
      });
    },
    getCategoryList() {
      const category = this.$store.state.category;
      return Array.from(category).map(item => {
        return item.name;
      });
    },
  }
};
</script>
