<script setup>
import TaskForm from "@/Pages/Elog/Cutting/partials/TaskForm.vue";

const props = defineProps({
    auth: Object,
    cuttingTask: {
        required: true
    },
    workers: {
        required: true
    },
    machines: {
        required: true
    },
    materials: {
        required: true
    },
    productTypes: {
        required: true
    },
});

</script>


<template>
    <TaskForm
        :cutting-task = "cuttingTask"
        :workers="workers"
        :machines="machines"
        :materials="materials"
        :product-types="productTypes"
        :auth="props.auth"
    />
</template>
