<template>
    <div :class="{'saturday-style': isSaturday, 'sunday-style': isSunday, 'normal-day-style': isNormalDay}">
        {{today}}
    </div>
</template>

<script>
import moment from 'moment';

export default {
    props : {
        date: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            moment : null,
        }
    },
    created() {
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
}
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
