<script setup>
import TaskForm from "@/Pages/Elog/Recycling/partials/TaskForm.vue";

const props = defineProps({
    auth: Object,
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
    <TaskForm :workers="workers"
              :machines="machines"
              :granulates="granulates"
              :ingots_types="ingots_types"
              :auth="props.auth"
    />

</template>
