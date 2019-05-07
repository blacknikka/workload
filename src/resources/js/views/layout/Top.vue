<template>
    <div>
        <div
        class="top-container"
        @click="toHome">
            top page
        </div>

        <calendar></calendar>
    </div>
</template>

<script>
import Calendar from '../components/calendar/Calendar';

export default {
    methods: {
        toHome() {
            this.$router.push('home')
        }
    },
    components: {
        Calendar,
    },
};
</script>

<style lang="scss" scoped>
.top-container {
    width: 100vw;
}
</style>

