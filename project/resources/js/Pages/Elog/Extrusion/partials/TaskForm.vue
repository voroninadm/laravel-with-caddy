<script setup>
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import TextareaInput from '@/components/TextareaInput.vue';
import InputError from '@/components/InputError.vue';
import Checkbox from '@/components/Checkbox.vue';
import {useForm} from "@inertiajs/vue3";
import {onMounted, ref, watch} from "vue";
import PrimaryButton from '@/components/PrimaryButton.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {calculateMinDatepickerDate} from '@/common/dateTimeHelper.js'
import {useTknUpdater} from "@/common/useTknUpdater.js";

import {showNotification} from "@/common/notifications.js";
import axios from "axios";
import SadSmile from "@/components/icons/SadSmile.vue";
import {getTknDataFromDb} from "@/common/getTknDataFromDb.js";
import NumberInput from "@/components/NumberInput.vue";
import {calculateWorkFacts, goBack} from "@/common/elogTaskHelper.js";
import SelectInput from "@/components/SelectInput.vue";

const props = defineProps({
    auth: Object,
    extrusionTask: {
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
    productTypes: {
        required: true
    },
});

const form = useForm({
    machine_id: props.extrusionTask ? props.extrusionTask.machine_id : '',
    work_date: props.extrusionTask ? props.extrusionTask.work_date : new Date(),
    work_shift: '',
    master_id: props.extrusionTask ? props.extrusionTask.master_id : '',
    machinist1_id: props.extrusionTask ? props.extrusionTask.machinist1_id : '',
    machinist2_id: props.extrusionTask ? props.extrusionTask.machinist2_id : '',
    machinist3_id: props.extrusionTask ? props.extrusionTask.machinist3_id : '',
    student_id: props.extrusionTask ? props.extrusionTask.student_id : '',
    tkn: props.extrusionTask ? props.extrusionTask.tkn : '',
    recipe_number: props.extrusionTask ? props.extrusionTask.recipe_number : null,
    part_number: props.extrusionTask ? props.extrusionTask.part_number : '',
    work_plan: props.extrusionTask ? props.extrusionTask.work_plan : null,
    work_start: props.extrusionTask ? props.extrusionTask.work_start : '',
    work_finish: props.extrusionTask ? props.extrusionTask.work_finish : '',
    work_fact: props.extrusionTask ? props.extrusionTask.work_fact : null,
    customer: props.extrusionTask ? props.extrusionTask.customer : '',
    print_title: props.extrusionTask ? props.extrusionTask.print_title : '',
    circulation_kg: props.extrusionTask ? props.extrusionTask.circulation_kg : null,
    circulation_length: props.extrusionTask ? props.extrusionTask.circulation_length : null,

    product_type: props.extrusionTask ? props.extrusionTask.product_type : '',
    width: props.extrusionTask ? props.extrusionTask.width : null,
    thickness: props.extrusionTask ? props.extrusionTask.thickness : null,
    activation_pole: props.extrusionTask ? props.extrusionTask.activation_pole : null,
    edge: props.extrusionTask ? props.extrusionTask.edge : null,
    streams: props.extrusionTask ? props.extrusionTask.streams : null,

    roll_mass: props.extrusionTask ? props.extrusionTask.roll_mass : null,
    roll_length: props.extrusionTask ? props.extrusionTask.roll_length : null,
    roll_diameter: props.extrusionTask ? props.extrusionTask.roll_diameter : null,

    workout_mass: props.extrusionTask ? props.extrusionTask.workout_mass : null,
    workout_otk: props.extrusionTask ? props.extrusionTask.workout_otk : null,
    workout_length: props.extrusionTask ? props.extrusionTask.workout_length : null,
    workout_m2: props.extrusionTask ? props.extrusionTask.workout_m2 : null,

    waste_plan_roll: props.extrusionTask ? props.extrusionTask.waste_plan_roll : null,
    waste_roll: props.extrusionTask ? props.extrusionTask.waste_roll : null,
    waste_trans: props.extrusionTask ? props.extrusionTask.waste_trans : null,
    waste_plan_edge: props.extrusionTask ? props.extrusionTask.waste_plan_edge : null,
    waste_edge: props.extrusionTask ? props.extrusionTask.waste_edge : null,
    waste_plan_ingets: props.extrusionTask ? props.extrusionTask.waste_plan_ingets : null,
    waste_ingets: props.extrusionTask ? props.extrusionTask.waste_ingets : null,
    waste_slice: props.extrusionTask ? props.extrusionTask.waste_slice : null,
    waste_sum: props.extrusionTask ? props.extrusionTask.waste_sum : null,

    electro: props.extrusionTask ? props.extrusionTask.electro : null,
    mechanical: props.extrusionTask ? props.extrusionTask.mechanical : null,
    electronics: props.extrusionTask ? props.extrusionTask.electronics : null,
    tech_service: props.extrusionTask ? props.extrusionTask.tech_service : null,
    change_net: props.extrusionTask ? props.extrusionTask.change_net : null,
    change_raw: props.extrusionTask ? props.extrusionTask.change_raw : null,
    change_order: props.extrusionTask ? props.extrusionTask.change_order : null,
    clean_machine: props.extrusionTask ? props.extrusionTask.clean_machine : null,
    no_human: props.extrusionTask ? props.extrusionTask.no_human : null,
    no_work: props.extrusionTask ? props.extrusionTask.no_work : null,
    no_raw: props.extrusionTask ? props.extrusionTask.no_raw : null,
    prepare_hours: props.extrusionTask ? props.extrusionTask.prepare_hours : null,
    prepare_ok: props.extrusionTask ? props.extrusionTask.prepare_ok : null,
    diff_circulation: props.extrusionTask ? props.extrusionTask.diff_circulation : null,
    notes: props.extrusionTask ? props.extrusionTask.notes : '',
    is_idle: props.extrusionTask ? Boolean(props.extrusionTask.is_idle) : false,
});

const { updateAndSatisfyTkn } = useTknUpdater(form);


const submit = () => {
    // если изменяем задачу
    if (props.extrusionTask) {
        form.patch((`/extrusion/update/${props.extrusionTask.id}`), {
            onSuccess: () => {
                showNotification('success', 'Задача успешно изменена!')
            }
        })
        // или если создаем новую
    } else {
        form.post(route('extrusion.store'), {
            onSuccess: () => {
                form.reset();
                showNotification('success', 'Задача успешно создана!')
            }
        })
    }
}

// задаем номер смены по умолчанию исходя из текущего часа
function setWorkShiftBasedOnTime(form) {
    if (!props.extrusionTask) {
        const currentHour = new Date().getHours();
        currentHour >= 7 && currentHour < 19 ? form.work_shift = 1 : form.work_shift = 2;
        return
    }
    form.work_shift = props.extrusionTask.work_shift
}

setWorkShiftBasedOnTime(form);


// функция калькуляции выработки в м2
const workoutCountM2 = ref(null);

function calculateWorkoutCountM2() {
    if (form.width || form.workout_length) {
        const calc = ((+form.width * +form.workout_length) / 1000);
        workoutCountM2.value = calc !== 0 ? calc.toFixed(2) : null;
        form.workout_m2 = +workoutCountM2.value;
    } else {
        workoutCountM2.value = 0;
        form.workout_m2 = null;
    }
}

// функция cуммирования отходов
const wasteSum = ref(0);

function calculateWaste() {
    if (form.waste_roll || form.waste_trans || form.waste_edge || form.waste_ingets || form.waste_slice) {
        const calc = (+form.waste_roll + +form.waste_trans + +form.waste_edge + +form.waste_ingets + +form.waste_slice);
        wasteSum.value = calc.toFixed(2);
        form.waste_sum = +wasteSum.value;
    } else {
        wasteSum.value = 0;
        form.waste_sum = null;
    }
}

// разница тиража
const circulation = ref(0);

const calcCurrentCirculation = (tasksCirculation = 0) => {
    if (form.circulation_kg || form.workout_mass || form.workout_otk) {
        const calc = (+form.circulation_kg - (+form.workout_mass + +form.workout_otk + +tasksCirculation));
        circulation.value = calc.toFixed(2);
        form.diff_circulation = +circulation.value;
    } else {
        circulation.value = 0;
        form.diff_circulation = null;
    }
}

const calcCirculation = async () => {
    try {
        const response = await axios.post('/backend/get-circulations', {
            tkn: form.tkn,
            type: 'extrusion',
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

// отслеживание изменений для калькуляции выработки m2
watch(
    [() => form.width, () => form.workout_length],
    ([newWidth, newLength], [oldWidth, oldLength]) => {
        // изменились ли значения времени начала и окончания
        if (newWidth !== oldWidth || newLength !== oldLength) {
            calculateWorkoutCountM2()
        }
    }
);

// отслеживание изменений для суммирования отходов
watch(
    [() => form.waste_roll, () => form.waste_trans, () => form.waste_edge, () => form.waste_ingets, () => form.waste_slice],
    ([newRoll, newTrans, newEdge, newIngets, newSlice], [oldRoll, oldTrans, oldEdge, oldIngets, oldSlice]) => {
        // изменились ли значения времени начала и окончания
        if (newRoll !== oldRoll || newTrans !== oldTrans || newEdge !== oldEdge || newIngets !== oldIngets || newSlice !== oldSlice) {
            calculateWaste()
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
                form.work_plan = data.extrusion_time.toFixed(2);
                form.activation_pole = data.activation_width;
                form.streams = data.extrusion_streams;
                form.width = data.hose_width
            });
        }
    }
);

// если вся смена простой - все заблокированные поля очищаем от данных, если они занесены
watch(() => form.is_idle, (newValue, oldValue) => {
    if (newValue) {
        form.machinist1_id = form.machinist2_id = form.machinist3_id = form.student_id = form.tkn =
            form.part_number = form.work_start = form.work_finish = form.customer = form.print_title =
                form.product_type = '';

        form.recipe_number = form.work_plan = form.work_fact = form.circulation_kg =
            form.circulation_length = form.width = form.thickness = form.activation_pole = form.edge =
                form.streams = form.roll_mass = form.roll_length = form.roll_diameter = form.workout_mass =
                    form.workout_otk = form.workout_length = form.workout_length = form.workout_m2 = form.raw_meters =
                        form.waste_plan_roll = form.waste_roll = form.waste_trans = form.waste_plan_edge = form.waste_edge =
                            form.waste_plan_ingets = form.waste_ingets = form.waste_slice = form.waste_sum = form.prepare_ok =
                                form.diff_circulation = null;
    }
});
// описание времени смены
let FirstShift = ref('с 7 до 19');
let SecondShift = ref('с 19 до 7');

watch(
    () => form.machine_id,
    (newMachine) => {
        FirstShift.value = newMachine === 'КАСТ' ? 'с  8 до  20' : 'с  7 до  19';
        SecondShift.value = newMachine === 'КАСТ' ? 'с  20 до  8' : 'с  19 до  7';
    }
);
onMounted(() => {
    if (props.extrusionTask) {
        if (form.machine_id === 'КАСТ') {
            FirstShift.value = 'с  8 до  20';
            SecondShift.value = 'с  20 до  8';
        }
    }
})
</script>

<template>
    <div class="h-full gradient">
        <h1 class="ml-36 font-semibold text-md flex text-gray-600">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                <path fill="#989898" fill-rule="evenodd"
                      d="M31.4 4 27.9.7a2 2 0 0 0-2.8 0L21.6 4h2.9l1.3-1.3a1 1 0 0 1 1.4 0l2 2c.5.5.5 1.1 0 1.5L28 7.5v2.8l3.4-3.4c.8-.8.8-2 0-2.8ZM11 17.5l3.5 3.6L25.8 9.7l-3.6-3.5L11 17.4Zm-4.3 7.2c-.2.4.2.9.7.7l5.4-3.3-2.8-2.8-3.3 5.4Zm8.2-1.7-8.7 4.3c-1 .4-1.8-.4-1.4-1.4L9.1 17c0-.4.2-.8.5-1L21.6 4H2.8A2.8 2.8 0 0 0 0 6.8v22.4C0 30.7 1.3 32 2.8 32h22.4c1.5 0 2.8-1.3 2.8-2.8V10.3l-12 12a2 2 0 0 1-1 .6ZM27.2 8.3l.8-.8v-.7C28 5.3 26.7 4 25.2 4h-.7l-.8.7 3.5 3.6Z"/>
            </svg>
            <span class="ml-2">{{ extrusionTask ? 'Изменение задания ' : 'Новое задание ' }} экструзии</span>
        </h1>

        <form @submit.prevent="submit">
            <article class="grid grid xl:grid-cols-3 px-4 gap-8 mt-5">
                <fieldset class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                    <legend class=" mt-2 w-full text-center font-semibold text-md mb-6">✎ График работ</legend>

                    <div class="flex gap-4">
                        <div class="flex flex-col items-center m-auto">
                            <input-label for="invoice">Дата начала смены*</input-label>
                            <VueDatePicker v-model="form.work_date" day-picker :format="'dd.MM.yyyy'" locale="ru"
                                           text-input :enable-time-picker="false"
                                           :min-date="!props.auth.permissions.includes('full_extrusion_permission') ? calculateMinDatepickerDate() : null"
                                           auto-apply/>
                            <InputError class="mt-2" :message="form.errors.work_date"/>
                        </div>

                        <div class="flex flex-col items-center m-auto">
                            <input-label>Смена*</input-label>
                            <div class="flex gap-6 border border-white rounded-sm p-4">
                                <label class="cursor-pointer text-sm">
                                    <input type="radio" class="input-radio green" v-model="form.work_shift" value="1">
                                    {{ FirstShift }}
                                </label>
                                <label class="cursor-pointer text-sm">
                                    <input type="radio" class="input-radio green" v-model="form.work_shift" value="2">
                                    {{ SecondShift }}
                                </label>
                            </div>
                            <InputError class="mt-2" :message="form.errors.work_shift"/>
                        </div>
                    </div>


                    <div class="flex items-center">
                        <input-label class="w-1/2" for="machine_id">Машина*</input-label>

                        <SelectInput :options="machines" v-model="form.machine_id" id="machine_id" required
                                     class="h-7 text-sm w-full flex pt-0.5 rounded-lg pr-0.5"
                                     :placeholder-option="'выберите машину экструзии'"
                        />
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="master_id">Мастер смены*</input-label>
                        <TextInput
                            id="master_id"
                            class="h-6 block w-full  text-sm"
                            v-model="form.master_id"
                            autocomplete="off"
                            list="workers"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.master_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="operator_id">Машинист-1</input-label>
                        <TextInput
                            id="operator_id"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.machinist1_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.machinist1_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="packer_id">Машинист-2</input-label>
                        <TextInput
                            id="packer_id"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.machinist2_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.machinist2_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="packer_id">Машинист-3</input-label>
                        <TextInput
                            id="packer_id"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.machinist3_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.machinist3_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="student_id">Ученик</input-label>
                        <TextInput
                            id="student_id"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.student_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.student_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="tkn">Номер ТКН*</input-label>
                        <TextInput
                            id="tkn"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.tkn"
                            @input="updateAndSatisfyTkn"
                            autocomplete="off"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.tkn"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="recipe_number">Номер рецептуры*</input-label>
                        <NumberInput
                            id="recipe_number"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model.number="form.recipe_number"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.tkn"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="part_number">Номер партии*</input-label>
                        <TextInput
                            id="part_number"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.part_number"
                            autocomplete="off"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.part_number"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="circulation">План выработки кг*</input-label>
                        <NumberInput
                            id="circulation"
                            step="0.01"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model.number="form.circulation_kg"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.circulation_kg"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="circulation_length">План выработки погонные метры*</input-label>
                        <NumberInput
                            id="circulation_length"
                            step="0.01"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model.number="form.circulation_length"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.circulation_length"/>
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
                                     class="h-7 w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     :class="{'bg-gray-200': form.is_idle}" :disabled=" form.is_idle"
                                     :placeholder-option="'выберите тип продукта'"
                        />
                        <InputError class="mt-2" :message="form.errors.product_type"/>

                    </div>

                    <div class="flex gap-2 pb-2">
                        <input-label class="w-1/3 self-center" for="mat1_id">Продукт</input-label>
                        <div class="w-full gap-2 flex flex-col">
                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <NumberInput
                                        id="mat1_id"
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.width"
                                        placeholder="ширина, мм"
                                        title="ширина"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.width"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.thickness"
                                        placeholder="толщина, мкм"
                                        title="толщина, мкм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.thickness"/>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.activation_pole"
                                        placeholder="поле активации, мм"
                                        title="поле активации, мм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.activation_pole"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.edge"
                                        placeholder="кромка, мм"
                                        title="кромка, мм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.edge"/>
                                </div>
                            </div>
                            <div>
                                <NumberInput
                                    class="h-6 block w-full text-sm"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    :disabled=" form.is_idle"
                                    v-model.number="form.streams"
                                    placeholder="количество потоков"
                                    title="количество потоков"
                                    step="0.01"
                                />
                                <InputError class="mt-2" :message="form.errors.streams"/>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center pb-4">
                        <input-label for="roll" class="italic">Данные по рулонам</input-label>
                        <div class="flex gap-4 mt-2">
                            <NumberInput
                                id="roll"
                                class="h-6 w-full text-sm"
                                :class="{'bg-gray-200': form.is_idle}"
                                v-model.number="form.roll_mass"
                                :disabled=" form.is_idle"
                                placeholder="масса, кг"
                                title="масса рулона, кг"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.roll_mass"/>
                            <NumberInput
                                id="roll_length"
                                class="h-6 w-full text-sm"
                                :class="{'bg-gray-200': form.is_idle}"
                                v-model.number="form.roll_length"
                                :disabled=" form.is_idle"
                                placeholder="длина, м"
                                title="длина рулона, м"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.roll_length"/>
                            <NumberInput
                                id="roll_diameter"
                                class="h-6 w-full text-sm"
                                :class="{'bg-gray-200': form.is_idle}"
                                v-model.number="form.roll_diameter"
                                :disabled=" form.is_idle"
                                placeholder="диаметр, мм"
                                title="диаметр рулона, мм"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.roll_diameter"/>
                        </div>
                    </div>

                    <div class="flex gap-2 pb-5">
                        <input-label class="w-1/3 self-center" for="workout_mass">Выработка</input-label>
                        <div class="w-full gap-2 flex flex-col">
                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <NumberInput
                                        id="workout_mass"
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.workout_mass"
                                        placeholder="килограмм"
                                        title="килограмм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_mass"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.workout_length"
                                        placeholder="погонные метры"
                                        title="погонные метры"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_length"/>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.workout_otk"
                                        placeholder="на ОТК, кг"
                                        title="на ОТК, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_otk"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm bg-gray-200"
                                        v-model.number="form.workout_m2"
                                        placeholder="метры квадратные"
                                        title="метры квадратные"
                                        step="0.01"
                                        disabled
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_m2"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 pb-6">
                        <input-label class="w-1/3 self-center" for="waste_plan_roll">Отходы</input-label>
                        <div class="w-full gap-2 flex items-center">
                            <div class="flex flex-col w-1/2 gap-2">
                                <div class="flex flex-col">
                                    <NumberInput
                                        id="waste_plan_roll"
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_plan_roll"
                                        placeholder="план рулонные"
                                        title="план рулонные, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_plan_roll"/>
                                </div>
                                <div class="flex flex-col">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_plan_edge"
                                        placeholder="план кромки"
                                        title="план кромки, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_plan_edge"/>
                                </div>
                                <div class="flex flex-col">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_plan_ingets"
                                        placeholder="план слитки"
                                        title="план слитки, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_plan_ingets"/>
                                </div>
                            </div>
                            <div class="flex flex-col w-1/2 gap-2">
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_roll"
                                        placeholder="факт рулонные"
                                        title="факт рулонные, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_roll"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_trans"
                                        placeholder="факт переходные"
                                        title="факт переходные, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_trans"/>
                                </div>

                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_edge"
                                        placeholder="факт кромки"
                                        title="факт кромки, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_edge"/>
                                </div>

                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_ingets"
                                        placeholder="факт слитки"
                                        title="факт слитки, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_ingets"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_slice"
                                        placeholder="факт срезы"
                                        title="факт срезы, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_slice"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm bg-gray-200"
                                        v-model.number="form.waste_sum"
                                        placeholder="факт отходов итого"
                                        title="факт отходов итого, кг"
                                        step="0.01"
                                        disabled
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_sum"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                    <legend class="mt-2 w-full text-center font-semibold text-md mb-10">⚒ Технические операции</legend>

                    <div class="w-full gap-4 grid grid-cols-2">
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="electro">Электрика</input-label>
                            <NumberInput
                                id="electro"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.electro"
                                placeholder="... часов"
                                title="электрика часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.electro"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="mechanical">Механика</input-label>
                            <NumberInput
                                id="mechanical"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.mechanical"
                                placeholder="... часов"
                                title="механика, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.mechanical"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="electronics">Электроника</input-label>
                            <NumberInput
                                id="electronics"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.electronics"
                                placeholder="... часов"
                                title="электроника часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.electronics"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="tech_service">Техническое обслуживание</input-label>
                            <NumberInput
                                id="tech_service"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.tech_service"
                                placeholder="... часов"
                                title="техобслуживание часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.tech_service"/>
                        </div>
                    </div>

                    <div class="w-full gap-3 grid grid-cols-2 mt-6">
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="knifes_barbell">Замена сеток</input-label>
                            <NumberInput
                                id="knifes_barbell"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.change_net"
                                placeholder="... часов"
                                title="Замена ножей/штанги, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.change_net"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="reconfig">Замена сырья</input-label>
                            <NumberInput
                                id="reconfig"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.change_raw"
                                placeholder="... часов"
                                title="Перестройка"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.change_raw"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="change_order">Замена заказа</input-label>
                            <NumberInput
                                id="change_order"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.change_order"
                                placeholder="... часов"
                                title="Замена заказа, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.change_order"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="clean_machine">Мойка машин</input-label>
                            <NumberInput
                                id="clean_machine"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.clean_machine"
                                placeholder="... часов"
                                title="мойка машин"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.clean_machine"/>
                        </div>

                        <div class="flex gap-2 items-center self-center">
                            <input-label class="w-4/5" for="prepare_hours">Приправка</input-label>
                            <NumberInput
                                id="prepare_hours"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.prepare_hours"
                                placeholder="... часов"
                                title="приправка, часы"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.prepare_hours"/>
                        </div>

                        <div class="flex gap-2 justify-center">
                            <input-label class="w-4/5 self-center" for="prepare_ok">Приправка принята</input-label>
                            <div class="flex gap-4">
                                <label class="radio-label text-sm text-gray-600 ml-4">
                                    <input type="radio" class="input-radio green"
                                           id="prepare_ok"
                                           :class="{'bg-gray-200': form.is_idle}"
                                           :disabled=" form.is_idle"
                                           v-model="form.prepare_ok" value="1">
                                    Да
                                </label>
                                <label class="radio-label text-sm text-gray-600">
                                    <input type="radio" class="input-radio red" :class="{'bg-gray-200': form.is_idle}"
                                           :disabled=" form.is_idle"
                                           id="prepare_ok"
                                           v-model="form.prepare_ok" value="0">
                                    Нет
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center mb-5 mt-3">
                        <input-label for="no">Часы отсутствия</input-label>
                        <div class="flex gap-4 mt-2">
                            <NumberInput
                                id="no"
                                class="h-6 w-full"
                                v-model.number="form.no_human"
                                placeholder="... людей"
                                title="Часы отсутствия людей"
                                autocomplete=""
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_human"/>
                            <NumberInput
                                id="no"
                                class="h-6 w-full "
                                v-model.number="form.no_work"
                                placeholder="... заказов"
                                title="Часы отсутствия заказов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_work"/>
                            <NumberInput
                                id="no"
                                class="h-6 w-full"
                                v-model.number="form.no_raw"
                                placeholder="... сырья"
                                title="Часы отсутствия сырья"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_raw"/>
                        </div>
                    </div>

                    <div class="flex items-center mb-8">
                        <input-label class="w-1/2" for="diff_circulation">Разница тиража</input-label>
                        <div class="grid grid-cols-2 gap-4">
                            <NumberInput
                                id="diff_circulation"
                                class="h-6 block w-full"
                                :class="{'bg-gray-200' : !props.cuttingTask}"
                                v-model.number="form.diff_circulation"
                                placeholder="... кг"
                                title="Разница тиража кг"
                                :disabled="!props.cuttingTask"
                                step="0.01"
                            />
                            <button @click="calcCirculation()"
                                    class="w-36 italic text-sm rounded-md border border-dashed border-green-200 font-sans border animate__animated animate__pulse bg-emerald-50"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    :disabled="form.is_idle"
                                    type="button"> 💫 вжух и готово
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="notes">Примечание</input-label>
                        <TextareaInput
                            id="notes"
                            class="h-24 block w-full text-sm"
                            autocomplete="off"
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

                    <div class="mx-auto p-3">
                        <SecondaryButton @click="goBack" replace class="mr-10">Назад</SecondaryButton>
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

    <datalist id="productTypes">
        <option v-for="productType in productTypes" :value="productType"></option>
    </datalist>

</template>

<style scoped>

.dp__theme_light {
    --dp-disabled-color: rgb(229 231 235);
}

.gradient {
    background: linear-gradient(to left bottom, rgba(57, 70, 119, 0.15), rgba(102, 96, 128, 0) 90%);
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
