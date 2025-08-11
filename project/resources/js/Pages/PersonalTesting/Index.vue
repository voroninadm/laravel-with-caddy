<script>
import PersonalTestingLayout from '@/Layouts/PersonalTestingLayout.vue';

export default {
    layout: PersonalTestingLayout
}
</script>

<script setup>

import PersonalTestCard from "@/Pages/PersonalTesting/partials/PersonalTestCard.vue";
</script>

<template>
    <section class="w-full h-full  bg-linear-to-r from-red-50 via-purple-100 to-gray-100">
        <h1 class="hidden">Главная страница тестов и анкетирования</h1>

        <div class="flex flex-col gap-10 p-4">
            <div>
                <h2 class="flex flex-col text-gray-500 uppercase ml-10">Отдел кадров</h2>
                <div class="w-full h-1 rounded-full bg-linear-to-r from-indigo-200 via-gray-200 to-inherit"></div>
                <div class="grid grid-cols-7 gap-5 px-4 py-4">
                    <PersonalTestCard :route-name="'hiring.index'"
                                      :imageName="'hiring.webp'"
                                      :title="'Трудоустройство'"/>
                </div>
            </div>
        </div>

    </section>
</template>

<style scoped>

</style>
