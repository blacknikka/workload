<template>
  <v-card>
    <v-layout wrap>
      <v-flex
        xs2
        sm2
        md2
      >
        <v-card-text>{{thisDate}}</v-card-text>
      </v-flex>

      <v-flex
        xs10
        sm10
        md10
      >
        <div v-if="isWorkloadExists">
          <job-detail-item
            v-for="(workload,index) in workloads"
            :key="index"
            :workload="workload"
          >
          </job-detail-item>
        </div>
        <div v-else>
          <v-card-text>登録ありません</v-card-text>
        </div>
      </v-flex>
    </v-layout>
  </v-card>
</template>

<script>
import JobDetailItem from './JobDetailItem';
import moment from 'moment/moment';

export default {
  components: {
    JobDetailItem
  },
  props: {
    workloads: {
      type: Array,
      required: true
    },
    day: {
      type: Object
    }
  },
  computed: {
    isWorkloadExists() {
      return this.workloads.length > 0;
    },
    thisDate() {
      return moment.isMoment(this.day) === true ? this.day.format('MM/DD') : '';
    }
  },
  mounted() {}
};
</script>
