<script setup>
import TaskForm from "@/Pages/Elog/Welding/partials/TaskForm.vue";

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
    productTypes: {
        required: true
    },
    bottomTypes: {
        required: true
    },
    boxesTypes: {
        required: true
    },
});
</script>


<template>
    <TaskForm :workers="workers"
              :machines="machines"
              :materials="materials"
              :product-types="productTypes"
              :bottom-types="bottomTypes"
              :boxes-types="boxesTypes"
              :auth="props.auth"
    ></TaskForm>
</template>

