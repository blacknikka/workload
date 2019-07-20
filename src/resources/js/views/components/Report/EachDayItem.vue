<template>
  <div class="pa-1 pl-5">
    <v-layout
      row
      wrap
    >
      <v-flex
        xs11
        sm11
        md11
      >
        <v-card>
          <v-layout
            row
            wrap
          >
            <v-flex
              xs2
              sm2
              md2
            >
              <v-card-text class="ma-1 ml-3">{{thisDate}}</v-card-text>
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
                <v-flex
                  xs12
                  sm12
                  md12
                >
                  <v-card-text class="ma-1">登録ありません</v-card-text>
                </v-flex>
              </div>
            </v-flex>
          </v-layout>
        </v-card>
      </v-flex>
    </v-layout>

  </div>
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
      type: Object,
    }
  },
  computed: {
    isWorkloadExists() {
      return this.workloads.length > 0;
    },
    thisDate() {
      return moment.isMoment(this.day) === true ?
        this.day.format('MM/DD'):
        '';
    }
  },
  mounted() {
    console.log(this.workloads);
  }
};
</script>
