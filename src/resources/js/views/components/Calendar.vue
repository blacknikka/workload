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
        v-model="pickerData"
        locale="ja-jp"
        :day-format="getDateStr"
        :full-width="true"
      ></v-date-picker>
    </v-layout>
  </v-sheet>

</template>

<script>
import moment from 'moment/moment';

export default {
  data() {
    return {
    };
  },
  model: {
    prop: 'picker',
    event: 'input'
  },
  props: {
      picker: String,
  },
  computed: {
    getToday() {
      return moment().format('YYYY-MM-DD');
    },
    pickerData: {
      // pickしたデータを親コンポーネントと共有する。
      get() {
        this.picker;
      },
      set(value) {
        this.$store.commit('setPickedDate', value);
      },
    },
  },
  methods: {
    getDateStr(date) {
      return moment(date).date();
    }
  },
};
</script>

<style lang="sass" scoped>
</style>

<style>
.v-date-picker-table.v-date-picker-table--date > table > tbody tr td:nth-child(7) .v-btn__content {
  color: blue;
}
.v-date-picker-table.v-date-picker-table--date > table > tbody tr td:nth-child(1) .v-btn__content {
  color: red;
}
</style>
