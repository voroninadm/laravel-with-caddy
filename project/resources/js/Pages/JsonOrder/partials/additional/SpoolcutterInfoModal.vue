<script setup>

import ExportToExcelButton from "@/components/buttons/ExportToExcelButton.vue";
import {computed, ref} from "vue";
import NavDropdownArrow from "@/components/icons/NavDropdownArrow.vue";
import axios from "axios";
import {saveAs} from 'file-saver';

const props = defineProps({
    spoolcutterWorkout: {
        require: false
    },
    date: {
        require: false
    },
    spoolcutter: {
        require: false
    },
    isSpoolcutterInfoOpen: Boolean
})

const countOfTopWorkout = 5;
const isModalOpen = ref(props.isSpoolcutterInfoOpen);
const isConverting = ref(false);


const sortedSpoolcutterWorkoutTop10 = computed(() => {
    if (props.spoolcutterWorkout['workout'].length !== 0) {
        return Object.entries(props.spoolcutterWorkout['workout'])
            .map(([width, count]) => ({width, count}))
            .sort((a, b) => b.count - a.count)
            .slice(0, countOfTopWorkout);
    } else {
        return [];
    }
});

const totalSpools = computed(() => {
    if (props.spoolcutterWorkout['workout'].length !== 0) {
        return Object.values(props.spoolcutterWorkout['workout']).reduce((total, count) => total + count, 0);
    }
});

const exportToExcel = () => {
    isConverting.value = true;
    isModalOpen.value = false;
    const requestData = {
        spoolcutterData: props.spoolcutterWorkout,
        dates: props.date
    };

    axios.post(route('order.spoolcutter'), requestData, {responseType: 'blob'})
        .then(response => {
            // Используем FileSaver.js для сохранения файла
            saveAs(response.data, `${props.spoolcutterWorkout['spoolcutter']}.xlsx`);
        })
        .catch(error => {
            console.error('Ошибка при экспорте в Excel', error);
        })
        .finally(() => {
            isConverting.value = false;
        });
};
</script>

<template>
    <section v-if="props.spoolcutterWorkout['workout'].length !== 0">
        <div>
            <button
                @click="isModalOpen = !isModalOpen" @keydown.esc="isModalOpen = false"
                class="w-full inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md border hover:bg-gray-100 transition duration-150"
                type="button">
                <svg class="w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                    <path d="M10 18h8v2h-8zM10 13h12v2H10zM10 23h5v2h-5z"/>
                    <path
                        d="M25 5h-3V4a2 2 0 0 0-2-2h-8a2 2 0 0 0-2 2v1H7a2 2 0 0 0-2 2v21a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2ZM12 4h8v4h-8Zm13 24H7V7h3v3h12V7h3Z"/>
                    <path d="M0 0h32v32H0z" data-name="&lt;Transparent Rectangle&gt;"
                          style="fill:none"/>
                </svg>
                <NavDropdownArrow/>
            </button>
        </div>
        <div v-if="isModalOpen"
             class="absolute w-96 bg-gray-50 flex flex-col gap-2 shadow-lg shadow-gray-500 top-10 left-0 border rounded-lg p-2 z-30">
            <div class="flex flex-col justify-center items-center text-center text-sm">
                <p class="text-xs text-gray-600">Первые {{ countOfTopWorkout }} позиций по выработке
                    {{ props.spoolcutterWorkout['spoolcutter'] }}</p>
            </div>
            <table class="min-w-max w-full table-auto">
                <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-xs leading-normal">
                    <th class="text-center">Ширина</th>
                    <th class="text-center">Количество</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">

                <tr v-for="(item, index) in sortedSpoolcutterWorkoutTop10" :key="index"
                    class="border-b border-gray-200 hover:bg-gray-100 text-xs">
                    <td class="text-center">{{ item.width }}</td>
                    <td class="text-center">{{ item.count }}</td>
                </tr>
                </tbody>
            </table>
            <div class="flex justify-center text-sm justify-between items-center mt-4 gap-5 px-5">
                <p class="font-bold text-sm ">Всего шпуль: {{ totalSpools }} </p>
                <button type="button" class="flex items-center gap-2 border rounded-lg px-2 hover:bg-gray-200 transition duration-300"
                        @click="exportToExcel">
                    <p>Выгрузить отчет</p>
                    <ExportToExcelButton width="5" padding="1" bg-white></ExportToExcelButton>
                </button>
            </div>
        </div>
    </section>


    <div v-if="isConverting">
        <transition class="animate__animated animate__fadeIn">
            <div class="fixed inset-0 w-screen h-screen bg-black/40 z-30">
                <div
                    class="fixed top-56 w-96 left-[40%] h-20 z-30 p-2 bg-black/60 text-white font-bold rounded-lg flex justify-center align-center shadow-sm">
                    <p class="m-auto animate-pulse">Подождите, идет выгрузка...</p>
                </div>
            </div>
        </transition>
    </div>

</template>

<style scoped>

</style>
