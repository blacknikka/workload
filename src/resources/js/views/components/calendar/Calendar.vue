<template>
  <div>
    <daily-item v-for="(date, index) in daysArray" :date="date" :key="index"> </daily-item>
  </div>
</template>

<script>
import moment from 'moment';
import DailyItem from './DailyItem';

export default {
  components: {
    DailyItem,
  },
  data() {
    return {
      /**
       * 今月の日付一覧
       */
      daysArray: null,
    };
  },
  mounted() {
    const today = moment();
    this.daysArray = [...Array(today.daysInMonth()).keys()].map(x => {
      return moment({year: today.year(), month: today.month(), day: x + 1});
    });
  },
  computed: {
    getToday() {
      return moment();
    },
  },
};
</script>

<style lang="scss" scoped></style>
