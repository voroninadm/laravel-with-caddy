<script setup>
import {Head, Link} from '@inertiajs/vue3'
import NTLlogo from "@/components/icons/NTLlogo.vue";
import PersonalTestingHeader from "@/Layouts/partials/PersonalTestingHeader.vue";
</script>

<template>

    <Head title="Тестирование"></Head>

    <section class="page">
        <PersonalTestingHeader/>
        <slot/>
    </section>
</template>
