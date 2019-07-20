<template>
  <v-container>
    <v-card>
      <job-detail-item
        v-for="(workload,index) in workloads"
        :key="index"
        :workload="date"
      >
      </job-detail-item>
    </v-card>
  </v-container>
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
  mounted() {}
};
</script>
