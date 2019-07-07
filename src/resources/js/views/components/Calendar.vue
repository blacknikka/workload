<template>
  <v-sheet
    height="100%"
    width="100%"
    class="pa-3"
  >
    <v-layout
      row
      justify-center
    >
      <v-date-picker
        v-model="picker"
        locale="ja-jp"
        :day-format="getDateStr"
        :full-width="true"
        :show-current="true"
      ></v-date-picker>
    </v-layout>
  </v-sheet>

</template>

<script>
import moment from 'moment/moment';

export default {
  data() {
    return {
      start: '2019-1-1',
      picker: ''
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
    isSaturday(day) {
      return moment(day).day();
    },
    getDateStr(date) {
      return moment(date).date();
    },
  },
  watch: {
    start(newStart, oldStart) {
      this.$emit('currentChanged', newStart);
    }
  }
};
</script>

<style lang="sass" scoped>
</style>

<style>
.v-date-picker-table.v-date-picker-table--date > table > tbody tr td:nth-child(7) .v-btn__content {
    color:blue
}
.v-date-picker-table.v-date-picker-table--date > table > tbody tr td:nth-child(1) .v-btn__content {
    color:red
}
</style>
