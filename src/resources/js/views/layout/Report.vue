<template>
  <v-app light>
    <header-bar class="report-header"></header-bar>
    <each-day-item
      v-for="(item, index) in workloads"
      :key="index"
      :workloads="item"
      :day="days[index]"
    >
    </each-day-item>
  </v-app>
</template>

<script>
import EachDayItem from '../components/Report/EachDayItem';
import HeaderBar from '../components/headerBar';

import axios from '../../Util/axios/axios';
import moment from 'moment/moment';
import Workload from '../../model/workload/workload';

export default {
  components: {
    EachDayItem,
    HeaderBar,
  },
  data() {
    return {
      days: [],
      workloads: [...Array(7)].map(item => {
        return [];
      }),
    };
  },
  async mounted() {
    // 一週間分の日付データを作る
    const baseDay = moment().startOf('week');
    [...Array(7)].forEach((_, index) => {
      const addedDate = new moment(baseDay).add(index, 'days');
      this.days.push(addedDate);
    });

    const userInf = this.$store.getters.userInfo;
    const today = moment();
    const workloadsFromServer = await axios.getWithJwt(
      `api/workload/get/user/id/${userInf.id}/week/${baseDay.format('YYYY-MM-DD')}`
    );

    if (workloadsFromServer !== false) {
      const workloadsOfWeek = workloadsFromServer.data.data.map(workload => {
        const dateString = workload.date.replace(/T.*/, '');

        // Workloadオブジェクトを作成。
        return new Workload(
          workload.id,
          dateString,
          workload.amount,
          this.$store.getters.getProjectFromId(workload.project_id),
          this.$store.getters.getCategoryFromId(workload.category_id),
          false
        );
      });

      // 日付ごとに工数データを抽出する
      this.days.forEach((eachDay, index) => {
        // 各日付にマッチする工数を探す
        const filteredWorklaods = workloadsOfWeek.filter(workload => {
          return moment(workload.date).isSame(eachDay);
        });

        filteredWorklaods.forEach(workload => this.workloads[index].push(workload));
      });

      console.log(this.workloads);
    }
  },
  computed: {}
};
</script>

<style lang="scss" scoped>
.report-header {
  z-index: 10;
}
</style>
