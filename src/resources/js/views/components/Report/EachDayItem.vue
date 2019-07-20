<template>
  <div class="pa-1 pl-5">
    <v-flex
      xs6
      sm6
      md6
    >
      <v-card>
        <div v-if="isWorkloadExists">
          <job-detail-item
            v-for="(workload,index) in workloads"
            :key="index"
            :workload="date"
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
      </v-card>
    </v-flex>
  </div>
</template>

<script>
import JobDetailItem from './JobDetailItem';

export default {
  components: {
    JobDetailItem
  },
  props: {
    workloads: {
      type: Array,
      required: true
    }
  },
  computed: {
    isWorkloadExists() {
      return this.workloads.length > 0;
    }
  }
};
</script>
