<template>
  <v-app light>
    <v-layout column>
      <header-bar></header-bar>
      <v-layout wrap>
        <v-flex
          sm12
          lg3
          class="pa-3 mb-3 feature-pane"
        >
          <v-btn
            fab
            outline
            small
            absolute
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
            small
            absolute
            right
            color="primary"
            @click="$refs.calendar.next()"
          >
            <v-icon dark>
              keyboard_arrow_right
            </v-icon>
          </v-btn>
        </v-flex>
        <v-flex
          sm12
          lg9
          class="pl-3"
        >
          <v-sheet height="500">
            <v-calendar
              ref="calendar"
              :now="today"
              :value="today"
              color="primary"
              locale="ja-jp"
            >
            </v-calendar>
          </v-sheet>
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
  data() {
    return {
      today: '2019-07-04'
    };
  },
  components: {
    WorkloadItem,
    HeaderBar
  },
  async mounted() {
    this.today = moment().format('YYYY-MM-DD');

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
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
