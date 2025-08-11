<script setup>
import HeaderNav from '@/Layouts/partials/HeaderNav.vue';
import { Head } from '@inertiajs/vue3'
</script>

<template>

    <Head title="Журнал смен" ></Head>

    <div class="page">
        <header-nav/>
        <slot/>
    </div>
</template>

<style>
.page {
    display: flex;
    flex-direction: column;
    height: 100vh;
}
</style>
