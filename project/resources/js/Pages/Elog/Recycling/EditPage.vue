<script setup>
import TaskForm from "@/Pages/Elog/Recycling/partials/TaskForm.vue";

const props = defineProps({
    auth: Object,
    recyclingTask: {
        required: true
    },
    workers: {
        required: true
    },
    machines: {
        required: true
    },
    granulates: {
        required: true
    },
    ingots_types: {
        required: true
    },
});

</script>


<template>
    <TaskForm
        :recycling-task = "recyclingTask"
        :workers="workers"
        :machines="machines"
        :auth="props.auth"
        :granulates="granulates"
        :ingots_types="ingots_types"
    />
</template>
