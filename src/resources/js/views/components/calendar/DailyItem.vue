<template>
  <div
    :class="{'saturday-style': isSaturday, 'sunday-style': isSunday, 'normal-day-style': isNormalDay}"
    @click="clicked"
  >
    {{ today }}
  </div>
</template>

<script>
import moment from 'moment';
import axios from '../../../Util/axios/axios';

export default {
  props: {
    date: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      moment: null,
    };
  },
  methods: {
    async clicked() {
      const result = await axios.post('api/workload/set/user_id', {
        user_id: 1,
        project_id: 1,
        category_id: 1,
        amount: 2.5,
        date: this.date,
      });
      console.log(result);
    },
  },
  computed: {
    isSaturday() {
      return this.date.day() === 6;
    },
    isSunday() {
      return this.date.day() === 0;
    },
    isNormalDay() {
      const day = this.date.day();
      return day !== 0 && day !== 6;
    },
    today() {
      return this.date.date();
    },
  },
};
</script>

<style lang="scss" scoped>
.saturday-style {
  background-color: lightskyblue;
}

.sunday-style {
  background-color: pink;
}

.normal-day-style {
  background-color: oldlace;
}
</style>
