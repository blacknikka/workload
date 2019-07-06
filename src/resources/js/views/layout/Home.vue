<template>
  <v-app light>
    <v-layout column>
      <header-bar></header-bar>
      <v-layout wrap>
        <v-flex
          xs12
          sm7
          lg7
          class="pa-3"
        >
          <v-layout column>
            <v-chip
              label
              class="pa-3 ma-3 top-month-display"
            >{{getTopDisplay}}</v-chip>
            <v-sheet
              height="500"
              width="100%"
            >
              <v-calendar
                ref="calendar"
                :now="getToday"
                :value="getToday"
                start="2019-1-1"
                end="2019-7-1"
                color="primary"
                locale="ja-jp"
                type="month"
                v-model="start"
              >
              </v-calendar>
            </v-sheet>
          </v-layout>
        </v-flex>
        <v-flex
          xs12
          sm5
          lg5
          class="pa-3 mb-3 feature-pane"
        >
          <v-btn
            fab
            outline
            absolute
            small
            left
            color="primary"
            @click="$refs.calendar.prev()"
          >
            <v-icon dark>
              keyboard_arrow_left
            </v-icon>
          </v-btn>

          <v-btn
            fab
            outline
            absolute
            small
            right
            color="primary"
            @click="$refs.calendar.next()"
          >
            <v-icon dark>
              keyboard_arrow_right
            </v-icon>
          </v-btn>
          <br><br><br>
        </v-flex>
      </v-layout>
    </v-layout>
  </v-app>
</template>

<script>
import axios from '../../Util/axios/axios';
import WorkloadItem from '../components/WorkloadItem';
import HeaderBar from '../components/headerBar';
import moment from 'moment/moment';

export default {
  components: {
    WorkloadItem,
    HeaderBar
  },
  data() {
    return {
      start: '2019-1-1',
    };
  },
  created() {
    this.start = moment().format('YYYY-MM-DD');
  },
  async mounted() {
    // -------------------
    // user情報を取得する
    const { data } = await axios.getWithJwt('api/auth/me');

    // 取得した情報をセットする
    const user = data.user;
    this.$store.commit('setUserInfo', {
      name: user.name,
      email: user.email,
      department: {
        departmentName: user.department.name,
        sectionName: user.department.sectionName,
        comment: user.department.comment
      }
    });

    // -------------------
    // データ取得
    const result = await axios.getWithJwt('api/workload/get/user_id/1');

    const filteredData = Array.from(result.data).map(data => {
      const date = data.date.replace(/T.*/, '');
      return {
        date,
        amount: data.amount,
        project_id: data.project_id,
        category_id: data.category_id
      };
    });
    this.$store.commit('setWorkload', filteredData);
  },
  computed: {
    getWorkload() {
      return this.$store.getters.workload;
    },
    getToday() {
      return moment().format('YYYY-MM-DD');
    },
    getThisMonth() {
      return moment().format('YYYY-MM');
    },
    getTopDisplay() {
      return moment(this.start).format('YYYY-MM');
    },
  }
};
</script>

<style lang="scss" scoped>
.feature-pane {
  position: relative;
  padding-top: 30px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
}
.top-month-display {
  font-size: 2em;
}
</style>
