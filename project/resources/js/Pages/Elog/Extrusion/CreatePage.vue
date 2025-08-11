<script setup>
import TaskForm from "@/Pages/Elog/Extrusion/partials/TaskForm.vue";

const props = defineProps({
    auth: Object,
    workers: {
        required: true
    },
    machines: {
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
              :product-types="productTypes"
              :auth="props.auth"
    />

</template>
