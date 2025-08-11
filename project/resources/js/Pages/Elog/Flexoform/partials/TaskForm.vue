<script setup xmlns="http://www.w3.org/1999/html">
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import TextareaInput from '@/components/TextareaInput.vue';
import InputError from '@/components/InputError.vue';
import {useForm} from "@inertiajs/vue3";
import {computed, ref, watch} from "vue";
import PrimaryButton from '@/components/PrimaryButton.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {calculateMinDatepickerDate} from '@/common/dateTimeHelper.js'
import {useTknUpdater} from "@/common/useTknUpdater.js";


import {showNotification} from '@/common/notifications.js';
import 'vue-toast-notification/dist/theme-sugar.css';
import RollLiteralUp from "@/components/icons/RollLiteralUp.vue";
import RollLiteralDown from "@/components/icons/RollLiteralDown.vue";
import RollLiteralLeft from "@/components/icons/RollLiteralLeft.vue";
import RollLiteralRight from "@/components/icons/RollLiteralRight.vue";
import NumberInput from "@/components/NumberInput.vue";
import {getTknDataFromDb} from "@/common/getTknDataFromDb.js";
import {calculateWorkFacts, goBack} from "@/common/elogTaskHelper.js";
import SelectInput from "@/components/SelectInput.vue";

const props = defineProps({
    auth: Object,
    flexoformTask: {
        type: Object,
        require: false,
        default: null
    },
    workers: {
        required: true
    },
    print_machines: {
        required: true
    },
    flexoforms_machines: {
        required: true
    },
    sticky_tape: {
        required: true
    },
});

defineEmits(['submit']);

const form = useForm({
    work_date: props.flexoformTask ? props.flexoformTask.work_date : new Date(),
    work_shift: '',
    machine_id: props.flexoformTask ? props.flexoformTask.machine_id : '',
    print_machine_id: props.flexoformTask ? props.flexoformTask.print_machine_id : '',
    master_id: props.flexoformTask ? props.flexoformTask.master_id : '',
    collector_id: props.flexoformTask ? props.flexoformTask.collector_id : '',
    tkn: props.flexoformTask ? props.flexoformTask.tkn : '',
    customer: props.flexoformTask ? props.flexoformTask.customer : '',
    print_title: props.flexoformTask ? props.flexoformTask.print_title : '',
    work_start: props.flexoformTask ? props.flexoformTask.work_start : '',
    work_finish: props.flexoformTask ? props.flexoformTask.work_finish : '',
    work_fact: props.flexoformTask ? props.flexoformTask.work_fact : null,

    design_number: props.flexoformTask ? props.flexoformTask.design_number : '',
    streams: props.flexoformTask ? props.flexoformTask.streams : null,
    sleeves_diameter: props.flexoformTask ? props.flexoformTask.sleeves_diameter : null,
    sleeves_fact: props.flexoformTask ? props.flexoformTask.sleeves_fact : null,
    paints_count: props.flexoformTask ? props.flexoformTask.paints_count : null,
    new_forms: props.flexoformTask ? props.flexoformTask.new_forms : '',
    aniloks: props.flexoformTask ? props.flexoformTask.aniloks : null,

    paints_and_sticky: props.flexoformTask ? props.flexoformTask.paints_and_sticky : [
        {paints: '', sticky_brand: '', sticky_name: '', sticky_thickness: null}
    ],

    unwind_direction: props.flexoformTask ? props.flexoformTask.unwind_direction : 'flow',
    is_streams_distance_ok: props.flexoformTask ? props.flexoformTask.is_streams_distance_ok : 0,
    is_mounting_dots_ok: props.flexoformTask ? props.flexoformTask.is_mounting_dots_ok : 0,

    notes: props.flexoformTask ? props.flexoformTask.notes : '',

});

const paintsMinRows = 1;
const paintsMaxRows = 10;
const { updateAndSatisfyTkn } = useTknUpdater(form);

const getStickyNames = (brand) => {
    return computed(() => {
        if (props.sticky_tape[brand]) {
            return Object.keys(props.sticky_tape[brand]);
        }
        return [];
    });
};

function findStickyThickness(brand, name, index) {
    return form.paints_and_sticky[index].sticky_thickness = props.sticky_tape[brand][name];
}

const submit = () => {
    // если изменяем задачу
    if (props.flexoformTask) {
        form.patch((`/flexoform/update/${props.flexoformTask.id}`), {
            onSuccess: () => {
                showNotification('success', 'Задача успешно изменена!')
            }
        })
        // или если создаем новую
    } else {
        form.post(route('flexoform.store'), {
            onSuccess: () => {
                form.reset();
                showNotification('success', 'Задача успешно создана!')
            }
        })
    }
}

// задаем номер смены по умолчанию исходя из текущего часа
function setWorkShiftBasedOnTime(form) {
    if (!props.flexoformTask) {
        const currentHour = new Date().getHours();
        currentHour >= 8 && currentHour < 20 ? form.work_shift = 1 : form.work_shift = 2;
        return
    }
    form.work_shift = props.flexoformTask.work_shift
}

setWorkShiftBasedOnTime(form);

const addPaintsStickyRow = () => {
    form.paints_and_sticky.push({paints: '', sticky_brand: '', sticky_name: '', sticky_thickness: null});
};

const removePaintsStickyRow = (index) => {
    form.paints_and_sticky.splice(index, 1);
};


// отслеживание изменений времени начала и окончания работ
watch(
    [() => form.work_start, () => form.work_finish],
    ([newStart, newFinish], [oldStart, oldFinish]) => {
        // изменились ли значения времени начала и окончания
        if (newStart !== oldStart || newFinish !== oldFinish) {
            form.work_fact = calculateWorkFacts(newStart, newFinish)
        }
    }
);


// отслеживание изменений поля tkn и обращение к DB
watch(
    () => form.tkn,
    (newNumber, oldNumber) => {
        if (form.tkn !== '') {
            getTknDataFromDb(newNumber, oldNumber, (data) => {
                form.customer = data.customer;
                form.print_title = data.print_title;
                form.sleeves_diameter = data.sleeves_diameter;
                form.design_number = data.design_number;
                form.streams = data.streams;
                form.paints_count = data.colors;
            });
        }
    }
);
</script>

<template>
    <div class="h-full gradient">
        <h1 class="ml-36 font-semibold text-md flex text-gray-600">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                <path fill="#989898" fill-rule="evenodd"
                      d="M31.4 4 27.9.7a2 2 0 0 0-2.8 0L21.6 4h2.9l1.3-1.3a1 1 0 0 1 1.4 0l2 2c.5.5.5 1.1 0 1.5L28 7.5v2.8l3.4-3.4c.8-.8.8-2 0-2.8ZM11 17.5l3.5 3.6L25.8 9.7l-3.6-3.5L11 17.4Zm-4.3 7.2c-.2.4.2.9.7.7l5.4-3.3-2.8-2.8-3.3 5.4Zm8.2-1.7-8.7 4.3c-1 .4-1.8-.4-1.4-1.4L9.1 17c0-.4.2-.8.5-1L21.6 4H2.8A2.8 2.8 0 0 0 0 6.8v22.4C0 30.7 1.3 32 2.8 32h22.4c1.5 0 2.8-1.3 2.8-2.8V10.3l-12 12a2 2 0 0 1-1 .6ZM27.2 8.3l.8-.8v-.7C28 5.3 26.7 4 25.2 4h-.7l-.8.7 3.5 3.6Z"/>
            </svg>
            <span class="ml-6">{{ flexoformTask ? 'Изменение задания ' : 'Новое задание ' }} монтажа флексоформ</span>
        </h1>

        <form @submit.prevent="submit">
            <article class="grid grid xl:grid-cols-2 w-10/12 m-auto px-4 gap-8 mt-5">
                <fieldset class="w-11/12 flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                    <legend class=" mt-2 w-full text-center font-semibold text-md mb-6">✎ График работ</legend>

                    <div class="flex gap-4 mb-5">

                        <div class="flex flex-col items-center m-auto">
                            <input-label for="invoice">Дата начала смены*</input-label>
                            <VueDatePicker v-model="form.work_date" day-picker :format="'dd.MM.yyyy'" locale="ru"
                                           text-input :enable-time-picker="false"
                                           :min-date="!props.auth.permissions.includes('full_upff_permission') ? calculateMinDatepickerDate() : null"
                                           auto-apply/>
                            <InputError class="mt-2" :message="form.errors.work_date"/>
                        </div>

                        <div class="flex flex-col items-center m-auto">
                            <input-label>Смена*</input-label>
                            <div class="flex gap-6 border border-white rounded-sm p-2">
                                <label class="radio-label text-sm">
                                    <input type="radio" class="input-radio green" v-model="form.work_shift" value="1">
                                    с 8 до 20
                                </label>
                                <label class="radio-label text-sm">
                                    <input type="radio" class="input-radio green" v-model="form.work_shift" value="2">
                                    с 20 до 8
                                </label>
                            </div>
                            <InputError class="mt-2" :message="form.errors.work_shift"/>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="machine_id">Монтажное оборудование*</input-label>
                        <SelectInput :options="flexoforms_machines" v-model="form.machine_id" id="machine_id"
                                     class="h-8 w-full flex pt-0.5 rounded-lg pr-0.5" required
                                     :placeholder-option="'выберите монтажное оборудование'"
                        />
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="master_id">Мастер смены*</input-label>
                        <TextInput
                            id="master_id"
                            class="h-8 block w-full"
                            v-model="form.master_id"
                            autocomplete="off"
                            list="workers"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.master_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="collector_id">Сборщик*</input-label>
                        <TextInput
                            id="collector_id"
                            class="h-8 block w-full"
                            v-model="form.collector_id"
                            autocomplete="off"
                            list="workers"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.collector_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="tkn">Номер ТКН*</input-label>
                        <TextInput
                            id="tkn"
                            class="h-8 block w-full"
                            v-model="form.tkn"
                            @input="updateAndSatisfyTkn"
                            autocomplete="off"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.tkn"/>
                    </div>


                    <div class="flex items-center">
                        <input-label class="w-1/2" for="customer">Заказчик*</input-label>
                        <TextInput
                            id="customer"
                            class="h-8 block w-full"
                            v-model="form.customer"
                            autocomplete="off"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.customer"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="print_title">Заказ*</input-label>
                        <TextInput
                            id="print_title"
                            class="h-8 block w-full"
                            v-model="form.print_title"
                            autocomplete="off"
                            required

                        />
                        <InputError class="mt-2" :message="form.errors.print_title"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="work_start">Начало работ*</input-label>
                        <VueDatePicker v-model="form.work_start" :format="'dd.MM.yyyy  HH:mm'" locale="ru" text-input
                                       id="work_start" required time-picker-inline auto-apply/>
                        <InputError class="mt-2" :message="form.errors.work_start"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="work_finish">Окончание работ*</input-label>
                        <VueDatePicker v-model="form.work_finish" :format="'dd.MM.yyyy  HH:mm'" locale="ru" text-input
                                       id="work_finish" required time-picker-inline auto-apply/>
                        <InputError class="mt-2" :message="form.errors.work_finish"/>
                    </div>

                    <div class="flex gap-1 items-center">
                        <input-label class="w-2/4 " for="work_fact">Фактическое время</input-label>
                        <NumberInput
                            id="work_fact"
                            step="0.01"
                            class="h-8 block w-full bg-gray-200"
                            v-model.number="form.work_fact"
                            disabled
                        />
                        <InputError class="mt-2" :message="form.errors.work_fact"/>
                    </div>
                </fieldset>

                <fieldset class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white relative">
                    <legend class="mt-2 w-full text-center font-semibold text-md mb-6">🧻 Печатная форма</legend>

                    <div class="w-full gap-4 grid grid-cols-3">
                        <div class="flex flex-col">
                            <input-label class="w-4/5" for="design_number">Номер дизайна</input-label>
                            <TextInput
                                id="design_number"
                                class="h-6 block w-full text-sm"
                                v-model="form.design_number"
                                title="Номер дизайна"
                                autocomplete="off"
                            />
                            <InputError class="mt-2" :message="form.errors.design_number"/>
                        </div>
                        <div class="flex flex-col">
                            <input-label class="w-4/5" for="paints_count">Количество красок</input-label>
                            <NumberInput
                                id="paints_count"
                                class="h-6 block w-full text-sm bg-gray-200"
                                v-model.number="form.paints_count"
                                title="Количество красок"
                                disabled
                            />
                            <InputError class="mt-2" :message="form.errors.paints_count"/>
                        </div>
                        <div class="flex flex-col">
                            <input-label class="w-4/5" for="streams">Количество ручьев</input-label>
                            <NumberInput
                                id="streams"
                                class="h-6 block w-full text-sm bg-gray-200"
                                v-model.number="form.streams"
                                title="Количество ручьев"
                                disabled
                            />
                            <InputError class="mt-2" :message="form.errors.streams"/>
                        </div>
                    </div>

                    <div class="w-full gap-4 grid grid-cols-3">
                        <div class="flex flex-col">
                            <input-label class="w-4/5" for="sleeves_diameter">Диаметр сливс*</input-label>
                            <NumberInput
                                id="sleeves_diameter"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.sleeves_diameter"
                                title="Сливс диаметр"
                                min="1"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.sleeves_diameter"/>
                        </div>
                        <div class="flex flex-col">
                            <input-label class="w-4/5" for="sleeves_fact">Количество сливс, факт*</input-label>
                            <NumberInput
                                id="sleeves_fact"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.sleeves_fact"
                                title="Сливс, факт"
                                autocomplete="off"
                                min="0"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.sleeves_fact"/>
                        </div>
                        <div class="flex flex-col">
                            <input-label class="w-4/5" for="new_forms">Новые формы</input-label>
                            <TextInput
                                id="new_forms"
                                class="h-6 block w-full text-sm"
                                v-model="form.new_forms"
                                title="Новые формы"
                                autocomplete="off"
                            />
                            <InputError class="mt-2" :message="form.errors.new_forms"/>
                        </div>
                    </div>

                    <div class="flex flex-col m-auto">
                        <input-label for="aniloks">Чистка анилоксовых валов, шт</input-label>
                        <NumberInput
                            id="aniloks"
                            class="h-6 block w-full text-sm"
                            v-model.number="form.aniloks"
                            title="Чистка анилоксовых валов"
                            min="0"
                        />
                        <InputError class="mt-2" :message="form.errors.aniloks"/>
                    </div>

                    <div class="flex flex-col items-start mb-5 mt-3 p-2 border rounded-lg shadow-sm">
                        <p class="flex gap-2 items-center m-auto text-gray-600 mb-2">Краски и липкая лента
                            <button v-if="form.paints_and_sticky.length <= paintsMaxRows" @click="addPaintsStickyRow"
                                    type="button" class="opacity-50 hover:opacity-100 transition duration-150">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="#5e5e5e" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M6 12h12m-6-6v12"/>
                                </svg>
                            </button>
                        </p>
                        <div v-for="(item, index) in form.paints_and_sticky" :key="index"
                             class="grid grid-cols-12 gap-2 p-0.5">
                            <div class="flex justify-center items-center">
                                <button v-if="form.paints_and_sticky.length > paintsMinRows"
                                        @click="removePaintsStickyRow(index)"
                                        class=" opacity-50 hover:opacity-100 transition duration-150" type="button">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                           stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M6 12L18 12" stroke="#757575" stroke-width="2"
                                                  stroke-linecap="round" stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                </button>
                            </div>

                            <div class="col-span-3">
                                <TextInput
                                    id="paints"
                                    class="h-6 w-full block text-sm"
                                    v-model="form.paints_and_sticky[index].paints"
                                    title="Краски"
                                    placeholder="краски"
                                    autocomplete="off"
                                    required
                                    list="paint-names"
                                />
                                <InputError class="mt-2" :message="form.errors.paints_and_sticky"/>
                            </div>

                            <div class="col-span-3">
                                <select
                                    id="sticky"
                                    class="h-6 w-full text-sm flex py-0.5 rounded-lg border-gray-300 focus:border-orange-600 focus:ring-orange-600"
                                    v-model.lazy="form.paints_and_sticky[index].sticky_brand"
                                    title="Липкая лента, производитель"
                                    autocomplete="off"
                                    required
                                >
                                    <option v-for="(key, brand) in sticky_tape" :key="key" :value="brand">
                                        {{ brand }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.paints_and_sticky"/>
                            </div>

                            <div class="col-span-3">
                                <select
                                    id="sticky"
                                    class="h-6 text-sm w-full flex px-2 py-0.5 rounded-lg border-gray-300 focus:border-orange-600 focus:ring-orange-600"
                                    v-model.lazy="form.paints_and_sticky[index].sticky_name"
                                    autocomplete="off"
                                    required
                                    title="Липкая лента, марка"
                                    @update:model-value=findStickyThickness(item.sticky_brand,item.sticky_name,index)
                                >
                                    <option v-for="name in getStickyNames(item.sticky_brand).value"
                                            :key="name" :value="name" class="text-xs w-full">{{ name }}
                                    </option>
                                </select>

                                <InputError class="mt-2" :message="form.errors.paints_and_sticky"/>
                            </div>
                            <div class="col-span-2">
                                <NumberInput
                                    id="sticky"
                                    class="h-6 w-full text-sm bg-gray-200"
                                    v-model.number="form.paints_and_sticky[index].sticky_thickness"
                                    title="Липкая лента, толщина"
                                    disabled
                                />
                                <InputError class="mt-2" :message="form.errors.paints_and_sticky"/>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between gap-6 py-2">
                        <div class="flex items-center">
                            <input-label class="w-1/2" for="print_machine_id">Печатная машина*</input-label>
                            <SelectInput :options="print_machines" v-model="form.print_machine_id" id="print_machine_id"
                                         class="h-8 w-full flex py-0.5 rounded-lg text-sm"
                                         :placeholder-option="'выберите машину'"
                            />
                            <InputError class="mt-2" :message="form.errors.print_machine_id"/>
                        </div>
                        <div class="flex items-center gap-2 m-auto ">
                            <!-- Рулон с направлением печати по потоку размотки -->
                            <label class="radio-label cursor-pointer">
                                <input type="radio" class="hidden" v-model="form.unwind_direction" value="flow">
                                <roll-literal-down class="w-14 select-none"
                                                   :class="{'fill-amber-600' : form.unwind_direction === 'flow'}"/>
                            </label>
                            <!-- Рулон с направлением печати против потока размотки -->
                            <label class="radio-label cursor-pointer">
                                <input type="radio" class="hidden" v-model="form.unwind_direction" value="against_flow">
                                <roll-literal-up class="w-14"
                                                 :class="{'fill-amber-600' : form.unwind_direction === 'against_flow'}"/>
                            </label>
                            <!-- Рулон с направлением печати по потоку слева-->
                            <label class="radio-label cursor-pointer">
                                <input type="radio" class="hidden" v-model="form.unwind_direction" value="flow_left">
                                <roll-literal-left class="w-14"
                                                   :class="{'fill-amber-600' : form.unwind_direction === 'flow_left'}"/>
                            </label>
                            <!-- Рулон с направлением печати по потоку справа-->
                            <label class="radio-label cursor-pointer">
                                <input type="radio" class="hidden" v-model="form.unwind_direction" value="flow_right">
                                <roll-literal-right class="w-14"
                                                    :class="{'fill-amber-600' : form.unwind_direction === 'flow_right'}"/>
                            </label>
                        </div>
                    </div>


                    <div class="flex gap-20">
                        <div class="flex items-center">
                            <input-label class="w-3/4" for="streams_ok">Расстояние между ручьями проверено</input-label>
                            <div class="flex gap-2">
                                <label class="radio-label text-sm text-gray-600">
                                    <input type="radio" class="input-radio green"
                                           v-model="form.is_streams_distance_ok" value="1">
                                    Да
                                </label>
                                <label class="radio-label text-sm text-gray-600">
                                    <input type="radio" class="input-radio red"
                                           v-model="form.is_streams_distance_ok" value="0">
                                    Нет
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input-label class="w-3/4" for="streams_ok">Проверка монтажных точек проведена</input-label>
                            <div class="flex gap-2">
                                <label class="radio-label text-sm text-gray-600">
                                    <input type="radio" class="input-radio green"
                                           v-model="form.is_mounting_dots_ok" value="1">
                                    Да
                                </label>
                                <label class="radio-label text-sm text-gray-600">
                                    <input type="radio" class="input-radio red"
                                           v-model="form.is_mounting_dots_ok" value="0">
                                    Нет
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center w-10/12 m-auto">
                        <input-label for="notes">Примечание</input-label>
                        <TextareaInput
                            id="notes"
                            class="h-20 block w-full text-sm"
                            autocomplete="off"
                            v-model.trim="form.notes"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.notes"/>


                    <div class="buttons">
                        <SecondaryButton @click="goBack" class="mr-10">Назад</SecondaryButton>
                        <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>
                    </div>

                </fieldset>
            </article>
        </form>
    </div>

    <datalist id="workers">
        <option v-for="worker in workers" :value="worker"></option>
    </datalist>

    <datalist id="flexoforms_machines">
        <option v-for="machine in flexoforms_machines" :value="machine"></option>
    </datalist>

    <datalist id="paint-names">
        <option value="CMYK">CMYK</option>
        <option value="C">C</option>
        <option value="M">M</option>
        <option value="Y">Y</option>
        <option value="K">K</option>
        <option value="420">420</option>
        <option value="421">421</option>
        <option value="Lak">Lak</option>
    </datalist>

</template>

<style scoped>

.buttons {
    position: relative;
    padding: 25px 0;
    margin: 0 auto;
}

.dp__theme_light {
    --dp-disabled-color: rgb(229 231 235);
}

.gradient {
    background: linear-gradient(to left bottom, rgba(57, 70, 119, 0.15), rgba(102, 96, 128, 0) 90%);
}

.radio-label {
    cursor: pointer;
}

.input-radio {
    box-shadow: 0 0 0 1px #6d6d6d;
    font-size: 3em;
    width: 18px;
    height: 18px;
    margin-right: 5px;

    border: 2px solid #ffffff;
    background-clip: border-box;
    border-radius: 50%;
    appearance: none;
    transition: background-color 0.3s, box-shadow 0.3s;

}

.card .form label {
    display: flex;
    margin: 10px 15px;
}

.input-radio.green:checked {
    box-shadow: 0 0 0 2px rgb(49, 136, 37);
    background-color: rgb(49, 136, 37);
}

.input-radio.red:checked {
    box-shadow: 0 0 0 2px rgb(201, 7, 7);
    background-color: rgb(201, 7, 7);
}

svg {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

</style>
