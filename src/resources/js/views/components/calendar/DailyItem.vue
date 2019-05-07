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
            // ??
            type: Date,
            required: true,
        },
    },
    data() {
        return {
            moment : null,
        }
    },
    created() {
        this.moment = moment(this.data);
    },
    computed: {
        isSaturday() {
            return this.moment.day() === 6;
        },
        isSunday() {
            return this.moment.day() === 0;
        },
        isNormalDay() {
            const day = this.moment.day();
            return day !== 0 && day !== 6;
        },
        today() {
            return this.moment.date();
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
