<template>
    <div class="workload-container">
        <p class="date-container">
            {{date}}
        </p>
        <p class="amount-container">
            {{amount}}
        </p>
        <p class="project-id-container">
            {{project_id}}
        </p>
        <p class="category-id-container">
            {{category_id}}
        </p>
    </div>
</template>

<script>
export default {
    props: {
        date: {
            type: String,
            required: true,
        },
        amount: {
            type: Number,
            required: true,
            default: 0,
        },
        project_id: {
            type: Number,
            required: true,
        },
        category_id: {
            type: Number,
            required: true,
        },
    }
}
</script>

<style lang="scss" scoped>
.workload-container {
    width: 100%;
}
.date-container {
    width: 20%;
}
.amount-container {
    width: 30%;
}
.project-id-container {
    width: 30%;
}
.category-id-container {
    width: 30%;
}
</style>
