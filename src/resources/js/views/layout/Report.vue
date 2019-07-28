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
            <v-textarea
              box
              label="報告内容"
              @change="deitalChanged"
              :value="getDetailStr"
            >
            </v-textarea>
          </v-card>
        </v-flex>

        <v-flex
          xs12
          sm12
          md12
        >
          <v-card>
            <v-textarea
              box
              label="雑感"
              @change="thoughtsChanged"
              :value="getMyThoughts"
            >
            </v-textarea>
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
      dayOfReport: null,
      reportDetail: '',
      myThoughts: '',
    };
  },
  async mounted() {
    this.dayOfReport = moment();

    // 一週間分の日付データを作る
    const baseDay = new moment(this.dayOfReport).startOf('week');
    [...Array(7)].forEach((_, index) => {
      const addedDate = new moment(baseDay).add(index, 'days');
      this.days.push(addedDate);
    });

    const userInf = this.$store.getters.userInfo;
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

      // ReportのCommentを取得する
      const reportComment = await axios.getWithJwt(
        `api/report_comment/get/user/${userInf.id}/week/${baseDay.format('YYYY-MM-DD')}`
      );

      if (reportComment !== false) {
        // データが存在するかチェック
        if (reportComment.data.result === true) {
          const reportCommentOfWeek = reportComment.data.data;
          this.reportDetail = reportCommentOfWeek.report_comment;
          this.myThoughts = reportCommentOfWeek.report_opinion;
        }
      }
    }
  },
  methods: {
    async registerToDB() {
      const reportCommentDate = new moment(this.dayOfReport).startOf('week').format('YYYY-MM-DD');

      const reportComment = {
        id: null,
        user_id: this.$store.getters.userInfo.id,
        report_comment: this.reportDetail,
        report_opinion: this.myThoughts,
        date: reportCommentDate
      };
      const registeredResult = await axios.postWithJwt(`api/report_comment/save`, reportComment);
    },
    deitalChanged(value) {
      this.reportDetail = value;
    },
    thoughtsChanged(value) {
      this.myThoughts = value;
    },
  },
  computed: {
    getDetailStr() {
      return this.reportDetail;
    },
    getMyThoughts() {
      return this.myThoughts;
    }
  }
};
</script>

<style lang="scss" scoped>
.report-header {
  z-index: 10;
}
</style>
