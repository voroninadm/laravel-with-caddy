<script setup>
import UpdateUserProfile from './Partials/UpdateUserProfile.vue'
import UpdateUserPassword from './Partials/UpdateUserPassword.vue'

const props = defineProps(['user', 'sectors'])
</script>

<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <UpdateUserProfile :user="props.user" :sectors="props.sectors"/>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow border sm:rounded-lg">
                <UpdateUserPassword :userId="props.user.id" />
            </div>
        </div>
    </div>
</template>



<style scoped>

</style>
