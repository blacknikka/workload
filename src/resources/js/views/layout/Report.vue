<template>
  <v-app light>
    <header-bar class="report-header"></header-bar>
    <v-container grid-list-xl>
      <v-layout column>
        <v-flex
          xs2
          sm2
          md2
        >
          <v-btn
            color="success"
            @click="registerToDB"
          >
            DB登録
          </v-btn>
        </v-flex>
        <v-flex
          xs12
          sm12
          md12
        >
          <each-day-item
            v-for="(item, index) in workloads"
            :key="index"
            :workloads="item"
            :day="days[index]"
          >
          </each-day-item>
        </v-flex>
        <v-flex
          xs12
          sm12
          md12
        >
          <v-card>
            <input-text
              description="報告内容"
              v-model="reportDetail"
            >
            </input-text>
          </v-card>
        </v-flex>

        <v-flex
          xs12
          sm12
          md12
        >
          <v-card>
            <input-text
              description="雑感"
              v-model="myThoughts"
            >
            </input-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import EachDayItem from '../components/Report/EachDayItem';
import HeaderBar from '../components/headerBar';
import InputText from '../components/Report/InputText';

import axios from '../../Util/axios/axios';
import moment from 'moment/moment';
import Workload from '../../model/workload/workload';

export default {
  components: {
    EachDayItem,
    HeaderBar,
    InputText
  },
  data() {
    return {
      days: [],
      workloads: [...Array(7)].map(item => {
        return [];
      }),
      reportDetail: '',
      myThoughts: ''
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
    }
  },
  methods: {
    async registerToDB() {
      const reportComment = {
        id: null,
        user_id: this.$store.getters.userInfo.id,
        report_comment: this.reportDetail,
        report_opinion: this.myThoughts,
      }
      const registeredResult = await axios.postWithJwt(
        `api/report_comment/save`,
        reportComment
      );
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
