<script setup>
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import TextareaInput from '@/components/TextareaInput.vue';
import InputError from '@/components/InputError.vue';
import Checkbox from '@/components/Checkbox.vue';
import {useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import PrimaryButton from '@/components/PrimaryButton.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {calculateMinDatepickerDate} from '@/common/dateTimeHelper.js'


import {showNotification} from '@/common/notifications.js';
import axios from "axios";
import SadSmile from "@/components/icons/SadSmile.vue";
import {getTknDataFromDb} from "@/common/getTknDataFromDb.js";
import {calculateWorkFacts, goBack} from "@/common/elogTaskHelper.js";
import NumberInput from "@/components/NumberInput.vue";
import SelectInput from "@/components/SelectInput.vue";
import {useTknUpdater} from "@/common/useTknUpdater.js";

const props = defineProps({
    auth: Object,
    weldingTask: {
        type: Object,
        require: false,
        default: null
    },
    workers: {
        required: true
    },
    machines: {
        required: true
    },
    materials: {
        required: true
    },
    productTypes: {
        required: true
    },
    bottomTypes: {
        required: true
    },
    boxesTypes: {
        required: true
    },
});

defineEmits(['submit']);

const form = useForm({
    machine_id: props.weldingTask ? props.weldingTask.machine_id : '',
    work_date: props.weldingTask ? props.weldingTask.work_date : new Date(),
    work_shift: '',
    master_id: props.weldingTask ? props.weldingTask.master_id : '',
    operator1_id: props.weldingTask ? props.weldingTask.operator1_id : '',
    operator2_id: props.weldingTask ? props.weldingTask.operator2_id : '',
    operator3_id: props.weldingTask ? props.weldingTask.operator3_id : '',
    operator4_id: props.weldingTask ? props.weldingTask.operator4_id : '',
    operator5_id: props.weldingTask ? props.weldingTask.operator5_id : '',
    student1_id: props.weldingTask ? props.weldingTask.student1_id : '',
    student2_id: props.weldingTask ? props.weldingTask.student2_id : '',
    acceptor_id: props.weldingTask ? props.weldingTask.acceptor_id : '',
    tkn: props.weldingTask ? props.weldingTask.tkn : '',
    work_plan: props.weldingTask ? props.weldingTask.work_plan : null,
    work_start: props.weldingTask ? props.weldingTask.work_start : '',
    work_finish: props.weldingTask ? props.weldingTask.work_finish : '',
    work_fact: props.weldingTask ? props.weldingTask.work_fact : null,
    customer: props.weldingTask ? props.weldingTask.customer : '',
    print_title: props.weldingTask ? props.weldingTask.print_title : '',
    circulation: props.weldingTask ? props.weldingTask.circulation : null,

    product_type: props.weldingTask ? props.weldingTask.product_type : '',
    mat1_id: props.weldingTask ? props.weldingTask.mat1_id : '',
    mat2_id: props.weldingTask ? props.weldingTask.mat2_id : '',
    mat3_id: props.weldingTask ? props.weldingTask.mat3_id : '',
    product_width: props.weldingTask ? props.weldingTask.product_width : null,
    thickness1: props.weldingTask ? props.weldingTask.thickness1 : null,
    thickness2: props.weldingTask ? props.weldingTask.thickness2 : null,
    thickness3: props.weldingTask ? props.weldingTask.thickness3 : null,
    bottom: props.weldingTask ? props.weldingTask.bottom : null,
    length: props.weldingTask ? props.weldingTask.length : null,
    count_plan: props.weldingTask ? props.weldingTask.count_plan : null,
    count: props.weldingTask ? props.weldingTask.count : null,
    bottom_type: props.weldingTask ? props.weldingTask.bottom_type : '',

    is_pocket: props.weldingTask ? Boolean(props.weldingTask.is_pocket) : false,
    is_handle: props.weldingTask ? Boolean(props.weldingTask.is_handle) : false,
    is_edge: props.weldingTask ? Boolean(props.weldingTask.is_edge) : false,
    is_perforation: props.weldingTask ? Boolean(props.weldingTask.is_perforation) : false,
    is_ziplock: props.weldingTask ? Boolean(props.weldingTask.is_ziplock) : false,

    workout_count: props.weldingTask ? props.weldingTask.workout_count : null,
    workout_otk: props.weldingTask ? props.weldingTask.workout_otk : null,

    waste_plan: props.weldingTask ? props.weldingTask.waste_plan : null,
    waste_print: props.weldingTask ? props.weldingTask.waste_print : null,
    waste_edge: props.weldingTask ? props.weldingTask.waste_edge : null,
    waste_welding: props.weldingTask ? props.weldingTask.waste_welding : null,
    waste_sum: props.weldingTask ? props.weldingTask.waste_sum : null,

    box_size_id: props.weldingTask ? props.weldingTask.box_size_id : '',

    electro: props.weldingTask ? props.weldingTask.electro : null,
    mechanical: props.weldingTask ? props.weldingTask.mechanical : null,
    speed: props.weldingTask ? props.weldingTask.speed : null,
    reconfig: props.weldingTask ? props.weldingTask.reconfig : null,
    calibrating: props.weldingTask ? props.weldingTask.calibrating : null,
    change_teflon: props.weldingTask ? props.weldingTask.change_teflon : null,
    tech_service: props.weldingTask ? props.weldingTask.tech_service : null,
    no_human: props.weldingTask ? props.weldingTask.no_human : null,
    no_work: props.weldingTask ? props.weldingTask.no_work : null,
    no_raw: props.weldingTask ? props.weldingTask.no_raw : null,
    diff_circulation: props.weldingTask ? props.weldingTask.diff_circulation : null,
    notes: props.weldingTask ? props.weldingTask.notes : null,
    prepare_ok: props.weldingTask ? props.weldingTask.prepare_ok : null,
    is_idle: props.weldingTask ? Boolean(props.weldingTask.is_idle) : false,
});

const submit = () => {
    // если изменяем задачу
    if (props.weldingTask) {
        form.patch(route('welding.update', props.weldingTask.id), {
            onSuccess: () => {
                showNotification('success', 'Задача успешно изменена!')
            }
        })
        // или если создаем новую
    } else {
        form.post(route('welding.store'), {
            onSuccess: () => {
                form.reset();
                showNotification('success', 'Задача успешно создана!')
            }
        })
    }
}

const { updateAndSatisfyTkn } = useTknUpdater(form);


// задаем номер смены по умолчанию исходя из текущего часа
function setWorkShiftBasedOnTime(form) {
    if (!props.weldingTask) {
        const currentHour = new Date().getHours();

        if (currentHour >= 7 && currentHour < 19) {
            form.work_shift = 1;
        } else {
            form.work_shift = 2;
        }
    } else {
        form.work_shift = props.weldingTask.work_shift
    }
}

setWorkShiftBasedOnTime(form);

// функция cуммирования отходов
const wasteSum = ref(0);

function calculateWaste() {
    if (form.waste_print || form.waste_edge || form.waste_welding) {
        const calc = (+form.waste_print + +form.waste_edge + +form.waste_welding);
        wasteSum.value = calc.toFixed(2);
        form.waste_sum = +wasteSum.value;
    } else {
        wasteSum.value = 0;
        form.waste_sum = null;
    }
}

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

// отслеживание изменений для суммирования
watch(
    [() => form.waste_print, () => form.waste_edge, () => form.waste_welding],
    ([newPrint, newEdge, newWelding], [oldPrint, oldEdge, oldWelding]) => {
        // изменились ли значения времени начала и окончания
        if (newPrint !== oldPrint || newEdge !== oldEdge || newWelding !== oldWelding) {
            calculateWaste()
        }
    }
);

// разница тиража
const circulation = ref(0);

const calcCurrentCirculation = (tasksCirculation = 0) => {
    if (form.circulation || form.workout_count || form.workout_otk) {
        const calc = (+form.circulation - (+form.workout_count + +form.workout_otk + +tasksCirculation));
        circulation.value = calc.toFixed(2);
        form.diff_circulation = +circulation.value;
    } else {
        circulation.value = 0;
        form.diff_circulation = null;
    }
}

const calcCircilation = async () => {
    try {
        const response = await axios.post('/backend/get-circulations', {
            tkn: form.tkn,
            type: 'welding',
        });
        if (response.data) {
            if (response.data.tasksCount > 0) {
                showNotification('info', `${response.data.taskType}. По карте ${form.tkn} уже создано задач: ` + response.data.tasksCount + `. <br> Общая сумма выработки: ` + response.data.tasksCirculation + ' кг');
            } else {
                showNotification('info', `${response.data.taskType}. По карте ${form.tkn} это первая задача`);
            }
            calcCurrentCirculation(response.data.tasksCirculation);
        } else {
            showNotification('warning', 'Ошибка расчета');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Ошибка расчета разницы тиража');
    }
}


// отслеживание изменений поля tkn и обращение к DB
watch(
    () => form.tkn,
    (newNumber, oldNumber) => {
        if (form.tkn !== '') {
            getTknDataFromDb(newNumber, oldNumber, (data) => {
                form.customer = data.customer;
                form.print_title = data.print_title;
                form.colors = data.colors;
                form.circulation = data.circulation_sht;
                form.product_width = data.product_width;
                form.length = data.length;
                form.bottom = data.bottom;
                form.count_plan = data.circulation_kg;
                form.work_plan = +data.welding_time.toFixed(2);
            });
        }
    }
);

// если вся смена простой - все заблокированные поля очищаем от данных, если они занесены
watch(() => form.is_idle, (newValue, oldValue) => {
    if (newValue) {
        form.operator1_id = form.operator2_id = form.operator3_id = form.operator4_id = form.operator5_id =
            form.student1_id = form.student2_id = form.acceptor_id = form.tkn = form.work_start = form.work_finish =
                form.customer = form.print_title = form.product_type = form.mat1_id = form.mat2_id = form.mat3_id =
                    form.bottom_type = form.box_size_id = '';

        form.is_pocket = form.is_handle = form.is_edge = form.is_perforation = form.is_ziplock = false;

        form.work_plan = form.work_fact = form.circulation = form.product_width = form.thickness1 = form.thickness2 =
            form.thickness3 = form.bottom = form.length = form.count_plan = form.count = form.workout_count =
                form.workout_otk = form.waste_plan = form.waste_print = form.waste_edge = form.waste_welding =
                    form.waste_sum = form.diff_circulation = form.speed = form.prepare_ok = null;

    }
});
</script>

<template>
    <div class="h-full gradient">
        <h1 class="ml-36 font-semibold text-md flex text-gray-600">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                <path fill="#989898" fill-rule="evenodd"
                      d="M31.4 4 27.9.7a2 2 0 0 0-2.8 0L21.6 4h2.9l1.3-1.3a1 1 0 0 1 1.4 0l2 2c.5.5.5 1.1 0 1.5L28 7.5v2.8l3.4-3.4c.8-.8.8-2 0-2.8ZM11 17.5l3.5 3.6L25.8 9.7l-3.6-3.5L11 17.4Zm-4.3 7.2c-.2.4.2.9.7.7l5.4-3.3-2.8-2.8-3.3 5.4Zm8.2-1.7-8.7 4.3c-1 .4-1.8-.4-1.4-1.4L9.1 17c0-.4.2-.8.5-1L21.6 4H2.8A2.8 2.8 0 0 0 0 6.8v22.4C0 30.7 1.3 32 2.8 32h22.4c1.5 0 2.8-1.3 2.8-2.8V10.3l-12 12a2 2 0 0 1-1 .6ZM27.2 8.3l.8-.8v-.7C28 5.3 26.7 4 25.2 4h-.7l-.8.7 3.5 3.6Z"/>
            </svg>
            <span class="ml-2">{{ weldingTask ? 'Изменение задания ' : 'Новое задание ' }} сварки</span>
        </h1>

        <form @submit.prevent="submit">
            <article class="grid grid xl:grid-cols-3 px-4 gap-8 mt-5 mb-10">
                <fieldset class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                    <legend class=" mt-2 w-full text-center font-semibold text-md mb-6">✎ График работ</legend>

                    <div class="flex gap-4">

                        <div class="flex flex-col items-center m-auto">
                            <input-label for="invoice">Дата начала смены*</input-label>
                            <VueDatePicker v-model="form.work_date" day-picker :format="'dd.MM.yyyy'" locale="ru"
                                           text-input :enable-time-picker="false"
                                           :min-date="!props.auth.permissions.includes('full_welding_permission') ? calculateMinDatepickerDate() : null"
                                           auto-apply/>
                            <InputError class="mt-2" :message="form.errors.work_date"/>
                        </div>

                        <div class="flex flex-col items-center m-auto">
                            <input-label>Смена*</input-label>
                            <div class="flex gap-6 border border-white rounded-sm p-2">
                                <label class="radio-label text-sm">
                                    <input type="radio" class="input-radio green" v-model="form.work_shift" value="1">
                                    с 7 до 19
                                </label>
                                <label class="radio-label text-sm">
                                    <input type="radio" class="input-radio green" v-model="form.work_shift" value="2">
                                    с 19 до 7
                                </label>
                            </div>
                            <InputError class="mt-2" :message="form.errors.work_shift"/>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="machine_id">Машина сварки*</input-label>
                        <TextInput
                            id="machine_id"
                            class="h-6 block w-full"
                            v-model="form.machine_id"
                            autocomplete="off"
                            list="machines"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.machine_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="master_id">Мастер смены*</input-label>
                        <TextInput
                            id="master_id"
                            class="h-6 block w-full"
                            v-model="form.master_id"
                            autocomplete="off"
                            list="workers"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.master_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="operator1_id">Оператор&nbsp;1</input-label>
                        <TextInput
                            id="operator1_id"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.operator1_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.operator1_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="operator2_id">Оператор&nbsp;2</input-label>
                        <TextInput
                            id="operator2_id"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.operator2_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.operator2_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="operator3_id">Оператор 3</input-label>
                        <TextInput
                            id="operator3_id"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.operator3_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.operator3_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="operator4_id">Оператор 4</input-label>
                        <TextInput
                            id="operator4_id"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.operator4_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.operator4_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="operator5_id">Оператор 5</input-label>
                        <TextInput
                            id="operator5_id"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.operator5_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.operator5_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="student1_id">Ученик 1</input-label>
                        <TextInput
                            id="student1_id"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.student1_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.student1_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="student2_id">Ученик 2</input-label>
                        <TextInput
                            id="student2_id"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.student2_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.student2_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="acceptor_id">Приемщик</input-label>
                        <TextInput
                            id="acceptor_id"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.acceptor_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.acceptor_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="tkn">Номер ТКН*</input-label>
                        <TextInput
                            id="tkn"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            @input="updateAndSatisfyTkn"
                            v-model="form.tkn"
                            autocomplete="off"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.tkn"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="circulation">План выработки (тираж)*</input-label>
                        <NumberInput
                            id="circulation"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model.number="form.circulation"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.circulation"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="customer">Заказчик*</input-label>
                        <TextInput
                            id="customer"
                            class="h-6 block w-full"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.customer"
                            autocomplete="off"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.customer"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="print_title">Заказ*</input-label>
                        <TextInput
                            id="print_title"
                            class="h-6 block w-full text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.print_title"
                            autocomplete="off"
                            :required="!form.is_idle"

                        />
                        <InputError class="mt-2" :message="form.errors.print_title"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="work_start">Начало работ*</input-label>
                        <VueDatePicker v-model="form.work_start" :format="'dd.MM.yyyy  HH:mm'" locale="ru" text-input
                                       :class="{'bg-gray-200': form.is_idle}" id="work_start"
                                       :disabled=" form.is_idle" :required="!form.is_idle" time-picker-inline
                                       auto-apply/>
                        <InputError class="mt-2" :message="form.errors.work_start"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="work_finish">Окончание работ*</input-label>
                        <VueDatePicker v-model="form.work_finish" :format="'dd.MM.yyyy  HH:mm'" locale="ru" text-input
                                       :class="{'bg-gray-200': form.is_idle}" id="work_finish"
                                       :disabled=" form.is_idle" :required="!form.is_idle" time-picker-inline
                                       auto-apply/>
                        <InputError class="mt-2" :message="form.errors.work_finish"/>
                    </div>

                    <div class="flex gap-6">
                        <div class="flex gap-4 items-center">
                            <input-label class="w-3/4 text-center" for="work_plan">Планируемое время</input-label>
                            <NumberInput
                                id="work_plan"
                                step="0.01"
                                class="h-6 block w-full"
                                :class="{'bg-gray-200': form.is_idle}"
                                :disabled=" form.is_idle"
                                v-model.number="form.work_plan"
                            />
                            <InputError class="mt-2" :message="form.errors.work_plan"/>
                        </div>

                        <div class="flex gap-4 items-center">
                            <input-label class="w-3/4 text-center" for="work_fact">Фактическое время</input-label>
                            <NumberInput
                                id="work_fact"
                                step="0.01"
                                class="h-6 block w-full bg-gray-200"
                                v-model.number="form.work_fact"
                                disabled
                            />
                            <InputError class="mt-2" :message="form.errors.work_fact"/>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white relative">
                    <legend class="mt-2 w-full text-center font-semibold text-md mb-10">🧻 Расход материала</legend>

                    <div class="flex gap-2 items-center pb-6">
                        <input-label class="w-1/3" for="product_type">Тип продукта</input-label>
                        <SelectInput :options="productTypes" v-model="form.product_type" id="product_type"
                                     class="h-7 text-sm w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     :class="{'bg-gray-200': form.is_idle}" :disabled=" form.is_idle"
                                     :placeholder-option="'выберите тип продукта'"
                        />
                        <InputError class="mt-2" :message="form.errors.product_type"/>
                    </div>

                    <div class="flex gap-2 pb-6">
                        <input-label class="w-1/3 self-center" for="mat1_id">Продукция</input-label>
                        <div class="w-full gap-2 flex flex-col">

                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <TextInput
                                        id="mat1_id"
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model="form.mat1_id"
                                        placeholder="материал-1"
                                        autocomplete="off"
                                        list="materials"
                                    />
                                    <InputError class="mt-2" :message="form.errors.mat1_id"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.thickness1"
                                        placeholder="толщина, мкм"
                                        title="толщина, мкм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.thickness1"/>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <TextInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model="form.mat2_id"
                                        placeholder="материал-2"
                                        autocomplete="off"
                                        list="materials"
                                    />
                                    <InputError class="mt-2" :message="form.errors.mat2_id"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.thickness2"
                                        placeholder="толщина, мкм"
                                        title="толщина, мкм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.thickness2"/>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <TextInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model="form.mat3_id"
                                        placeholder="материал-3"
                                        autocomplete="off"
                                        list="materials"
                                    />
                                    <InputError class="mt-2" :message="form.errors.mat3_id"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.thickness3"
                                        placeholder="толщина, мкм"
                                        title="толщина, мкм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.thickness3"/>
                                </div>
                            </div>
                            <div class="mt-3 flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.count_plan"
                                        placeholder="план, кг"
                                        title="план, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.count_plan"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.count"
                                        placeholder="факт, кг"
                                        title="факт, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.count"/>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.product_width"
                                        placeholder="ширина, мм"
                                        title="ширина, мм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.product_width"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.length"
                                        placeholder="длина, мм"
                                        title="длина, мм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.length"/>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-1/2">
                                    <SelectInput :options="bottomTypes" v-model="form.bottom_type" required
                                                 class="h-7 text-sm w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                                 :class="{'bg-gray-200': form.is_idle}" :disabled=" form.is_idle"
                                                 :placeholder-option="'выберите тип дна'"
                                    />
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.bottom"
                                        placeholder="дно, мм"
                                        title="дно, мм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.bottom"/>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex pb-6">
                        <input-label class="w-1/3 self-center" for="workout">Тип пакета</input-label>
                        <div class="flex gap-6 items-center">
                            <div class="flex w-full gap-2 flex-col">
                                <label class="flex items-center">
                                    <Checkbox :class="{'bg-gray-200': form.is_idle}" :disabled="form.is_idle"
                                              v-model:checked="form.is_pocket"/>
                                    <span class="ml-2 text-sm text-gray-600">Карман</span>
                                </label>
                                <label class="flex items-center">
                                    <Checkbox :class="{'bg-gray-200': form.is_idle}" :disabled="form.is_idle"
                                              v-model:checked="form.is_handle"/>
                                    <span class="ml-2 text-sm text-gray-600">Ручка</span>
                                </label>
                            </div>
                            <div class="flex w-full gap-2 flex-col">
                                <label class="flex items-center">
                                    <Checkbox :class="{'bg-gray-200': form.is_idle}" :disabled="form.is_idle"
                                              v-model:checked="form.is_edge"/>
                                    <span class="ml-2 text-sm text-gray-600">Кромка</span>
                                </label>
                                <label class="flex items-center">
                                    <Checkbox :class="{'bg-gray-200': form.is_idle}" :disabled="form.is_idle"
                                              v-model:checked="form.is_perforation"/>
                                    <span class="ml-2 text-sm text-gray-600">Перфорация</span>
                                </label>
                            </div>
                            <div class="flex w-full gap-2 flex-col">
                                <label class="flex items-center">
                                    <Checkbox :class="{'bg-gray-200': form.is_idle}" :disabled="form.is_idle"
                                              v-model:checked="form.is_ziplock"/>
                                    <span class="ml-2 text-sm text-gray-600">Zip&nbsp;lock</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex pb-6">
                        <input-label class="w-1/3 self-center" for="workout">Выработка</input-label>
                        <div class="flex w-full gap-2 flex-col">
                            <div>
                                <NumberInput
                                    id="workout"
                                    class="h-6 block w-full text-sm"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    :disabled=" form.is_idle"
                                    v-model.number="form.workout_count"
                                    placeholder="... тысяч штук"
                                    title="тысяч штук"
                                />
                                <InputError class="mt-2" :message="form.errors.workout_count"/>
                            </div>
                            <div>
                                <NumberInput
                                    class="h-6 block w-full text-sm"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    :disabled=" form.is_idle"
                                    v-model.number="form.workout_otk"
                                    placeholder="... на ОТК, тысяч штук"
                                    title="на ОТК, тысяч штук"
                                />
                                <InputError class="mt-2" :message="form.errors.workout_otk"/>
                            </div>
                        </div>
                    </div>

                    <div class="flex pb-6">
                        <input-label class="w-1/3 self-center" for="waste_plan">Отходы</input-label>
                        <div class="flex w-full gap-2 flex-col">
                            <div class="flex gap-2">
                                <div class="w-1/2">
                                    <NumberInput
                                        id="waste_plan"
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_plan"
                                        placeholder="... план, кг"
                                        title="план, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_plan"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_print"
                                        placeholder="... печати, кг"
                                        title="печати, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_print"/>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_edge"
                                        placeholder="... кромки, кг"
                                        title="кромки, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_edge"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_welding"
                                        placeholder="... сварки, кг"
                                        title="сварки, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_welding"/>
                                </div>
                            </div>

                            <div>
                                <NumberInput
                                    class="h-6 block w-full text-sm bg-gray-200"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    v-model.number="form.waste_sum"
                                    placeholder="... итог, кг"
                                    title="итог, кг"
                                    step="0.01"
                                    disabled
                                />
                                <InputError class="mt-2" :message="form.errors.waste_sum"/>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center mt-2 pb-6">
                        <input-label class="w-1/3" for="box_type">Упаковка</input-label>
                        <SelectInput :options="boxesTypes" v-model="form.box_size_id" required id="box_type"
                                     class="h-7 text-sm w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     :class="{'bg-gray-200': form.is_idle}" :disabled=" form.is_idle"
                                     :placeholder-option="'выберите тип упаковки'"
                        />
                    </div>
                </fieldset>

                <fieldset class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                    <legend class="mt-2 w-full text-center font-semibold text-md mb-10">⚒ Технические операции</legend>

                    <div class="w-full gap-4 grid grid-cols-2">
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="calibrating">Наладка</input-label>
                            <NumberInput
                                id="calibrating"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.calibrating"
                                placeholder="... часов"
                                title="Наладка, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.calibrating"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="reconfig">Перестройка</input-label>
                            <NumberInput
                                id="reconfig"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.reconfig"
                                placeholder="... часов"
                                title="Перестройка, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.reconfig"/>
                        </div>
                    </div>

                    <div class="w-full gap-4 grid grid-cols-2">

                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="change_teflon">Замена тефлона</input-label>
                            <NumberInput
                                id="change_teflon"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.change_teflon"
                                placeholder="... часов"
                                title="Замена тефлона, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.change_teflon"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="tech_service">Техническое обслуживание</input-label>
                            <NumberInput
                                id="tech_service"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.tech_service"
                                placeholder="... часов"
                                title="Замена ракеля, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.tech_service"/>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-5 mt-5 items-center justify-center">
                        <input-label class="w-1/3" for="prepare_OK">Приправка принята</input-label>
                        <div class="flex gap-4">
                            <label class="radio-label text-sm text-gray-600">
                                <input :disabled=" form.is_idle" ID="prepare_OK" type="radio" class="input-radio green"
                                       v-model="form.prepare_ok"
                                       :class="{'bg-gray-200': form.is_idle}" value="1">
                                Да
                            </label>
                            <label class="radio-label text-sm text-gray-600">
                                <input :disabled=" form.is_idle" ID="prepare_OK" type="radio" class="input-radio red"
                                       v-model="form.prepare_ok"
                                       :class="{'bg-gray-200': form.is_idle}" value="0">
                                Нет
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="electro">Эл/мех часть</input-label>
                        <div class="flex gap-4">
                            <NumberInput
                                id="electro"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.electro"
                                placeholder="... электрика"
                                title="электрика, ч"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.electro"/>
                            <NumberInput
                                class="h-6 block w-full text-sm"
                                v-model.number="form.mechanical"
                                placeholder="... механика"
                                title="механика, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.mechanical"/>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="no">Часы отсутствия</input-label>
                        <div class="flex gap-4">
                            <NumberInput
                                id="no"
                                class="h-6 w-24 text-sm"
                                v-model.number="form.no_human"
                                placeholder="людей"
                                title="Часы отсутствия людей"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_human"/>
                            <NumberInput
                                id="no"
                                class="h-6 w-24 text-sm"
                                v-model.number="form.no_work"
                                placeholder="заказов"
                                title="Часы отсутствия заказов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_work"/>
                            <NumberInput
                                id="no"
                                class="h-6 w-24 text-sm"
                                v-model.number="form.no_raw"
                                placeholder="сырья"
                                title="Часы отсутствия сырья"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_raw"/>
                        </div>
                    </div>

                    <div class="flex items-center mt-4 mb-10">
                        <input-label class="w-1/2" for="diff_circulation">Разница тиража</input-label>
                        <div class="grid grid-cols-2 gap-4">
                            <NumberInput
                                id="diff_circulation"
                                class="h-6 block w-full bg-gray-200"
                                v-model.number="form.diff_circulation"
                                placeholder="... кг"
                                title="Разница тиража кг"
                                disabled
                                step="0.01"
                            />
                            <button @click="calcCircilation()"
                                    class="w-36 italic text-sm rounded-md border border-dashed border-green-200 font-sans border animate__animated animate__pulse bg-emerald-50"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    :disabled="form.is_idle"
                                    type="button"> 💫 вжух и готово
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="speed">Скорость</input-label>
                        <NumberInput
                            id="speed"
                            class="h-6 block w-full text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            v-model.number="form.speed"
                            placeholder="... шт/мин"
                            title="...Скорость шт/мин"
                            :disabled=" form.is_idle"
                            step="0.01"
                        />
                        <InputError class="mt-2" :message="form.errors.speed"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="notes">Примечание</input-label>
                        <TextareaInput
                            id="notes"
                            class="h-20 block w-full text-sm"
                            v-model="form.notes"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.notes"/>


                    <div class="flex justify-center mt-2">
                        <label class="flex items-center">
                            <Checkbox v-model:checked="form.is_idle"/>
                            <span class="ml-2 text-sm flex gap-2 items-center">Отметить всю смену как простой
                                <SadSmile/>
                            </span>
                        </label>
                    </div>

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

    <datalist id="machines">
        <option v-for="machine in machines" :value="machine"></option>
    </datalist>

    <datalist id="materials">
        <option v-for="material in materials" :value="material"></option>
    </datalist>

</template>

<style scoped>

.buttons {
    padding: 40px 0;
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
    box-shadow: 0 0 0 2px rgb(245, 6, 6);
    background-color: rgb(245, 6, 6);
}


</style>
