<template>
  <div>
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
            @click="register"
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
      <v-btn
        color="success"
        @click="registerToDB"
      >
        DB登録
      </v-btn>
      <workload-table :workloads="getWorkloadList">
      </workload-table>
      <v-flex
        xs2
        sm2
        md2
      >
      </v-flex>
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
      errorMessage: '',
      list: null
    };
  },
  computed: {
    isNothing() {
      return this.getWorkloadList.length === 0;
    },
    isError() {
      return this.errorState === true;
    },
    getErrorMessage() {
      return this.errorMessage;
    },
    getWorkloadList() {
      return this.$store.getters.workload;
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
    register() {
      // エラーを解除
      this.errorMessage = '';
      this.errorState = false;

      // register
      const workload = this.getWorkloadData();
      if (workload === false) {
        this.errorState = true;
        this.errorMessage = '登録する日付を選んでください';
        return;
      }

      this.$store.commit('addWorklaod', workload);
    },
    async registerToDB() {
      const workload = this.$store.getters.workload;
      const updated = Array.from(workload).map((data, index) => {
        return {
          index,
          data,
        }
      }).filter((item) => {
        // updateされているものを抽出
        return item.data.isUpdated === true;
      });

      if (updated.length > 0) {
        const post = {
          user_id: this.$store.getters.userInfo.id,
          workloads: updated.map((item) => {
            return {
              id: item.data.id,
              project_id: item.data.projectId,
              category_id: item.data.categoryId,
              amount: item.data.amount,
              date: item.data.date,
            };
          }),
        };

        // updated
        const updatedResult = await axios
          .postWithJwt('api/workload/update/user_id', post)

        console.log(updatedResult);

        if (updatedResult !== false && updatedResult.data.result === true) {
          Array.from(updatedResult.data.id).filter((item) => {
            // idが取れたものを取得する
            return item > 0;
          }).forEach((item, index) => {
            const workloadIndex = updated[index].index;
            this.$store.commit(
              'setWorkloadToBeUploaded',
              {
                index: workloadIndex,
                id: item,
              });
          });
        }
      }
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
