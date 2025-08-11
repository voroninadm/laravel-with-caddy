<script setup>
import TextInput from '@/components/TextInput.vue';
import InputError from '@/components/InputError.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import Checkbox from '@/components/Checkbox.vue';
import {computed, onMounted, onUpdated, ref, toRef, toRefs, watch, watchEffect} from "vue";
import {useForm} from "@inertiajs/vue3";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import axios from "axios";
import ExportToExcelButton from '@/components/buttons/ExportToExcelButton.vue';
import {saveAs} from 'file-saver';
import NavDropdownArrow from "@/components/icons/NavDropdownArrow.vue";
import {checkMonthsToExcelExport} from "@/common/dateTimeHelper.js";
import {showNotification} from "@/common/notifications.js";
import {MAX_DAYS_TO_EXCEL_EXPORT} from "@/common/constants.js";
import {useTknUpdater} from "@/common/useTknUpdater.js";

const props = defineProps({
    auth: Object,
    header: {
        required: true
    },
    machines: {
        required: true
    },
    workers: {
        required: true
    },
    nomenclatures: {
        required: false
    },
    routeToSubmit: {
        required: true
    },
    localStorageKey: {
        required: false
    },
    isExcelButtonDisabled: Boolean,
    isWorkerFieldDisabled: {
        default: false
    },
    isTknFieldDisabled: {
        default: false
    },

})

const isFiltersOpen = ref(false);


const date = ref();
const form = useForm({
    start_date: null,
    finish_date: null,
    machine: '',
    worker: '',
    tkn: '',
    not_completed: false,
    is_viket: false,
    is_otk: false,
    part_number: '',
    nomenclature: ''
});


const filtersCounter = computed(() => {
    let count = 0;
    if (form.machine) count++;
    if (form.worker) count++;
    if (form.tkn) count++;
    if (form. not_completed) count++;
    if (form.is_viket) count++;
    if (form.is_otk) count++;
    if (form.part_number) count++;
    if (form.nomenclature) count++;
    return count;
});

const dateRangePerform = (modelData) => {
    date.value = modelData;
    form.start_date = date.value[0].toISOString().split('T')[0];
    form.finish_date = date.value[1].toISOString().split('T')[0];
}


const submit = () => {
    isFiltersOpen.value = false;
    form.post(route(props.routeToSubmit));
    if (props.localStorageKey) {
        saveSearchParameters();
    }
}

const isConverting = ref(false);
const isBlocking = computed(() => {
    return props.isExcelButtonDisabled || isConverting.value
})
const exportToExcel = () => {
    const urlContainsWorksReport = window.location.href.includes("worksReport");

    if (urlContainsWorksReport || (checkMonthsToExcelExport(form.start_date, form.finish_date) && isManagerCanSee())) {
        isConverting.value = true;
        const requestData = {
            start_date: form.start_date,
            finish_date: form.finish_date,
            machine: form.machine,
            worker: form.worker,
            tkn: form.tkn,
            not_completed: form.not_completed,
            is_viket: form.is_viket,
            is_otk: form.is_otk,
            convertToExcel: true,
            part_number: form.part_number,
            nomenclature: form.nomenclature
        };

        axios.post(route(props.routeToSubmit), requestData, { responseType: 'blob' })
            .then(response => {
                // Используем FileSaver.js для сохранения файла
                saveAs(response.data, `${props.header}.xlsx`);
            })
            .catch(error => {
                console.error('Ошибка при экспорте в Excel', error);
            })
            .finally(() => {
                isConverting.value = false;
            });
    } else {
        showNotification('info', `Нельзя выгрузить данные, если выбрано более ${MAX_DAYS_TO_EXCEL_EXPORT} дня.
            <br>
            Для получения показателей за выбранный период перейдите в раздел "Показатели работ"`);
    }
};


onMounted(() => {
    const savedSearchParameters = localStorage.getItem(props.localStorageKey);
    if (savedSearchParameters) {
        const searchParameters = JSON.parse(savedSearchParameters);
        date.value = searchParameters.date
        form.start_date = searchParameters.start_date;
        form.finish_date = searchParameters.finish_date;
        form.machine = searchParameters.machine ?? '';
        form.worker = searchParameters.worker ?? '';
        form.tkn = searchParameters.tkn ?? '';
        form.not_completed = searchParameters.not_completed ?? '';
        form.is_viket = searchParameters.is_viket ?? false;
        form.part_number = searchParameters.part_number ?? false;
        form.nomenclature = searchParameters.nomenclature;
        submit();
    }
});

function saveSearchParameters() {
    const searchParameters = {
        date: date.value,
        start_date: form.start_date,
        finish_date: form.finish_date,
        machine: form.machine,
        worker: form.worker,
        tkn: form.tkn,
        not_completed: form.not_completed,
        is_viket: form.is_viket,
        part_number: form.part_number,
        nomenclature: form.nomenclature,
    };
    localStorage.setItem(props.localStorageKey, JSON.stringify(searchParameters));
}

function clearSearchParameters() {
    localStorage.removeItem(props.localStorageKey);
}

const formReset = () => {
    form.reset();
    date.value = '';
    clearSearchParameters();
    location.reload();
}

/**
 * Для менеджера - некоторые фильтрры
 * @returns {boolean}
 */
const isManagerCanSee = () => {
    return !props.auth.role.includes('manager')
};

const { updateAndSatisfyTkn } = useTknUpdater(form);

</script>

<template>
    <form @keyup.esc.prevent="formReset" @keyup.enter.prevent.stop="submit">
        <div class="mt-1 mb-4 px-2 py-2 flex items-center justify-center gap-2 border shadow-md shadow-gray-400">
            <div class="w-11/12 flex justify-between gap-2 items-center justify-center">
                <div class=" flex items-center gap-2 mr-40">
                    <p class="w-80 px-3 mr-7 font-bold text-sm text-center">{{ props.header }}</p>
                    <VueDatePicker class='w-60 z-30' required locale="ru" :format="'dd.MM.yyyy'" range auto-apply
                                   placeholder="Выберите даты" :model-value="date"
                                   @update:model-value="dateRangePerform"
                                   input-class-name="dp-custom-input-date"
                                   calendar-cell-class-name="dp-custom-cell-date"
                                   :enable-time-picker="false">
                    </VueDatePicker>

                    <!-- Дополнительные фильтры -->
                    <div class="relative">
                        <button v-if="isManagerCanSee()"
                                @click="isFiltersOpen = !isFiltersOpen"
                                class="relative w-full inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md border hover:bg-gray-100 transition ease-in-out duration-150"
                                type="button">

                            <svg class="w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                <path
                                    d="M31.078 1.366A1.252 1.252 0 0 0 30 .75H8a1.25 1.25 0 0 0-1.09 1.863l-.003-.006 7.7 13.859v4.391c0 .486.278.908.684 1.114l.007.003 6.285 3.143a1.232 1.232 0 0 0 1.22-.058l-.005.003c.359-.223.594-.615.594-1.062V16.466l7.699-13.859c.1-.175.159-.385.159-.609 0-.233-.064-.452-.176-.638l.003.006zM21.051 15.535a1.235 1.235 0 0 0-.158.607v5.834l-3.785-1.893V16.141c0-.223-.058-.432-.16-.613l.003.006-6.826-12.285h17.751zm-6.837 7.215c-.69 0-1.25.56-1.25 1.25v3.977l-2.928-1.463V23.213c0-.222-.058-.431-.16-.612l.003.006L4.124 12.25h5.32a1.25 1.25 0 0 0 0-2.5H2a1.25 1.25 0 0 0-1.09 1.864l-.003-.006L7.536 23.54v3.746c0 .486.278.907.683 1.114l.007.003 5.428 2.715a1.238 1.238 0 0 0 1.222-.058l-.005.003c.358-.223.593-.615.593-1.062V24c0-.69-.56-1.25-1.25-1.25z"/>
                            </svg>

                            <NavDropdownArrow/>
                        </button>
                        <span v-if="filtersCounter" class="absolute shadow-sm left-12 -top-3 rounded-full text-sm text-white px-2 bg-red-800">{{ filtersCounter }}</span>

                        <div v-if="isFiltersOpen"
                             class="absolute w-70 flex flex-col items-center justify-center gap-2 bg-gray-100 shadow-lg shadow-gray-500 top-10 right-5/12 border rounded-lg p-2 z-40">
                            <p class="italic text-center text-sm">Дополнительные фильтры:</p>
                            <div class="flex gap-2">
                                <button type="button" @click="form.machine = ''">
                                    <svg class="w-5 h-5 opacity-50 hover:opacity-80 rotate-180"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 -5 32 32">
                                        <path fill-rule="evenodd"
                                              d="M22.7 7.3a1 1 0 0 0-1.4 0L19 9.6l-2.3-2.3a1 1 0 1 0-1.4 1.5l2.3 2.2-2.3 2.3a1 1 0 0 0 0 1.4c.4.4 1 .4 1.4 0l2.3-2.3 2.2 2.3a1 1 0 1 0 1.4-1.5L20.4 11l2.3-2.3c.4-.4.4-1 0-1.4ZM30 18a2 2 0 0 1-2 2H10.5l-8.2-9 8.1-9H28a2 2 0 0 1 2 2v14ZM28 0H10a1 1 0 0 0-.7.3l-9 10a1 1 0 0 0-.3.7c0 .3 0 .5.3.8l9 9.9c.2.2.4.3.7.3h18a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4Z"/>
                                    </svg>
                                </button>
                                <TextInput
                                    id="machine"
                                    class="relative h-6 block placeholder-gray-500 italic text-center text-sm"
                                    v-model="form.machine"
                                    autocomplete="off"
                                    placeholder="машина"
                                    title="Поиск по машине"
                                    list="machines"
                                />
                                <InputError class="mt-2" :message="form.errors.machine"/>
                            </div>

                            <div class="flex gap-2">
                                <button type="button" @click="form.worker = ''"
                                        :disabled="isWorkerFieldDisabled">
                                    <svg class="w-5 h-5 opacity-50 rotate-180"
                                         :class="{'hover:opacity-80' : !isWorkerFieldDisabled}"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 -5 32 32">
                                        <path fill-rule="evenodd"
                                              d="M22.7 7.3a1 1 0 0 0-1.4 0L19 9.6l-2.3-2.3a1 1 0 1 0-1.4 1.5l2.3 2.2-2.3 2.3a1 1 0 0 0 0 1.4c.4.4 1 .4 1.4 0l2.3-2.3 2.2 2.3a1 1 0 1 0 1.4-1.5L20.4 11l2.3-2.3c.4-.4.4-1 0-1.4ZM30 18a2 2 0 0 1-2 2H10.5l-8.2-9 8.1-9H28a2 2 0 0 1 2 2v14ZM28 0H10a1 1 0 0 0-.7.3l-9 10a1 1 0 0 0-.3.7c0 .3 0 .5.3.8l9 9.9c.2.2.4.3.7.3h18a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4Z"/>
                                    </svg>
                                </button>
                                <TextInput
                                    id="worker"
                                    class="h-6 block placeholder-gray-500 italic text-center text-sm"
                                    :class="{'bg-gray-200' : isWorkerFieldDisabled}"
                                    v-model="form.worker"
                                    autocomplete="off"
                                    placeholder="работник"
                                    title="Поиск по работнику"
                                    :disabled="isWorkerFieldDisabled"
                                    list="workers"

                                />
                                <InputError class="mt-2" :message="form.errors.worker"/>
                            </div>
                            <div v-if="isManagerCanSee() && $page.props.ziggy.location != route('recycling.allWorks') &&
                                                    $page.props.ziggy.location != route('recycling.worksReport') &&
                                                    $page.props.ziggy.location != route('recycling.workout')"
                                 class="flex gap-2 ">
                                <button type="button" @click="form.tkn = ''"
                                        :disabled="isTknFieldDisabled">
                                    <svg class="w-5 h-5 opacity-50 rotate-180"
                                         :class="{'hover:opacity-80' : !isTknFieldDisabled}"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 -5 32 32">
                                        <path fill-rule="evenodd"
                                              d="M22.7 7.3a1 1 0 0 0-1.4 0L19 9.6l-2.3-2.3a1 1 0 1 0-1.4 1.5l2.3 2.2-2.3 2.3a1 1 0 0 0 0 1.4c.4.4 1 .4 1.4 0l2.3-2.3 2.2 2.3a1 1 0 1 0 1.4-1.5L20.4 11l2.3-2.3c.4-.4.4-1 0-1.4ZM30 18a2 2 0 0 1-2 2H10.5l-8.2-9 8.1-9H28a2 2 0 0 1 2 2v14ZM28 0H10a1 1 0 0 0-.7.3l-9 10a1 1 0 0 0-.3.7c0 .3 0 .5.3.8l9 9.9c.2.2.4.3.7.3h18a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4Z"/>
                                    </svg>
                                </button>
                                <TextInput
                                    id="tkn"
                                    class="h-6 block placeholder-gray-500 italic text-center text-sm"
                                    :class="{'bg-gray-200' : isTknFieldDisabled}"
                                    v-model="form.tkn"
                                    @input="updateAndSatisfyTkn"
                                    placeholder="карта"
                                    :disabled="isTknFieldDisabled"
                                    title="Поиск по карте"
                                    autocomplete="off"
                                />
                                <InputError class="mt-2" :message="form.errors.tkn"/>
                            </div>

                            <label v-if="$page.props.ziggy.location == route('printing.allWorks') ||
                                $page.props.ziggy.location == route('printing.worksReport') ||
                                $page.props.ziggy.location == route('lamination.allWorks') ||
                                $page.props.ziggy.location == route('lamination.worksReport')"
                                   class="flex justify-center items-center w-full">
                                <Checkbox v-model:checked="form.not_completed"/>
                                <span class="text-sm ml-4 text-gray-600">Только не закрытые карты</span>
                            </label>


                            <div v-if="isManagerCanSee() && $page.props.ziggy.location == route('recycling.allWorks') ||
                                                    $page.props.ziggy.location == route('recycling.worksReport') ||
                                                    $page.props.ziggy.location == route('recycling.workout')"
                                 class="flex justify-center items-center gap-2 ">
                                <button type="button" @click="form.nomenclature = ''"
                                        :disabled="isTknFieldDisabled">
                                    <svg class="w-5 h-5 opacity-50 rotate-180"
                                         :class="{'hover:opacity-80' : !isTknFieldDisabled}"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 -5 32 32">
                                        <path fill-rule="evenodd"
                                              d="M22.7 7.3a1 1 0 0 0-1.4 0L19 9.6l-2.3-2.3a1 1 0 1 0-1.4 1.5l2.3 2.2-2.3 2.3a1 1 0 0 0 0 1.4c.4.4 1 .4 1.4 0l2.3-2.3 2.2 2.3a1 1 0 1 0 1.4-1.5L20.4 11l2.3-2.3c.4-.4.4-1 0-1.4ZM30 18a2 2 0 0 1-2 2H10.5l-8.2-9 8.1-9H28a2 2 0 0 1 2 2v14ZM28 0H10a1 1 0 0 0-.7.3l-9 10a1 1 0 0 0-.3.7c0 .3 0 .5.3.8l9 9.9c.2.2.4.3.7.3h18a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4Z"/>
                                    </svg>
                                </button>
                                <TextInput
                                    id="tkn"
                                    class="h-6 block placeholder-gray-500 italic text-center text-sm"
                                    :class="{'bg-gray-200' : isTknFieldDisabled}"
                                    v-model="form.nomenclature"
                                    placeholder="номенклатура"
                                    :disabled="isTknFieldDisabled"
                                    title="Поиск по номенклатуре"
                                    autocomplete="off"
                                    list="nomenclatures"
                                />
                                <InputError class="mt-2" :message="form.errors.nomenclature"/>
                            </div>

                            <div v-if="$page.props.ziggy.location == route('extrusion.allWorks')"
                                 class="flex items-center gap-2">
                                <button type="button" @click="form.part_number = ''"
                                        :disabled="isTknFieldDisabled">
                                    <svg class="w-5 h-5 opacity-50 rotate-180"
                                         :class="{'hover:opacity-80' : !isTknFieldDisabled}"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 -5 32 32">
                                        <path fill-rule="evenodd"
                                              d="M22.7 7.3a1 1 0 0 0-1.4 0L19 9.6l-2.3-2.3a1 1 0 1 0-1.4 1.5l2.3 2.2-2.3 2.3a1 1 0 0 0 0 1.4c.4.4 1 .4 1.4 0l2.3-2.3 2.2 2.3a1 1 0 1 0 1.4-1.5L20.4 11l2.3-2.3c.4-.4.4-1 0-1.4ZM30 18a2 2 0 0 1-2 2H10.5l-8.2-9 8.1-9H28a2 2 0 0 1 2 2v14ZM28 0H10a1 1 0 0 0-.7.3l-9 10a1 1 0 0 0-.3.7c0 .3 0 .5.3.8l9 9.9c.2.2.4.3.7.3h18a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4Z"/>
                                    </svg>
                                </button>
                                <TextInput
                                    id="tkn"
                                    class="h-6 block placeholder-gray-500 italic text-center text-sm"
                                    :class="{'bg-gray-200' : isTknFieldDisabled}"
                                    v-model="form.part_number"
                                    placeholder="партия"
                                    :disabled="isTknFieldDisabled"
                                    title="Поиск по партии"
                                    autocomplete="off"
                                />
                                <InputError class="mt-2" :message="form.errors.part_number"/>
                            </div>
                            <label v-if="$page.props.ziggy.location == route('welding.allWorks') ||
                                $page.props.ziggy.location == route('welding.worksReport')"
                                   class="flex items-center ml-6">
                                <Checkbox v-model:checked="form.is_viket"/>
                                <span class="text-sm ml-4 text-gray-600">только викет-машины</span>
                            </label>
                            <label v-if="$page.props.ziggy.location == route('welding.allWorks') ||
                                $page.props.ziggy.location == route('welding.worksReport')"
                                   class="flex items-center ml-6">
                                <Checkbox v-model:checked="form.is_otk"/>
                                <span class="text-sm ml-4 text-gray-600">с выработкой на ОТК</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <ExportToExcelButton v-if="isManagerCanSee()" class="ml-10" @click="exportToExcel()"
                                         :is-disabled="isBlocking"/>


                    <div class="flex gap-6 ml-10">

                        <PrimaryButton @click="submit" type="button">Сформировать</PrimaryButton>
                        <button @click="formReset()" type="button"
                                class="border px-2 uppercase font-semibold rounded-md text-xs hover:bg-gray-100 active:bg-gray-200 transition ease-in-out duration-15">
                            Очистить
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isConverting">
            <div
                class="absolute top-56 w-1/3 left-[35%] h-20 z-30 p-2 bg-gray-600/50 text-white rounded-lg flex justify-center align-center shadow-sm">
                <p class="m-auto animate-pulse">Подождите, идет выгрузка...</p>
            </div>
        </div>

        <datalist id="machines">
            <option v-for="machine in machines" :value="machine"></option>
        </datalist>
        <datalist id="workers">
            <option v-for="worker in workers" :value="worker"></option>
        </datalist>
        <datalist id="nomenclatures">
            <option v-for="nomenclature in nomenclatures" :value="nomenclature"></option>
        </datalist>
    </form>

</template>


<style>
.dp-custom-input-date:focus {
    border-color: rgba(253, 9, 9, 0.3);
    --tw-ring-color: rgb(245, 6, 6, 0.3);
}

.dp__theme_light {
    --dp-primary-color: rgba(248, 7, 7, 0.3);
    --dp-primary-text-color: black;
    --dp-hover-color: #ffeeee;
    --tw-ring-color: rgba(248, 7, 7, 0.3);
}
</style>
