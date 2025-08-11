<script setup>
import TaskForm from "@/Pages/Elog/Lamination/partials/TaskForm.vue";

const props = defineProps({
    auth: Object,
    lamTask: {
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
    coverings: {
        required: true
    },
});

</script>


<template>
    <TaskForm
        :lam-task = "lamTask"
        :workers="workers"
        :machines="machines"
        :materials="materials"
        :coverings="coverings"
        :auth="props.auth"
    ></TaskForm>
</template>
