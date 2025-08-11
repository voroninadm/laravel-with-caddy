<script setup>
import TaskForm from "@/Pages/Elog/Flexoform/partials/TaskForm.vue";

const props = defineProps({
    auth: Object,
    flexoformTask: {
        required: true
    },
    workers: {
        required: true
    },
    print_machines: {
        required: true
    },
    flexoforms_machines: {
        required: true
    },
    sticky_tape: {
        required: true
    },
});

</script>


<template>
    <TaskForm
        :flexoform-task = flexoformTask
        :workers="workers"
        :print_machines="print_machines"
        :flexoforms_machines="flexoforms_machines"
        :auth="props.auth"
        :sticky_tape="props.sticky_tape"
    ></TaskForm>
</template>


<style scoped>

</style>
