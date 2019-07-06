<template>
  <v-sheet
    height="500"
    width="100%"
  >
    <v-calendar
      ref="calendar"
      :now="getToday"
      :value="getToday"
      start="2019-1-1"
      end="2019-7-1"
      color="primary"
      locale="ja-jp"
      type="month"
      v-model="start"
    >
    </v-calendar>
  </v-sheet>

</template>

<script>
import moment from 'moment/moment';

export default {
  data() {
    return {
      start: '2019-1-1',
    };
  },
  created() {
    this.start = moment().format('YYYY-MM-DD');
  },
  computed: {
    getToday() {
      return moment().format('YYYY-MM-DD');
    }
  },
  methods: {
    next() {
      this.$refs.calendar.next();
    },
    prev() {
      this.$refs.calendar.prev();
    },
  },
  watch: {
    start(newStart, oldStart) {
      this.$emit('currentChanged', newStart)
    },
  },
};
</script>

<style lang="sass" scoped>

</style>
