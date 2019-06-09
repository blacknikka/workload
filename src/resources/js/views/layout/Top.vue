<template>
    <div>
        <div class="top-container">
            <router-link :to="{name: 'home'}">Go to home</router-link>
        </div>

        <calendar></calendar>

        <logout></logout>
    </div>
</template>

<script>
import Calendar from '../components/calendar/Calendar';
import Logout from '../components/Logout';

export default {
    methods: {
        toHome() {
            this.$router.push({name: 'home'});
        }
    },
    components: {
        Calendar,
        Logout,
    },
};
</script>

<style lang="scss" scoped>
.top-container {
    width: 100vw;
}
</style>

