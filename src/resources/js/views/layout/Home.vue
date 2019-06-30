<template>
  <div class="home-container">
    <header-bar></header-bar>
    <div>
      <router-link :to="{name: 'top'}">Go to Top</router-link>
    </div>
    <workload-item
      v-for="(item, index) in getWorkload"
      :key="index"
      :date="item.date"
      :amount="item.amount"
      :project_id="item.project_id"
      :category_id="item.category_id"
    ></workload-item>
  </div>
</template>

<script>
import axios from '../../Util/axios/axios';
import WorkloadItem from '../components/WorkloadItem';
import HeaderBar from '../components/headerBar';

export default {
  components: {
    WorkloadItem,
    HeaderBar,
  },
  async mounted() {
    const result = await axios.get('api/workload/get/user_id/1');

    const filteredData = Array.from(result.data).map(data => {
      const date = data.date.replace(/T.*/, '');
      return {
        date,
        amount: data.amount,
        project_id: data.project_id,
        category_id: data.category_id,
      };
    });
    this.$store.commit('setWorkload', filteredData);
  },
  computed: {
    getWorkload() {
      return this.$store.getters.workload;
    },
  },
};
</script>

<style lang="scss" scoped>
.home-container {
  width: 100vw;
}
</style>
