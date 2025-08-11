<script setup>
import TaskForm from "@/Pages/Elog/Cutting/partials/TaskForm.vue";

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

});
</script>


<template>
    <TaskForm :workers="workers"
              :machines="machines"
              :materials="materials"
              :product-types="productTypes"
              :auth="props.auth"
    />

</template>
