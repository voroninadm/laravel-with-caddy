<script setup>
import PrimaryButton from '@/components/PrimaryButton.vue';
import Checkbox from '@/components/Checkbox.vue';
import {computed, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import ExportToExcelButton from '@/components/buttons/ExportToExcelButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import axios from 'axios';
import { saveAs } from 'file-saver';

const props = defineProps({
    title: String,
    route: String,
    isExcelButtonDisabled: Boolean,
    excelReportName: String
})

const date = ref();
const form = useForm({
    start_date: null,
    finish_date: null,
    machines: {
        printing: false,
        lamination: false,
        welding: false,
        cutting: false,
        extrusion: false,
        recycling: false,
    },
    convertToExcel: false
});

const dateRangePerform = (modelData) => {
    date.value = modelData;
    form.start_date = date.value[0].toISOString().split('T')[0];
    form.finish_date = date.value[1].toISOString().split('T')[0];
}

const submit = () => {
    form.post(props.route);
}

const formReset = () => {
    form.reset();
    location.reload();
}

const isConverting = ref(false);
const isBlocking = computed(() => {
    return props.isExcelButtonDisabled || isConverting.value
})
const exportToExcel = () => {
    isConverting.value = true;
    const requestData = {
        start_date: form.start_date,
        finish_date: form.finish_date,
        machines: {
            printing: form.machines.printing,
            lamination: form.machines.lamination,
            welding: form.machines.welding,
            cutting: form.machines.cutting,
            extrusion: form.machines.extrusion,
            recycling: form.machines.recycling,
        },
        convertToExcel: true,
    };

    axios.post(props.route, requestData, { responseType: 'blob' })
        .then(response => {
            // Используем FileSaver.js для сохранения файла
            saveAs(response.data, props.excelReportName);
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
    <form @submit.prevent="submit">
        <div class="mt-1 mb-4 px-2 py-2 flex items-center justify-center gap-8 border shadow-md shadow-gray-400">
            <div class="flex gap-6 items-center">
                <p class="mr-10 font-bold text-sm text-center"> {{ props.title }} </p>
                <VueDatePicker class='w-64 z-30 mr-10' required locale="ru" :format="'dd.MM.yyyy'" range auto-apply
                               placeholder="Выберите даты" :model-value="date" @update:model-value="dateRangePerform"
                               input-class-name="dp-custom-input" calendar-cell-class-name="dp-custom-cell"
                               :enable-time-picker="false">
                </VueDatePicker>

                <div class="flex items-center gap-2">
                    <div class="flex ml-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.machines.printing"/>
                                <span class="ml-1 text-sm text-gray-600 ">Печать</span>
                            </label>
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.machines.lamination"/>
                                <span class="ml-1 text-sm text-gray-600 ">Ламинация</span>
                            </label>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.machines.welding"/>
                                <span class="ml-1 text-sm text-gray-600 ">Сварка</span>
                            </label>
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.machines.cutting"/>
                                <span class="ml-1 text-sm text-gray-600 ">Резка</span>
                            </label>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.machines.extrusion"/>
                                <span class="ml-1 text-sm text-gray-600 ">Экструзия</span>
                            </label>
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.machines.recycling"/>
                                <span class="ml-1 text-sm text-gray-600 ">Переработка</span>
                            </label>
                        </div>
                    </div>
                </div>

                <ExportToExcelButton class="ml-20" @click="exportToExcel()" :is-disabled="isBlocking" />

                <div class="flex gap-6 ml-10">
                    <button @click="formReset()"
                            class="border px-2 uppercase font-semibold rounded-md text-xs hover:bg-gray-100 active:bg-gray-200 transition ease-in-out duration-15">
                        Очистить
                    </button>
                    <PrimaryButton>Сформировать</PrimaryButton>
                </div>

                <div v-if="isConverting">
                    <div class="absolute top-56 w-1/3 left-[35%] h-20 z-30 p-2 bg-gray-600/50 text-white rounded-lg flex justify-center align-center shadow-sm">
                        <p class="m-auto animate-pulse">Подождите, идет выгрузка...</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>


<style scoped>

</style>
