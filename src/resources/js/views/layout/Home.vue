<template>
  <div>
    home.
  </div>
</template>

<script>
import axios from '../../Util/axios/axios';

export default {
    async mounted() {
        const result = await axios.get('api/workload/get/user_id/1');
        console.log(result);
    },
};
</script>

<style lang="sass" scoped>

</style>
