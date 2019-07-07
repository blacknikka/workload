<template>
  <v-app light>
    <v-layout column>
      <header-bar></header-bar>
      <v-layout wrap>
        <v-flex
          xs12
          sm4
          lg4
          class="pa-3"
        >
          <calendar
            v-model="pickedDate"
          ></calendar>
        </v-flex>
        <v-flex
          xs12
          sm8
          lg8
          class="pa-3 mb-3 feature-pane"
        >
          <v-btn
            fab
            outline
            absolute
            small
            left
            color="primary"
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
import Calendar from '../components/Calendar';
import moment from 'moment/moment';

export default {
  components: {
    WorkloadItem,
    HeaderBar,
    Calendar
  },
  data() {
    return {
      pickedDate: '',
      currentMonth: '',
    };
  },
  async mounted() {
    // -------------------
    // user情報を取得する
    const { data } = await axios.getWithJwt('api/auth/me');

    // 取得した情報をセットする
    const user = data.user;
    this.$store.commit('setUserInfo', {
      id: user.id,
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
    const month = moment().format('YYYY-MM');
    const userInf = this.$store.getters.userInfo
    const result = await axios.getWithJwt(`api/workload/get/user/id/${userInf.id}/${month}`);

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
};
</script>

<style lang="scss" scoped>
.feature-pane {
  position: relative;
  padding-top: 30px;
  box-shadow: 0 10px 10px rgba(0, 0, 0, 0.4);
}
.top-month-display {
  font-size: 2em;
}
</style>
