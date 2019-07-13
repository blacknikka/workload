<template>
  <div>
    <v-form
      method="post"
      @submit.prevent="register"
    >
      <v-container>
        <v-layout
          row
          wrap
        >
          <v-flex
            xs3
            sm3
            md3
          >
            <v-text-field
              label="プロジェクトID"
              v-model="projectId"
              type="number"
            ></v-text-field>
          </v-flex>

          <v-flex
            xs3
            sm3
            md3
          >
            <v-text-field
              label="作業ID"
              v-model="categoryId"
              type="number"
            ></v-text-field>
          </v-flex>

          <v-flex
            xs4
            sm4
            md4
          >
            <v-text-field
              label="工数"
              v-model="amount"
              type="number"
            ></v-text-field>
          </v-flex>

          <v-flex
            xs2
            sm2
            md2
          >
            <v-btn
              color="info"
              type="submit"
            >追加</v-btn>
          </v-flex>

        </v-layout>
        <v-layout
          row
          wrap
          v-if="isError"
        >
          <v-flex
            xs12
            sm12
            md12
          >

            <v-chip
              color="red"
              text-color="white"
              style="width: 100%"
            >{{getErrorMessage}}</v-chip>
          </v-flex>
        </v-layout>

      </v-container>
    </v-form>
    <div v-if="isNothing">
      <v-container>
        <v-layout>
          <v-flex
            xs12
            sm12
            md12
          >

            <v-chip
              color="primary"
              text-color="white"
              style="width: 100%"
            >登録ありません</v-chip>
          </v-flex>
        </v-layout>
      </v-container>
    </div>
    <v-list
      two-line
      v-else
    >
      <workload-table :list="list">
      </workload-table>
    </v-list>
  </div>
</template>

<script>
import WorkloadTable from './WorkloadTable';
import Workload from '../../model/workload/workload';
import axios from '../../Util/axios/axios';
import moment from 'moment/moment';

export default {
  components: {
    WorkloadTable
  },
  data() {
    return {
      projectId: null,
      categoryId: null,
      amount: null,
      errorState: false,
      errorMessage: ''
    };
  },
  props: {
    list: {
      type: Array,
      required: true
    }
  },
  mounted() {
    console.log('list', this.list);
  },
  computed: {
    isNothing() {
      return this.list.length === 0;
    },
    isError() {
      return this.errorState === true;
    },
    getErrorMessage() {
      return this.errorMessage;
    }
  },
  methods: {
    getWorkloadData() {
      if (moment(this.$store.getters.getPickedDate).isValid() === false) {
        return false;
      }

      return new Workload(
        null,
        this.$store.getters.getPickedDate,
        Number(this.amount),
        Number(this.projectId),
        Number(this.categoryId),
        true
      );
    },
    async register() {
      // エラーを解除
      this.errorMessage = '';
      this.errorState = false;

      // register
      const workload = this.getWorkloadData();
      console.log(workload);

      if (workload === false) {
        this.errorState = true;
        this.errorMessage = '登録する日付を選んでください';
        return;
      }

      this.$store.commit('addWorklaod', workload);

      // const result = await axios
      //   .postWithJwt('api/workload/set/user_id', post)
      //   .then(response => {
      //     if (response.status !== 200) {
      //       throw new Error(response.status);
      //     }

      //     // 成功
      //     console.log('registration done');
      //   })
      //   .catch(error => {
      //     // something error
      //     console.log(error);

      //     console.log('registration error');
      //   });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
