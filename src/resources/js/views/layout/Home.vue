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
          <calendar></calendar>
        </v-flex>
        <v-flex
          xs12
          sm8
          lg8
          class="pa-3 mb-3 feature-pane"
        >
          <workload-items></workload-items>
        </v-flex>
      </v-layout>
    </v-layout>
  </v-app>
</template>

<script>
import axios from '../../Util/axios/axios';
import WorkloadItems from '../components/WorkloadItems';
import HeaderBar from '../components/headerBar';
import Calendar from '../components/Calendar';
import moment from 'moment/moment';
import Workload from '../../model/workload/workload';

export default {
  components: {
    WorkloadItems,
    HeaderBar,
    Calendar
  },
  data() {
    return {
      pickedDate: '',
      currentMonth: ''
    };
  },
  async mounted() {
    this.$store.commit('clearWorkload');

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
    // ProjectとCateogoryの取得
    const projectResult = await axios.getWithJwt('api/project/get');

    if (projectResult !== false) {
      this.$store.commit('setProjectData', projectResult.data.project);
      this.$store.commit('setCategoryData', projectResult.data.category);
    }

    // -------------------
    // データ取得
    const month = moment().format('YYYY-MM');
    const userInf = this.$store.getters.userInfo;
    const workloadResult = await axios.getWithJwt(`api/workload/get/user/id/${userInf.id}/month/${month}`);

    if (workloadResult !== false) {
      const filteredData = Array.from(workloadResult.data.data).map(data => {
        const dateString = data.date.replace(/T.*/, '');

        // Workloadオブジェクトを作成。
        return new Workload(
          data.id,
          dateString,
          data.amount,
          this.$store.getters.getProjectFromId(data.project_id),
          this.$store.getters.getCategoryFromId(data.category_id),
          false
        );
      });
      this.$store.commit('setWorkload', filteredData);
    }
  }
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
