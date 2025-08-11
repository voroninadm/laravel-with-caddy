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

import {showNotification} from "@/common/notifications.js";
import axios from "axios";
import SadSmile from "@/components/icons/SadSmile.vue";
import {getTknDataFromDb} from "@/common/getTknDataFromDb.js";
import {calculateWorkFacts, goBack} from "@/common/elogTaskHelper.js";
import NumberInput from "@/components/NumberInput.vue";
import SelectInput from "@/components/SelectInput.vue";
import {useTknUpdater} from "@/common/useTknUpdater.js";

const props = defineProps({
    auth: Object,
    cuttingTask: {
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
});

const form = useForm({
    machine_id: props.cuttingTask ? props.cuttingTask.machine_id : '',
    work_date: props.cuttingTask ? props.cuttingTask.work_date : new Date(),
    work_shift: '',
    master_id: props.cuttingTask ? props.cuttingTask.master_id : '',
    operator_id: props.cuttingTask ? props.cuttingTask.operator_id : '',
    packer_id: props.cuttingTask ? props.cuttingTask.packer_id : '',
    student_id: props.cuttingTask ? props.cuttingTask.student_id : '',
    tkn: props.cuttingTask ? props.cuttingTask.tkn : '',
    work_plan: props.cuttingTask ? props.cuttingTask.work_plan : null,
    work_start: props.cuttingTask ? props.cuttingTask.work_start : '',
    work_finish: props.cuttingTask ? props.cuttingTask.work_finish : '',
    work_fact: props.cuttingTask ? props.cuttingTask.work_fact : null,
    customer: props.cuttingTask ? props.cuttingTask.customer : '',
    print_title: props.cuttingTask ? props.cuttingTask.print_title : '',
    circulation: props.cuttingTask ? props.cuttingTask.circulation : null,

    product_type: props.cuttingTask ? props.cuttingTask.product_type : '',
    mat1_id: props.cuttingTask ? props.cuttingTask.mat1_id : '',
    mat2_id: props.cuttingTask ? props.cuttingTask.mat2_id : '',
    mat3_id: props.cuttingTask ? props.cuttingTask.mat3_id : '',
    thickness1: props.cuttingTask ? props.cuttingTask.thickness1 : null,
    thickness2: props.cuttingTask ? props.cuttingTask.thickness2 : null,
    thickness3: props.cuttingTask ? props.cuttingTask.thickness3 : null,
    canvas_width: props.cuttingTask ? props.cuttingTask.canvas_width : null,
    count_plan: props.cuttingTask ? props.cuttingTask.count_plan : null,
    count: props.cuttingTask ? props.cuttingTask.count : null,
    streams: props.cuttingTask ? props.cuttingTask.streams : null,
    stream_width: props.cuttingTask ? props.cuttingTask.stream_width : '',

    workout_mass: props.cuttingTask ? props.cuttingTask.workout_mass : null,
    otk_mass: props.cuttingTask ? props.cuttingTask.otk_mass : null,
    workout_length: props.cuttingTask ? props.cuttingTask.workout_length : null,
    workout_m2: props.cuttingTask ? props.cuttingTask.workout_m2 : null,
    raw_meters: props.cuttingTask ? props.cuttingTask.raw_meters : null,

    waste_plan: props.cuttingTask ? props.cuttingTask.waste_plan : null,
    waste_print: props.cuttingTask ? props.cuttingTask.waste_print : null,
    waste_lam: props.cuttingTask ? props.cuttingTask.waste_lam : null,
    waste_edge: props.cuttingTask ? props.cuttingTask.waste_edge : null,
    waste_sum: props.cuttingTask ? props.cuttingTask.waste_sum : null,

    electro: props.cuttingTask ? props.cuttingTask.electro : null,
    mechanical: props.cuttingTask ? props.cuttingTask.mechanical : null,
    speed: props.cuttingTask ? props.cuttingTask.speed : null,
    tech_service: props.cuttingTask ? props.cuttingTask.tech_service : null,
    knifes_barbell: props.cuttingTask ? props.cuttingTask.knifes_barbell : null,
    reconfig: props.cuttingTask ? props.cuttingTask.reconfig : null,
    no_human: props.cuttingTask ? props.cuttingTask.no_human : null,
    no_work: props.cuttingTask ? props.cuttingTask.no_work : null,
    no_raw: props.cuttingTask ? props.cuttingTask.no_raw : null,
    prepare_ok: props.cuttingTask ? props.cuttingTask.prepare_ok : null,
    diff_circulation: props.cuttingTask ? props.cuttingTask.diff_circulation : null,
    notes: props.cuttingTask ? props.cuttingTask.notes : '',
    is_idle: props.cuttingTask ? Boolean(props.cuttingTask.is_idle) : false,
});

const submit = () => {
    // если изменяем задачу
    if (props.cuttingTask) {
        form.patch((`/cutting/update/${props.cuttingTask.id}`), {
            onSuccess: () => {
                showNotification('success', 'Задача успешно изменена!')
            }
        })
        // или если создаем новую
    } else {
        form.post(route('cutting.store'), {
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
    if (!props.cuttingTask) {
        const currentHour = new Date().getHours();

        if (currentHour >= 7 && currentHour < 19) {
            form.work_shift = 1;
        } else {
            form.work_shift = 2;
        }
    } else {
        form.work_shift = props.cuttingTask.work_shift
    }
}

setWorkShiftBasedOnTime(form);


// функция счета сырьевых метров
const rawMetersCount = ref(0);

function calculateRawMeters() {
    if (form.workout_length || form.streams) {
        const calc = (form.workout_length / form.streams);
        rawMetersCount.value = calc.toFixed(2);
        form.raw_meters = +rawMetersCount.value;
    } else {
        rawMetersCount.value = 0;
        form.raw_meters = null;
    }
}

// функция калькуляции выработки в м2
const workoutCountM2 = ref(0);

function calculateWorkoutCountM2() {
    if (form.stream_width || form.workout_length) {
        const calc = ((form.stream_width * form.workout_length) / 1000);
        workoutCountM2.value = calc.toFixed(2);
        form.workout_m2 = +workoutCountM2.value;
    } else {
        workoutCountM2.value = 0;
        form.workout_m2 = null;
    }
}

// функция cуммирования отходов
const wasteSum = ref(0);

function calculateWaste() {
    if (form.waste_print || form.waste_lam || form.waste_edge) {
        const calc = (+form.waste_print + +form.waste_lam + +form.waste_edge);
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
    if (form.circulation || form.count || form.otk_mass) {
        const calc = (+form.circulation - (+form.workout_mass + +form.otk_mass + +tasksCirculation));
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
            type: 'cutting',
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
    [() => form.stream_width, () => form.workout_length],
    ([newWidth, newLength], [oldWidth, oldLength]) => {
        // изменились ли значения времени начала и окончания
        if (newWidth !== oldWidth || newLength !== oldLength) {
            calculateWorkoutCountM2()
        }
    }
);

// отслеживание изменений для суммирования отходов
watch(
    [() => form.waste_print, () => form.waste_lam, () => form.waste_edge],
    ([newPrint, newLam, newEdge], [oldPrint, oldLam, oldEdge]) => {
        // изменились ли значения времени начала и окончания
        if (newPrint !== oldPrint || newLam !== oldLam || newEdge !== oldEdge) {
            calculateWaste()
        }
    }
);

// отслеживание изменений выработки пог.м и кол-ва ручьев для сырьевых метров
watch(
    [() => form.workout_length, () => form.streams],
    ([newLength, newStreams], [oldLength, oldStreams]) => {
        // изменились ли значения времени начала и окончания
        if (newLength !== oldLength || newStreams !== oldStreams) {
            calculateRawMeters()
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
                form.circulation = data.circulation_kg;
                form.streams = data.streams;
                form.canvas_width = data.product_width;
                form.stream_width = [...new Set(data.streams_widths)].join(' и ');
                form.work_plan = +data.cutting_time.toFixed(2);
            });
        }
    }
);

// если вся смена простой - все заблокированные поля очищаем от данных, если они занесены
watch(() => form.is_idle, (newValue, oldValue) => {
    if (newValue) {
        form.operator_id = form.packer_id = form.student_id = form.tkn = form.work_start = form.work_finish =
            form.customer = form.print_title = form.product_type = form.stream_width = form.mat1_id = form.mat2_id =
                form.mat3_id = '';

        form.work_plan = form.work_fact = form.circulation = form.thickness1 = form.thickness2 = form.thickness3 =
            form.canvas_width = form.count_plan = form.count = form.streams = form.workout_mass = form.otk_mass =
                form.workout_length = form.workout_m2 = form.raw_meters = form.waste_plan = form.waste_print = form.waste_lam =
                    form.waste_edge = form.waste_sum = form.prepare_ok = form.speed = form.diff_circulation = null;
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
            <span class="ml-2">{{ cuttingTask ? 'Изменение задания ' : 'Новое задание ' }} резки</span>
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
                                           :min-date="!props.auth.permissions.includes('full_cutting_permission') ? calculateMinDatepickerDate() : null"
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
                        <input-label class="w-1/2" for="machine_id">Бобинорезка*</input-label>
                        <SelectInput :options="machines" v-model="form.machine_id" id="machine_id" required
                                     class="h-7 text-sm w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     :placeholder-option="'выберите машину резки'"
                        />
                        <InputError class="mt-2" :message="form.errors.machine_id"/>
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
                        <input-label class="w-1/2" for="operator_id">Оператор</input-label>
                        <TextInput
                            id="operator_id"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.operator_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.operator_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="packer_id">Упаковщик</input-label>
                        <TextInput
                            id="packer_id"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.packer_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.packer_id"/>
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
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model.number="form.circulation"
                            autocomplete="off"
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
                                     class="h-7 text-sm text-sm w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     :class="{'bg-gray-200': form.is_idle}" :disabled=" form.is_idle"
                                     :placeholder-option="'выберите тип продукта'"
                        />
                        <InputError class="mt-2" :message="form.errors.product_type"/>
                    </div>

                    <div class="flex gap-2 pb-6">
                        <input-label class="w-1/3 self-center" for="mat1_id">Материал</input-label>
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
                                        title="материал-1"
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
                                        title="материал-2"
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
                                        titke="материал-3"
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

                            <div>
                                <NumberInput
                                    class="h-6 block w-full text-sm"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    :disabled=" form.is_idle"
                                    v-model.number="form.canvas_width"
                                    placeholder="ширина полотна, мм"
                                    title="ширина полотна, мм"
                                    step="0.01"
                                />
                                <InputError class="mt-2" :message="form.errors.canvas_width"/>
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
                                        v-model.number="form.streams"
                                        placeholder="кол-во ручьев"
                                        title="кол-во ручьев"
                                    />
                                    <InputError class="mt-2" :message="form.errors.streams"/>
                                </div>
                                <div class="w-1/2">
                                    <TextInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model="form.stream_width"
                                        placeholder="ширина ручья, мм"
                                        title="ширина ручья, мм"
                                        autocomplete="off"
                                    />
                                    <InputError class="mt-2" :message="form.errors.stream_width"/>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex gap-6 mt-2">
                        <div class="flex pb-6 gap-6">
                            <input-label class="w-1/3 self-center" for="workout_mass">Выработка</input-label>
                            <div class="flex w-full gap-2 flex-col">
                                <div>
                                    <NumberInput
                                        id="workout_mass"
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.workout_mass"
                                        placeholder="... кг"
                                        title="килограмм"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_mass"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.workout_length"
                                        placeholder="... пог.м"
                                        title="погонных метров"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_length"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.otk_mass"
                                        placeholder="... на ОТК, кг"
                                        title="на ОТК, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.otk_mass"/>
                                </div>
                                <div>
                                    <NumberInput
                                        type="number"
                                        class="h-6 block w-full text-sm bg-gray-200"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        v-model.number="form.workout_m2"
                                        placeholder="... м²"
                                        title="квадратных метров"
                                        disabled
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_m2"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm bg-gray-200"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        v-model.number="form.raw_meters"
                                        placeholder="... сырьевые м"
                                        title="сырьевые метры"
                                        disabled
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.raw_meters"/>
                                </div>
                            </div>
                        </div>

                        <div class="flex pb-6 gap-6">
                            <input-label class="w-1/3 self-center" for="waste_plan">Отходы</input-label>
                            <div class="flex w-full gap-2 flex-col">
                                <div>
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
                                <div>
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
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.waste_lam"
                                        placeholder="... ламинации, кг"
                                        title="ламинации, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_lam"/>
                                </div>
                                <div>
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
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm bg-gray-200"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        v-model.number="form.waste_sum"
                                        placeholder="... итог, кг"
                                        title="итог, кг"
                                        disabled
                                        step="0.01"
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
                    </div>

                    <div class="w-full gap-4 grid grid-cols-2">
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="speed">Скорость</input-label>
                            <NumberInput
                                id="speed"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.speed"
                                placeholder="метры/мин"
                                title="Скорость"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.speed"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="tech_service">Техническое обслуживание</input-label>
                            <NumberInput
                                id="tech_service"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.tech_service"
                                placeholder="... часов"
                                title="Тех обслуживание, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.tech_service"/>
                        </div>
                    </div>

                    <div class="w-full gap-4 grid grid-cols-2">
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="knifes_barbell">Замена ножей/штанги</input-label>
                            <NumberInput
                                id="knifes_barbell"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.knifes_barbell"
                                placeholder="... часов"
                                title="Замена ножей/штанги, часов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.knifes_barbell"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-4/5" for="reconfig">Перестройка</input-label>
                            <NumberInput
                                id="reconfig"
                                class="h-6 block w-full text-sm"
                                v-model.number="form.reconfig"
                                placeholder="... часов"
                                title="Перестройка"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.reconfig"/>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-center py-2">
                        <input-label for="prepare">Приправка принята</input-label>
                        <div class="flex gap-4">
                            <label class="radio-label text-sm text-gray-600 ml-4">
                                <input type="radio" class="input-radio green"
                                       :class="{'bg-gray-200': form.is_idle}"
                                       :disabled=" form.is_idle"
                                       v-model="form.prepare_ok" value="1">
                                Да
                            </label>
                            <label class="radio-label text-sm text-gray-600">
                                <input type="radio" class="input-radio red" :class="{'bg-gray-200': form.is_idle}"
                                       :disabled=" form.is_idle"
                                       v-model="form.prepare_ok" value="0">
                                Нет
                            </label>
                        </div>
                    </div>
                    <div class="flex flex-col items-center mb-5">
                        <input-label for="no">Часы отсутствия</input-label>
                        <div class="flex gap-4 mt-2">
                            <NumberInput
                                id="no"
                                class="h-6 w-full"
                                v-model.number="form.no_human"
                                placeholder="... людей"
                                title="Часы отсутствия людей"
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

    <datalist id="materials">
        <option v-for="material in materials" :value="material"></option>
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
