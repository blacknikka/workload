<template>
  <div>
    <v-layout column>
      <header-bar></header-bar>

      <v-sheet height="500">
        <v-calendar
          :now="today"
          :value="today"
          color="primary"
        >
        </v-calendar>
      </v-sheet>
    </v-layout>
  </div>
</template>

<script>
import axios from '../../Util/axios/axios';
import WorkloadItem from '../components/WorkloadItem';
import HeaderBar from '../components/headerBar';
import moment from 'moment/moment';

export default {
  data() {
    return {
      today: '2019-07-04',
    };
  },
  components: {
    WorkloadItem,
    HeaderBar
  },
  async mounted() {
    this.today = '2019-07-04';

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
