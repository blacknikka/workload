<template>
    <div>
        <daily-item :date="new Date('2019-5-1')"></daily-item>
    </div>
</template>

<script>
import DailyItem from './DailyItem';
export default {
    components: {
        DailyItem,
    },
}
</script>

<style lang="scss" scoped>

</style>
