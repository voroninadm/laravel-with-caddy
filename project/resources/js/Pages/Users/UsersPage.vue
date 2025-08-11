<script setup>
import {Head} from '@inertiajs/vue3';
import UsersTable from '@/Pages/Users/Partials/UsersTable.vue';

const props = defineProps({
    auth: Object,
    users: {
        required: true
    },
})
</script>

<template>
    <Head title="Пользователи"/>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg">
                <UsersTable :users="users" />
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
