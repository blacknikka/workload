<template>
  <div class="home-container">
    <div @click="clicked">
        home.
    </div>
    <workload-item
        v-for="(item, index) in getWorkload" :key="index"
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

export default {
    components: {
        WorkloadItem,
    },
    async mounted() {
        const result = await axios.get('api/workload/get/user_id/1');
        console.log(result);

        // 日付を表示用に加工する
        const filteredData = Array.from(result.data).map(data => {
            const date = data.date.replace(/T.*/, '');
            return {
                date,
                amount: data.amount,
                project_id: data.project_id,
                category_id: data.category_id,
            };
        })
        this.$store.commit('setWorkload', filteredData);
    },
    computed: {
        getWorkload() {
            return this.$store.getters.workload;
        }
    },
    methods: {
        clicked() {
            this.$router.push({name: 'top'});
        },
    },
};
</script>

<style lang="scss" scoped>
.home-container {
    width: 100vw;
}
</style>
