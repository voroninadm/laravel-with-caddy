<script setup>

import '@vuepic/vue-datepicker/dist/main.css';
import VueDatePicker from '@vuepic/vue-datepicker';
import {router, useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/components/PrimaryButton.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import {computed, onMounted, ref} from "vue";
import TextInput from '@/components/TextInput.vue';
import NavDropdownArrow from "@/components/icons/NavDropdownArrow.vue";
import InputError from "@/components/InputError.vue";
import SpoolcutterInfoModal from "@/Pages/JsonOrder/partials/additional/SpoolcutterInfoModal.vue";
import {useTknUpdater} from "@/common/useTknUpdater.js";


const props = defineProps({
    auth: Object,
    isCardFieldDisabled: {
        type: Boolean,
        default: false
    },
    machineType: {
        required: true
    },
    workers: {
        required: true
    },
    spoolcutterWorkout: {
        require: false
    }
})

const emit = defineEmits('toggleMonitoringMode');

const isFiltersOpen = ref(false);
const isSpoolcutterInfoOpen = ref(false);
const isMonitoringMode = ref(false);


const typesForMonitoring = ['printing']; // типы работ, по которым доступен режим мониторинга
const isMonitoringButtonDisabled = computed(() => typesForMonitoring.includes(props.machineType));

// === Filter Scripts ===
const filterForm = useForm({
    start_date: null,
    finish_date: null,
    machineType: 'printing',
    tkn: '',
    spoolcutter: ''
});

const date = ref(new Date());
const dateRangePerform = (modelData) => {
    date.value = modelData;
    filterForm.start_date = date.value[0];
    filterForm.finish_date = date.value[1];
}

const submitFilterForm = async () => {
    if (!isMachinesAndTypesValid()) {
        filterForm.setError('machineType', 'Неверный выбор');
        return;
    }
    isSpoolcutterInfoOpen.value = false;
    filterForm.machineType !== 'spoolcutting' ? filterForm.spoolcutter = null : ""
    isFiltersOpen.value = false;
    await filterForm.post(route('orders'), {})
}

// дни до и после сегодняшнего для авто подстановки в форму
const filterDaysAgo = () => {
    const date = new Date();
    date.setDate(date.getDate() - 3);
    return date;
}

const filterDaysLater = () => {
    const date = new Date();
    date.setDate(date.getDate() + 3);
    return date;
}

const filtersCounter = computed(() => {
    let count = 0;
    if (filterForm.tkn) count++;
    if (filterForm.spoolcutter) count++;

    return +count;
});

const machinesAndTypes = {
    printing: 'Печать',
    lamination: 'Ламинация',
    welding: 'Сварка',
    cutting: 'Резка',
    extrusion: 'Экструзия',
    recycling: 'Переработка',
    spoolcutting: 'Шпулерезка',
}

const isMachinesAndTypesValid = () => {
    return Object.keys(machinesAndTypes).includes(filterForm.machineType);
}

onMounted(() => {
    filterForm.start_date = filterDaysAgo();
    filterForm.finish_date = filterDaysLater();
    date.value = [filterDaysAgo(), filterDaysLater()];
    submitFilterForm();
})

const { updateAndSatisfyTkn } = useTknUpdater(filterForm);

</script>

<template>
    <button v-if="isMonitoringMode" @click="isMonitoringMode = !isMonitoringMode;$emit('toggleMonitoringMode')" type="button" title="Режим мониторинга"
            class="absolute flex font-bold items-center top-1 gap-2 border bg-linear-to-t from-red-50 to-red-200 rounded-lg px-2 py-1 pt-1 left-1/4 z-20 shadow-sm shadow-red-800 hover:from-red-50 hover:to-red-300">
        <svg class="loading-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160">
            <circle cx="80" cy="80" r="55" fill="none" stroke="lightgray" stroke-width="40" />
            <g transform="translate(80, 80)">
                <circle cx="0" cy="0" r="55" fill="none" stroke="red" stroke-width="35" stroke-dasharray="0, 345" stroke-linecap="round">
                    <animateTransform attributeName="transform" type="rotate" from="0" to="360" begin="0s" dur="1s" repeatCount="indefinite" />
                    <animate attributeName="stroke-dasharray" values="0, 345; 172.5, 172.5; 0, 345" dur="2.5s" repeatCount="indefinite" />
                </circle>
            </g>
        </svg>
        <span class="text-lg px-1">Режим мониторинга распоряжений</span>
    </button>
    <section v-show="!isMonitoringMode" @keyup.esc.prevent="filterForm.reset(); isFiltersOpen = false"
             @keyup.enter.prevent.stop="submitFilterForm">
        <form>
            <div
                class="mt-1 mb-4 px-10 py-1 flex items-center justify-between gap-8 border shadow-md shadow-gray-400">
                <div class="flex items-center gap-4">
                    <p class="font-bold text-sm px-8">Распоряжения </p>
                    <VueDatePicker required locale="ru" :format="'dd.MM.yy'" range
                                   auto-apply :enable-time-picker="false"
                                   placeholder="Выберите диапазон дат" :model-value="date"
                                   @update:model-value="dateRangePerform"
                                   input-class-name="dp-custom-input-date"
                                   :clearable="false"
                                   calendar-cell-class-name="dp-custom-cell-date">
                    </VueDatePicker>
                    <div>
                        <select v-model="filterForm.machineType"
                                class="cursor-pointer text-sm border border-gray-300 ring-offset-black ring-0 focus:ring-0 h-9 rounded-sm text-gray-500 ring-red-200 select-custom ">
                            <option v-for="(title, name) in machinesAndTypes" :value="name">{{ title }}</option>
                        </select>

                        <InputError class="text-xs" :message="filterForm.errors.machineType"/>
                    </div>

                    <!-- Дополнительные фильтры -->
                    <div class="relative">
                        <button
                            @click="isFiltersOpen = !isFiltersOpen; isSpoolcutterInfoOpen = false"
                            class="relative w-full inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md border hover:bg-gray-100 transition duration-150"
                            type="button">
                            <svg class="w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                <path
                                    d="M31.078 1.366A1.252 1.252 0 0 0 30 .75H8a1.25 1.25 0 0 0-1.09 1.863l-.003-.006 7.7 13.859v4.391c0 .486.278.908.684 1.114l.007.003 6.285 3.143a1.232 1.232 0 0 0 1.22-.058l-.005.003c.359-.223.594-.615.594-1.062V16.466l7.699-13.859c.1-.175.159-.385.159-.609 0-.233-.064-.452-.176-.638l.003.006zM21.051 15.535a1.235 1.235 0 0 0-.158.607v5.834l-3.785-1.893V16.141c0-.223-.058-.432-.16-.613l.003.006-6.826-12.285h17.751zm-6.837 7.215c-.69 0-1.25.56-1.25 1.25v3.977l-2.928-1.463V23.213c0-.222-.058-.431-.16-.612l.003.006L4.124 12.25h5.32a1.25 1.25 0 0 0 0-2.5H2a1.25 1.25 0 0 0-1.09 1.864l-.003-.006L7.536 23.54v3.746c0 .486.278.907.683 1.114l.007.003 5.428 2.715a1.238 1.238 0 0 0 1.222-.058l-.005.003c.358-.223.593-.615.593-1.062V24c0-.69-.56-1.25-1.25-1.25z"/>
                            </svg>
                            <NavDropdownArrow/>
                        </button>
                        <span v-if="filtersCounter"
                              class="absolute shadow-sm left-12 -top-3 rounded-full text-sm text-white px-2 bg-red-800">{{
                                filtersCounter
                            }}</span>


                        <div v-if="isFiltersOpen"
                             class="absolute right-0 bg-gray-100 flex flex-col gap-2 shadow-lg shadow-gray-500 top-10 border rounded-lg p-2 z-30">
                            <p class="italic text-center text-sm">Дополнительные фильтры:</p>
                            <div class="flex gap-2">
                                <button type="button" @click="filterForm.tkn = ''">
                                    <svg class="w-5 h-5 opacity-50 rotate-180"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 -5 32 32">
                                        <path fill-rule="evenodd"
                                              d="M22.7 7.3a1 1 0 0 0-1.4 0L19 9.6l-2.3-2.3a1 1 0 1 0-1.4 1.5l2.3 2.2-2.3 2.3a1 1 0 0 0 0 1.4c.4.4 1 .4 1.4 0l2.3-2.3 2.2 2.3a1 1 0 1 0 1.4-1.5L20.4 11l2.3-2.3c.4-.4.4-1 0-1.4ZM30 18a2 2 0 0 1-2 2H10.5l-8.2-9 8.1-9H28a2 2 0 0 1 2 2v14ZM28 0H10a1 1 0 0 0-.7.3l-9 10a1 1 0 0 0-.3.7c0 .3 0 .5.3.8l9 9.9c.2.2.4.3.7.3h18a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4Z"/>
                                    </svg>
                                </button>
                                <TextInput
                                    id="tkn"
                                    class="h-6 block placeholder-gray-500 italic text-center text-sm"
                                    v-model="filterForm.tkn"
                                    @input="updateAndSatisfyTkn"
                                    autocomplete="off"
                                    placeholder="номер карты"
                                />
                                <InputError class="mt-2" :message="filterForm.errors.tkn"/>
                            </div>
                            <div v-if="filterForm.machineType === 'spoolcutting'" class="flex gap-2">
                                <button type="button" @click="filterForm.spoolcutter = ''">
                                    <svg class="w-5 h-5 opacity-50 rotate-180"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 -5 32 32">
                                        <path fill-rule="evenodd"
                                              d="M22.7 7.3a1 1 0 0 0-1.4 0L19 9.6l-2.3-2.3a1 1 0 1 0-1.4 1.5l2.3 2.2-2.3 2.3a1 1 0 0 0 0 1.4c.4.4 1 .4 1.4 0l2.3-2.3 2.2 2.3a1 1 0 1 0 1.4-1.5L20.4 11l2.3-2.3c.4-.4.4-1 0-1.4ZM30 18a2 2 0 0 1-2 2H10.5l-8.2-9 8.1-9H28a2 2 0 0 1 2 2v14ZM28 0H10a1 1 0 0 0-.7.3l-9 10a1 1 0 0 0-.3.7c0 .3 0 .5.3.8l9 9.9c.2.2.4.3.7.3h18a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4Z"/>
                                    </svg>
                                </button>
                                <TextInput
                                    id="spoolcutter"
                                    class="h-6 block placeholder-gray-500 italic text-center text-sm"
                                    v-model="filterForm.spoolcutter"
                                    autocomplete="off"
                                    placeholder="Шпулерез"
                                    list="workers"
                                />
                                <InputError class="mt-2" :message="filterForm.errors.spoolcutter"/>
                            </div>
                        </div>
                    </div>

                    <!-- Дополнительная информация -->
                    <div
                        v-if="props.auth.permissions.includes('full_spoolcutting_orders_permission') && spoolcutterWorkout.length!== 0"
                        class="relative">
                        <SpoolcutterInfoModal
                            :date="date"
                            :spoolcutter="filterForm.spoolcutter"
                            :isSpoolcutterInfoOpen="isSpoolcutterInfoOpen"
                            :spoolcutterWorkout="spoolcutterWorkout"
                        />
                    </div>
                    <button v-if="isMonitoringButtonDisabled"
                        @click="isMonitoringMode = !isMonitoringMode; $emit('toggleMonitoringMode')" type="button" title="Режим мониторинга"
                            class="border rounded-lg px-2 py-1.5 shadow-sm hover:bg-gray-100 transition duration"
                            :class="{'bg-emerald-200 hover:bg-emerald-100 relative -top-14 mt-1 z-10' : isMonitoringMode}" >
                        <svg class="w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                            <path d="M486.2 701v135.4a20.5 20.5 0 1 0 41 0V701a20.5 20.5 0 1 0-41 0z"/>
                            <path d="M354.8 878.6h312.3a20.5 20.5 0 1 0 0-41H354.8a20.5 20.5 0 1 0 0 41zm595.5-207.3a30.9 30.9 0 0 0 30.7-30.7V215a30.9 30.9 0 0 0-30.7-30.7H71.7A30.9 30.9 0 0 0 41 215v425.6a30.9 30.9 0 0 0 30.7 30.7h878.6zm0 41H71.7A71.8 71.8 0 0 1 0 640.6V215a71.8 71.8 0 0 1 71.7-71.6h878.6A71.8 71.8 0 0 1 1022 215v425.6a71.8 71.8 0 0 1-71.7 71.7z"/>
                            <path d="M332.7 155.5 17.5 470.6a20.5 20.5 0 1 0 29 29l315.1-315.2a20.5 20.5 0 1 0-29-29zm189 5.5L19.9 662.8a20.5 20.5 0 1 0 29 29L550.7 190a20.5 20.5 0 1 0-29-29z"/>
                        </svg>
                    </button>
                </div>

                <div class="flex gap-20">
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-xs text-gray-600/70 font-bold">Статусы распоряжений:</span>
                        <div class="flex items-center gap-2">
                    <span
                        class="inline-block whitespace-nowrap rounded-full bg-yellow-200 px-[0.65em] pt-[0.35em] pb-[0.25em] text-center align-baseline text-[0.75em] font-bold leading-none text-yellow-800 ">В работе</span>
                            <span
                                class="inline-block whitespace-nowrap rounded-full bg-orange-200 px-[0.65em] pt-[0.35em] pb-[0.25em] text-center align-baseline text-[0.75em] font-bold leading-none text-orange-800 ">Приостановлено</span>
                            <span
                                class="inline-block whitespace-nowrap rounded-full bg-sky-200 px-[0.65em] pt-[0.35em] pb-[0.25em] text-center align-baseline text-[0.75em] font-bold leading-none text-sky-700">Завершено</span>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <primary-button type="button" @click="submitFilterForm">Применить</primary-button>
                        <secondary-button type="button" @click="filterForm.reset(); router.visit('/orders')">Сбросить
                        </secondary-button>
                    </div>
                </div>
            </div>

            <datalist id="workers">
                <option v-for="worker in workers" :value="worker"></option>
            </datalist>
        </form>
    </section>
</template>

<style lang="scss" scoped>
.select-custom {
    appearance: none;
    outline-offset: 0;

    &:focus-within {
        border: none;
        outline-color: rgba(240, 128, 128, 0.62);
    }

    &:focus-visible {
        outline: none;
        border-color: lightgray;

    }
}

.loading-svg {
    width: 25px;
    height: 25px;
}
</style>
