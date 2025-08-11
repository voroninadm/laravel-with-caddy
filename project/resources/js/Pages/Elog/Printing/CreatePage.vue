<script setup>
import TaskForm from "@/Pages/Elog/Printing/partials/TaskForm.vue";

const props = defineProps({
    auth: Object,
    workers: {
        required: true
    },
    machines: {
        required: true
    },
    materials: {
        required: true
    },
});
</script>


<template>
    <TaskForm :workers="workers"
              :machines="machines"
              :materials="materials"
              :auth="props.auth"
    ></TaskForm>
</template>
