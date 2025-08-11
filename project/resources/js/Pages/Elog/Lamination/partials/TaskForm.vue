<script setup>
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
import {showNotification} from '@/common/notifications.js';
import {calculateMinDatepickerDate} from '@/common/dateTimeHelper.js'
import SadSmile from "@/components/icons/SadSmile.vue";
import {getTknDataFromDb} from "@/common/getTknDataFromDb.js";
import {
    calcCirculation,
    calculateWaste,
    calculateWorkFacts,
    calculateWorkoutCountM2,
    goBack, setWorkShiftBasedOnTime
} from "@/common/elogTaskHelper.js";
import NumberInput from "@/components/NumberInput.vue";
import SelectInput from "@/components/SelectInput.vue";
import {useTknUpdater} from "@/common/useTknUpdater.js";
import {getEncostIdlesData} from "@/common/getDataFromEncost.js";
import {useTimeUpdate} from "@/common/useTimeUpdater.js";
import PlusIcon from "@/components/icons/PlusIcon.vue";


const props = defineProps({
    auth: Object,
    lamTask: {
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
    coverings: {
        required: true
    },
});

defineEmits(['submit']);

const form = useForm({
    machine_id: props.lamTask ? props.lamTask.machine_id : '',
    work_date: props.lamTask ? props.lamTask.work_date : new Date(),
    work_shift: props.printTask ? props.printTask.work_shift : setWorkShiftBasedOnTime(8, 20),
    master_id: props.lamTask ? props.lamTask.master_id : '',
    operator1_id: props.lamTask ? props.lamTask.operator1_id : '',
    operator2_id: props.lamTask ? props.lamTask.operator2_id : '',
    operator3_id: props.lamTask ? props.lamTask.operator3_id : '',
    student_id: props.lamTask ? props.lamTask.student_id : '',
    helper_id: props.lamTask ? props.lamTask.helper_id : '',
    tkn: props.lamTask ? props.lamTask.tkn : '',
    work_plan: props.lamTask ? props.lamTask.work_plan : null,
    work_start: props.lamTask ? props.lamTask.work_start : '',
    work_finish: props.lamTask ? props.lamTask.work_finish : '',
    work_fact: props.lamTask ? props.lamTask.work_fact : null,
    work_productive: props.lamTask ? props.lamTask.work_productive : null,
    customer: props.lamTask ? props.lamTask.customer : '',
    print_title: props.lamTask ? props.lamTask.print_title : '',
    circulation: props.lamTask ? props.lamTask.circulation : null,

    mat1_id: props.lamTask ? props.lamTask.mat1_id : '',
    mat2_id: props.lamTask ? props.lamTask.mat2_id : '',
    covering_id: props.lamTask ? props.lamTask.covering_id : '',
    width1: props.lamTask ? props.lamTask.width1 : null,
    width2: props.lamTask ? props.lamTask.width2 : null,
    covering_width: props.lamTask ? props.lamTask.covering_width : null,
    thickness1: props.lamTask ? props.lamTask.thickness1 : null,
    thickness2: props.lamTask ? props.lamTask.thickness2 : null,
    covering_thickness: props.lamTask ? props.lamTask.covering_thickness : null,
    mat1count_plan: props.lamTask ? props.lamTask.mat1count_plan : null,
    mat2count_plan: props.lamTask ? props.lamTask.mat2count_plan : null,
    mat1count: props.lamTask ? props.lamTask.mat1count : null,
    mat2count: props.lamTask ? props.lamTask.mat2count : null,
    covering_count: props.lamTask ? props.lamTask.covering_count : null,

    workout_mass: props.lamTask ? props.lamTask.workout_mass : null,
    workout_length: props.lamTask ? props.lamTask.workout_length : null,
    workout_m2: props.lamTask ? props.lamTask.workout_m2 : null,
    otk_mass: props.lamTask ? props.lamTask.otk_mass : null,
    waste_plan: props.lamTask ? props.lamTask.waste_plan : null,
    waste_print: props.lamTask ? props.lamTask.waste_print : null,
    waste_lam: props.lamTask ? props.lamTask.waste_lam : null,
    waste_sum: props.lamTask ? props.lamTask.waste_sum : null,

    prepare_hours: props.lamTask ? props.lamTask.prepare_hours : null,
    tech_clean: props.lamTask ? props.lamTask.tech_clean : null,
    change_glue: props.lamTask ? props.lamTask.change_glue : null,
    electro: props.lamTask ? props.lamTask.electro : null,
    mechanical: props.lamTask ? props.lamTask.mechanical : null,
    tech_service: props.lamTask ? props.lamTask.tech_service : null,
    no_human: props.lamTask ? props.lamTask.no_human : null,
    no_work: props.lamTask ? props.lamTask.no_work : null,
    no_raw: props.lamTask ? props.lamTask.no_raw : null,
    remain_perv: props.lamTask ? props.lamTask.remain_perv : null,
    remain_sec: props.lamTask ? props.lamTask.remain_sec : null,
    short_downtime: props.lamTask ? props.lamTask.short_downtime : null,
    total_downtime: props.lamTask ? props.lamTask.total_downtime : null,
    no_reason_downtime: props.lamTask ? props.lamTask.no_reason_downtime : null,
    diff_circulation: props.lamTask ? props.lamTask.diff_circulation : null,
    prepare_ok: props.lamTask ? props.lamTask.prepare_ok : null,
    notes: props.lamTask ? props.lamTask.notes : '',
    is_idle: props.lamTask ? Boolean(props.lamTask.is_idle) : false,
    is_complete: props.lamTask ? Boolean(props.lamTask.is_complete) : false,
});

const submit = () => {
    if (form.is_complete && (!form.work_productive && !form.total_downtime)) {
        showNotification('info', 'Для закрытия карты получите простои из Энкоста')
    } else if (form.is_complete && (!form.is_idle && !form.diff_circulation)) {
        showNotification('info', 'Для закрытия карты рассчитайте разницу тиража')
    } else {
        if (props.lamTask) {
            form.patch(route('lamination.update', props.lamTask.id), {
                onSuccess: () => {
                    showNotification('success', 'Задача успешно изменена!')
                }
            })
        } else {
            form.post(route('lamination.store'), {
                onSuccess: () => {
                    form.reset();
                    showNotification('success', 'Задача успешно создана!')
                }
            })
        }
    }
}

// ===== Константы и переменные =====
const IS_ADMIN = props.auth.permissions.includes('full_lamination_permission');
const isEncostConnection = ref(false);
const isSecondMaterial =props.lamTask ? ref(props.lamTask.mat2_id) : ref(false);
const {updateAndSatisfyTkn} = useTknUpdater(form);
const {handleTimeUpdate} = useTimeUpdate();

//  =====  ФУНКЦИИ  =====
const intButtonStep = computed(() => {
    if (!form.is_complete) {
        return 2;
    } else {
        return form.is_idle ? 3 : 4
    }
});

/**
 * Расчитываем разницу тиража и подставляем значение в форму
 * @returns {Promise<void>}
 */
const getCirculation = async () => {
    let formData = {
        tkn: form.tkn,
        type: 'lamination',
        planOfCirculation: form.circulation
    }
    let circulation_fields = [form.workout_mass, form.otk_mass]
    form.diff_circulation = await calcCirculation(formData, circulation_fields);
}

/**
 * Отправляем запрос и получаем простои из Энкоста, подставляем данные в поля формы
 * @returns {Promise<void>}
 */
const sendEncostRequest = async () => {
    isEncostConnection.value = true;
    try {
        let response = await getEncostIdlesData(form);
        const {
            'Работа': work_productive = null,
            'Не работа': total_downtime = null,
            'Техническое обслуживание': tech_service = null,
            'Технологическая чистка': tech_clean = null,
            'Неисправность электрика': electro = null,
            'Неисправность механика': mechanical = null,
            'Приправка': prepare_hours = null,
            'Замена клея': change_glue = null,
            'Короткая остановка': short_downtime = null,
            'Причина не указана': no_reason_downtime = null,
            'Отсутствие людей': no_human = null,
            'Отсутствие заказов': no_work = null,
            'Отсутствие сырья': no_raw = null
        } = response;

        Object.assign(form, {
            work_productive,
            total_downtime,
            tech_service,
            tech_clean,
            electro,
            mechanical,
            prepare_hours,
            change_glue,
            short_downtime,
            no_reason_downtime,
            no_human,
            no_work,
            no_raw
        });
    } catch {
        showNotification('error', 'Неисправности при получении простоев, попробуйте еще раз')
    }
    isEncostConnection.value = false;
}


//  =====  ОТСЛЕЖИВАНИЕ ИЗМЕНЕНИЙ, WATCHERS  =====
/** Отслеживание изменений времени начала и окончания работ */
watch(
    [() => form.work_start, () => form.work_finish],
    ([newStart, newFinish], [oldStart, oldFinish]) => {
        if (newStart !== oldStart || newFinish !== oldFinish) {
            form.work_fact = calculateWorkFacts(newStart, newFinish)
        }
    }
);
/** Отслеживание изменений для калькуляции выработки m2 */
watch(
    [() => form.width1, () => form.workout_length],
    ([newWidth, newLength], [oldWidth, oldLength]) => {
        if (newWidth !== oldWidth || newLength !== oldLength) {
            form.workout_m2 = calculateWorkoutCountM2(newWidth, newLength);
        }
    }
);

/** отслеживание изменений для суммирования отходов */
watch(
    [() => form.waste_print, () => form.waste_lam],
    ([newPrint, newLam], [oldPrint, oldLam]) => {
        if (newPrint !== oldPrint || newLam !== oldLam) {
            form.waste_sum = calculateWaste(form.waste_print, form.waste_lam)
        }
    }
);

/** отслеживание изменений поля tkn и обращение к SPb Db api для получения данных */
watch(
    () => form.tkn,
    (newNumber, oldNumber) => {
        if (form.tkn !== '') {
            getTknDataFromDb(newNumber, oldNumber, (data) => {
                form.customer = data.customer;
                form.print_title = data.print_title;
                form.circulation = data.circulation_kg;
                form.work_plan = +data.lamination_time.toFixed(2);
            });
        }
    }
);

/** Отмечаем простой всей смены, если общее время простоя 12 часов*/
watch(() => form.total_downtime, (newValue, oldValue) => {
    newValue >= 12 ? form.is_idle = true : form.is_idle = false;
});

/** если вся смена простой - все заблокированные поля очищаем от данных, если они занесены */
watch(() => form.is_idle, (newValue, oldValue) => {
    if (newValue) {
           form.work_start = form.work_finish = form.mat1_id = form.mat2_id = form.covering_id = '';

        form.work_plan = form.work_fact = form.width1 = form.width2 = form.covering_width =
            form.thickness1 = form.thickness2 = form.covering_thickness = form.mat1count_plan = form.mat2count_plan =
                form.mat1count = form.mat2count = form.covering_count = form.workout_mass =
                    form.workout_length = form.workout_m2 = form.otk_mass = form.diff_circulation = form.prepare_ok = null;
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
            <span class="ml-2">{{ lamTask ? 'Изменение задания ' : 'Новое задание ' }} ламинации</span>
        </h1>

        <form @submit.prevent="submit">
            <article class="grid grid px-4 gap-8 mt-5 relative"
                     :class="form.is_complete ? 'xl:grid-cols-3' : 'xl:grid-cols-2 mx-40'">
                <fieldset class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                    <legend class=" mt-2 w-full text-center font-semibold text-md mb-6">✎ График работ</legend>

                    <div class="flex gap-4">
                        <div class="flex flex-col items-center m-auto">
                            <input-label for="invoice">Дата начала смены
                                <span class="text-red-600">*</span>
                            </input-label>
                            <VueDatePicker v-model="form.work_date" day-picker :format="'dd.MM.yyyy'" locale="ru"
                                           text-input :enable-time-picker="false"
                                           :min-date="!props.auth.permissions.includes('full_lamination_permission') ? calculateMinDatepickerDate() : null"
                                           auto-apply required/>
                            <InputError class="mt-2" :message="form.errors.work_date"/>
                        </div>

                        <div class="flex flex-col items-center m-auto">
                            <input-label>Смена
                                <span class="text-red-600">*</span>
                            </input-label>
                            <div class="flex gap-6 border border-white rounded-sm p-2">
                                <label class="radio-label text-sm">
                                    <input required type="radio" class="input-radio green" v-model="form.work_shift"
                                           value="1">
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
                        <input-label class="w-1/2" for="machine_id">Ламинатор
                            <span class="text-red-600">*</span>
                        </input-label>
                        <SelectInput :options="machines" v-model="form.machine_id" id="machine_id" required
                                     class="h-7 text-sm w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     placeholderOption="Выберите машину ламинации"/>

                        <InputError class="mt-2" :message="form.errors.machine_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="master_id">Мастер смены
                            <span class="text-red-600">*</span>
                        </input-label>
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
                        <input-label class="w-1/2" for="operator1_id">Оператор 1</input-label>
                        <TextInput
                            id="operator1_id"
                            class="h-6 block w-full"
                            v-model="form.operator1_id"
                            autocomplete="off"
                            list="workers"
                            :required="!form.is_idle && form.is_complete"
                        />
                        <InputError class="mt-2" :message="form.errors.operator1_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="operator2_id">Оператор 2</input-label>
                        <TextInput
                            id="operator2_id"
                            class="h-6 block w-full"
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
                            v-model="form.operator3_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.operator3_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="student_id">Ученик оператора</input-label>
                        <TextInput
                            id="student_id"
                            class="h-6 block w-full"
                            v-model="form.student_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.student_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="helper_id">Подсобный рабочий</input-label>
                        <TextInput
                            id="helper_id"
                            class="h-6 block w-full"
                            v-model="form.helper_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.helper_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="tkn">
                            <span class="border rounded-full px-1 py-0.5 bg-yellow-100 border-orange-600">1</span> ТКН
                            <span class="text-red-600">*</span>
                        </input-label>
                        <TextInput
                            id="tkn"
                            class="h-6 block w-full"
                            @input="updateAndSatisfyTkn"
                            v-model="form.tkn"
                            autocomplete="off"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.tkn"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="circulation">План выработки (тираж)</input-label>
                        <NumberInput
                            id="circulation"
                            class="h-6 block w-full"
                            v-model.number="form.circulation"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.circulation"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="customer">Заказчик</input-label>
                        <TextInput
                            id="customer"
                            class="h-6 block w-full"
                            v-model="form.customer"
                            autocomplete="off"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.customer"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="print_title">Заказ</input-label>
                        <TextInput
                            id="print_title"
                            class="h-6 block w-full text-sm"
                            v-model="form.print_title"
                            autocomplete="off"
                            :required="!form.is_idle"
                        />
                        <InputError class="mt-2" :message="form.errors.print_title"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="work_start">Начало работ
                            <span v-if="form.is_complete" class="text-red-600">*</span>
                        </input-label>
                        <VueDatePicker
                            v-model="form.work_start"
                            :format="'dd.MM.yyyy HH:mm'"
                            locale="ru"
                            text-input
                            :class="{'bg-gray-200': form.is_idle}"
                            id="work_start"
                            :disabled="form.is_idle"
                            :required="form.is_complete && !form.is_idle"
                            time-picker-inline
                            :enable-minutes="false"
                            @update:modelValue="handleTimeUpdate(form,'work_start', form.work_start )"
                            auto-apply/>
                        <InputError class="mt-2" :message="form.errors.work_start"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="work_finish">Окончание работ
                            <span v-if="form.is_complete" class="text-red-600">*</span>
                        </input-label>
                        <VueDatePicker
                            v-model="form.work_finish"
                            :format="'dd.MM.yyyy HH:mm'"
                            locale="ru"
                            text-input
                            class="ring-orange"
                            :class="{'bg-gray-200': form.is_idle}"
                            id="work_finish"
                            :disabled=" form.is_idle"
                            :required="form.is_complete && !form.is_idle"
                            :enable-minutes="false"
                            time-picker-inline
                            @update:modelValue="handleTimeUpdate(form,'work_finish', form.work_finish )"
                            auto-apply/>
                        <InputError class="mt-2" :message="form.errors.work_finish"/>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div class="flex gap-4 items-center">
                            <input-label class="w-3/4 text-center" for="work_plan">Планируемое время</input-label>
                            <TextInput
                                id="work_plan"
                                class="h-6 block w-full"
                                :class="{'bg-gray-200': form.is_idle}"
                                :disabled=" form.is_idle"
                                v-model.number="form.work_plan"
                            />
                            <InputError class="mt-2" :message="form.errors.work_plan"/>
                        </div>

                        <div class="flex gap-4 items-center">
                            <input-label class="w-3/4 text-center" for="work_fact">Фактическое время</input-label>
                            <TextInput
                                id="work_fact"
                                class="h-6 block w-full bg-gray-200"
                                v-model.number="form.work_fact"
                                readonly
                            />
                            <InputError class="mt-2" :message="form.errors.work_fact"/>
                        </div>

                        <div class="flex gap-4 items-center text-sm text-gray-600">
                            <p class="w-3/4 text-center">Учтено Энкостом:</p>
                            <span class="w-full h-6 border rounded-lg text-black py-0.5 px-3 bg-gray-200  block"
                            >{{
                                    (form.total_downtime + form.work_productive) === 0 ? ''
                                        : (form.total_downtime + form.work_productive).toFixed(2)
                                }}
                                </span>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white relative">
                    <legend class="mt-2 w-full text-center font-semibold text-md mb-6">🧻 Расход материала</legend>


                    <div class="grid grid-cols-2 items-center justify-center gap-4">
                        <div class="gap-2 flex flex-col" :class="{'col-span-2' : !isSecondMaterial}">
                            <div class="flex gap-2 items-center justify-center">
                                <input-label class=" self-center font-semibold"
                                             for="mat1_id">{{ isSecondMaterial ? 'Первый материал' : 'Материал' }}
                                </input-label>
                                <button v-if="!isSecondMaterial" type="button"
                                        class="p-0.5 flex gap-2 border border-dashed opacity-30 text-sm text-gray-500 border-gray-600 rounded-full hover:opacity-50 transition duration-200"
                                        @click="isSecondMaterial = true">
                                    <PlusIcon class="w-5 h-5"></PlusIcon>
                                </button>
                            </div>

                            <TextInput
                                id="mat1_id"
                                class="h-6 block"
                                :class="{'bg-gray-200': form.is_idle, 'w-1/2 mx-auto' : !isSecondMaterial}"
                                :disabled=" form.is_idle"
                                v-model="form.mat1_id"
                                placeholder="... материал"
                                autocomplete="off"
                                list="materials"
                                :required="!form.is_idle && form.is_complete"
                            />
                            <InputError class="mt-2" :message="form.errors.mat1_id"/>

                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.mat1count_plan"
                                        placeholder="план, кг"
                                        step="0.01"
                                        :required="form.mat1_id !== ''"
                                    />
                                    <InputError class="mt-2" :message="form.errors.mat1count_plan"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.mat1count"
                                        placeholder="факт, кг"
                                        step="0.01"
                                        :required="form.mat1_id !== ''"
                                    />
                                    <InputError class="mt-2" :message="form.errors.mat1count"/>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex flex-col w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.width1"
                                        placeholder="ширина, мм"
                                        step="0.01"
                                        :required="form.mat1_id !== ''"
                                    />
                                    <InputError class="mt-2" :message="form.errors.width1"/>
                                </div>
                                <div class="w-1/2">
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.thickness1"
                                        placeholder="толщина, мкм"
                                        step="0.01"
                                        :required="form.mat1_id !== ''"
                                    />
                                    <InputError class="mt-2" :message="form.errors.thickness1"/>
                                </div>
                            </div>
                            <NumberInput
                                class="h-6 block w-full text-sm"
                                :class="{'bg-gray-200': form.is_idle}"
                                :disabled=" form.is_idle"
                                v-model="form.remain_perv"
                                placeholder="... остаток первички"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.remain_perv"/>
                        </div>


                        <div class="flex gap-2">
                            <div v-show="isSecondMaterial" class="gap-2 flex flex-col">
                                <input-label class="self-center font-semibold" for="mat2_id">Второй материал
                                </input-label>
                                <TextInput
                                    id="mat2_id"
                                    class="h-6 block w-full"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    :disabled=" form.is_idle"
                                    v-model="form.mat2_id"
                                    placeholder="... материал"
                                    autocomplete="off"
                                    list="materials"
                                />
                                <InputError class="mt-2" :message="form.errors.mat2_id"/>

                                <div class="flex gap-2">
                                    <div class="flex flex-col w-1/2">
                                        <NumberInput
                                            class="h-6 block w-full text-sm"
                                            :class="{'bg-gray-200': form.is_idle}"
                                            :disabled=" form.is_idle"
                                            v-model.number="form.mat2count_plan"
                                            placeholder="план,кг"
                                            step="0.01"
                                            :required="form.mat2_id !== ''"
                                        />
                                        <InputError class="mt-2" :message="form.errors.mat2count_plan"/>
                                    </div>
                                    <div class="w-1/2">
                                        <NumberInput
                                            class="h-6 block w-full text-sm"
                                            :class="{'bg-gray-200': form.is_idle}"
                                            :disabled=" form.is_idle"
                                            v-model.number="form.mat2count"
                                            placeholder="факт,кг"
                                            step="0.01"
                                            :required="form.mat2_id !== ''"
                                        />
                                        <InputError class="mt-2" :message="form.errors.mat2count"/>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <div class="flex flex-col w-1/2">
                                        <NumberInput
                                            class="h-6 block w-full text-sm"
                                            :class="{'bg-gray-200': form.is_idle}"
                                            :disabled=" form.is_idle"
                                            v-model.number="form.width2"
                                            placeholder="ширина,мм"
                                            step="0.01"
                                            :required="form.mat2_id !== ''"
                                        />
                                        <InputError class="mt-2" :message="form.errors.width2"/>
                                    </div>
                                    <div class="w-1/2">
                                        <NumberInput
                                            class="h-6 block w-full text-sm"
                                            :class="{'bg-gray-200': form.is_idle}"
                                            :disabled=" form.is_idle"
                                            v-model.number="form.thickness2"
                                            placeholder="толщина,мкм"
                                            step="0.01"
                                            :required="form.mat2_id !== ''"
                                        />
                                        <InputError class="mt-2" :message="form.errors.thickness2"/>
                                    </div>
                                </div>
                                <NumberInput
                                    class="h-6 block w-full text-sm"
                                    :class="{'bg-gray-200': form.is_idle}"
                                    :disabled=" form.is_idle"
                                    v-model="form.remain_sec"
                                    placeholder="... остаток вторички"
                                    step="0.01"
                                />
                                <InputError class="mt-2" :message="form.errors.remain_sec"/>
                            </div>
                        </div>


                        <div class="flex gap-2 col-span-2">
                            <div class="w-full gap-2 flex flex-col">
                                <input-label class="self-center font-semibold" for="covering_id">Покрытие</input-label>
                                <div class="flex gap-4">
                                    <div class="flex flex-col w-1/2">
                                        <SelectInput :options="coverings" v-model="form.covering_id" id="covering_id"
                                                     class="h-7 text-sm w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                                     :class="{'bg-gray-200': form.is_idle}" :disabled=" form.is_idle"
                                                     :placeholder-option="'выберите покрытие'" />
                                        <InputError class="mt-2" :message="form.errors.covering_id"/>
                                    </div>
                                    <div class="w-1/2">
                                        <NumberInput
                                            class="h-6 block w-full text-sm"
                                            :class="{'bg-gray-200': form.is_idle}"
                                            :disabled=" form.is_idle"
                                            v-model.number="form.covering_count"
                                            placeholder="факт, кг"
                                            step="0.01"
                                            :required="form.covering_id !== ''"
                                        />
                                        <InputError class="mt-2" :message="form.errors.covering_count"/>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="flex flex-col w-1/2">
                                        <NumberInput
                                            class="h-6 block w-full text-sm"
                                            :class="{'bg-gray-200': form.is_idle}"
                                            :disabled=" form.is_idle"
                                            v-model.number="form.covering_width"
                                            placeholder="ширина, мм"
                                            step="0.01"
                                            :required="form.covering_id !== ''"
                                        />
                                        <InputError class="mt-2" :message="form.errors.covering_width"/>
                                    </div>
                                    <div class="w-1/2">
                                        <NumberInput
                                            class="h-6 block w-full text-sm"
                                            :class="{'bg-gray-200': form.is_idle}"
                                            :disabled=" form.is_idle"
                                            v-model.number="form.covering_thickness"
                                            placeholder="толщина, мкм"
                                            step="0.01"
                                            :required="form.covering_id !== ''"
                                        />
                                        <InputError class="mt-2" :message="form.errors.covering_thickness"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col pb-6 gap-2">
                            <input-label class="self-center font-semibold" for="workout_mass">Выработка</input-label>
                            <div class="grid grid-cols-2 w-full gap-2">
                                <div>
                                    <NumberInput
                                        id="workout_mass"
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.workout_mass"
                                        placeholder="кг"
                                        title="килограмм"
                                        step="0.01"
                                        :required="form.is_complete && !form.is_idle"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_mass"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.workout_length"
                                        placeholder="пог.м"
                                        title="погонных метров"
                                        step="0.01"
                                        :required="form.is_complete && !form.is_idle"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_length"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        :disabled=" form.is_idle"
                                        v-model.number="form.otk_mass"
                                        placeholder="на ОТК, кг"
                                        title="на ОТК, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.otk_mass"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm bg-gray-200"
                                        :class="{'bg-gray-200': form.is_idle}"
                                        v-model.number="form.workout_m2"
                                        placeholder="м²"
                                        title="квадратных метров"
                                        readonly
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.workout_m2"/>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col pb-6 gap-2">
                            <input-label class="self-center font-semibold" for="waste_plan">Килограммы отходов
                            </input-label>
                            <div class="grid grid-cols-2 w-full gap-2">
                                <div>
                                    <NumberInput
                                        id="waste_plan"
                                        class="h-6 block w-full text-sm"
                                        v-model.number="form.waste_plan"
                                        placeholder="плановые"
                                        title="план, кг"
                                        step="0.01"
                                        :required="form.is_complete && !form.is_idle"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_plan"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        v-model.number="form.waste_print"
                                        placeholder="печати"
                                        title="печати, кг"
                                        step="0.01"
                                        :required="form.is_complete && !form.is_idle"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_print"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm"
                                        v-model.number="form.waste_lam"
                                        placeholder="ламинации"
                                        title="ламинации, кг"
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_lam"/>
                                </div>
                                <div>
                                    <NumberInput
                                        class="h-6 block w-full text-sm bg-gray-200"
                                        v-model.number="form.waste_sum"
                                        placeholder="итого факт"
                                        title="итог, кг"
                                        readonly
                                        step="0.01"
                                    />
                                    <InputError class="mt-2" :message="form.errors.waste_sum"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center">
                        <input-label for="notes">Примечание</input-label>
                        <TextareaInput
                            id="notes"
                            class="h-32 block w-full text-sm"
                            autocomplete="off"
                            v-model="form.notes"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.notes"/>
                </fieldset>

                <Transition enter-active-class="animate__animated animate__fadeInDown">
                    <fieldset v-show="form.is_complete"
                              class="flex flex-col p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                        <legend class="mt-2 w-full text-center font-semibold text-md mb-6">⚒ Технические операции и
                            простои
                        </legend>

                        <div class="w-full gap-4 grid grid-cols-2 items-center justify-center">
                            <div class="flex gap-2 items-center justify-center">
                                <input-label for="pr_accepted">Приправка принята</input-label>
                                <input type="radio" class="input-radio green"
                                       id="pr_accepted" name="prepare"
                                       :class="{'bg-gray-200': form.is_idle}"
                                       :disabled="form.is_idle"
                                       v-model="form.prepare_ok" value="1"
                                       :required="form.is_complete && !form.is_idle">
                            </div>
                            <div class="flex gap-2 items-center justify-center">
                                <input-label for="pr_not_accepted">Приправка НЕ принята</input-label>
                                <input type="radio" class="input-radio red"
                                       id="pr_not_accepted" name="prepare"
                                       :class="{'bg-gray-200': form.is_idle}"
                                       :disabled="form.is_idle"
                                       v-model="form.prepare_ok" value="0"
                                >
                            </div>
                        </div>
                        <hr>

                        <div class="relative flex justify-center">
                            <button @click="sendEncostRequest" :disabled="isEncostConnection"
                                    class=" w-1/2 mx-auto my-2 p-1  italic text-sm rounded-md shadow-inner border text-white font-sans border text-white transition duration-200"
                                    :class="[isEncostConnection ? 'opacity-70 text-gray-700' : 'hover:text-gray-800',
                                        form.work_productive || form.total_downtime ? 'bg-gray-400' : 'bg-red-700' ]"
                                    type="button">
                            <span
                                class="border rounded-full px-1 py-0.5 bg-yellow-100 border-orange-600 text-xs text-black not-italic">2</span>
                                💫 {{
                                    isEncostConnection ? '... телеграфируем в Энкост' : 'получить простои из "Энкост"'
                                }}
                            </button>
                        </div>

                        <div class="w-full gap-4 grid grid-cols-2">
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="work_productive">Время выпуска</input-label>
                                <NumberInput
                                    id="work_productive"
                                    title="Время выпуска, часов"
                                    class="h-6 block w-full text-sm bg-gray-200"
                                    v-model.number="form.work_productive"
                                    readonly
                                />
                                <InputError class="mt-2" :message="form.errors.work_productive"/>
                            </div>
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="total_downtime">Всего простой</input-label>
                                <NumberInput
                                    id="total_downtime"
                                    title="Общий простой"
                                    class="h-6 block w-full text-sm bg-gray-200"
                                    v-model.number="form.total_downtime"
                                    readonly
                                />
                                <InputError class="mt-2" :message="form.errors.total_downtime"/>
                            </div>
                        </div>

                        <div class="w-full items-center gap-4 grid grid-cols-2">
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="">Технологическая чистка</input-label>
                                <NumberInput
                                    id="tech_clean"
                                    class="h-6 block w-full text-sm"
                                    v-model.number="form.tech_clean"
                                    placeholder="... часов"
                                    title="Техническая чистка часов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.tech_clean"/>
                            </div>
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="tech_service">Техническое обслуживание</input-label>
                                <NumberInput
                                    id="tech_service"
                                    class="h-6 block w-full text-sm"
                                    v-model.number="form.tech_service"
                                    placeholder="... часов"
                                    title="Техническое обслуживание, часов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.tech_service"/>
                            </div>
                        </div>

                        <div class="w-full gap-4 grid grid-cols-2">
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="electro">Электрика</input-label>
                                <NumberInput
                                    id="electro"
                                    class="h-6 block w-full text-sm"
                                    v-model.number="form.electro"
                                    placeholder="... часов"
                                    title="Переклейка форм, часов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
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
                                    title="Замена ракеля, часов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.mechanical"/>
                            </div>
                        </div>

                        <div class="w-full gap-4 grid grid-cols-2">
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="">Приправка</input-label>
                                <NumberInput
                                    id="tech_clean"
                                    class="h-6 block w-full text-sm"
                                    v-model.number="form.prepare_hours"
                                    placeholder="... часов"
                                    title="Приправка часов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.prepare_hours"/>
                            </div>
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="change_glue">Замена клея</input-label>
                                <NumberInput
                                    id="change_glue"
                                    class="h-6 block w-full text-sm"
                                    v-model.number="form.change_glue"
                                    placeholder="... часов"
                                    title="Замена клея, часов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.change_glue"/>
                            </div>
                        </div>

                        <div class="w-full gap-4 grid grid-cols-2">
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="short_downtime">Краткосрочный простой</input-label>
                                <NumberInput
                                    id="short_downtime"
                                    class="h-6 block w-full text-sm"
                                    v-model.number="form.short_downtime"
                                    placeholder="... часов"
                                    title="Короткий простой, часов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.short_downtime"/>
                            </div>
                            <div class="flex gap-2 items-center">
                                <input-label class="w-4/5" for="no_reason_downtime">Без причины</input-label>
                                <NumberInput
                                    id="no_reason_downtime"
                                    class="h-6 block w-full text-sm"
                                    v-model.number="form.no_reason_downtime"
                                    placeholder="... часов"
                                    title="Без причины, часов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.no_reason_downtime"/>
                            </div>
                        </div>

                        <div class="flex flex-col items-center pt-2">
                            <input-label class="text-center font-semibold" for="no">Часы отсутствия</input-label>
                            <div class="flex gap-4">
                                <NumberInput
                                    id="no"
                                    class="h-6 block w-full text-sm"
                                    v-model.number="form.no_human"
                                    placeholder="людей"
                                    title="Часы отсутствия людей"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.no_human"/>
                                <NumberInput
                                    id="no"
                                    class="h-6 w-full text-sm"
                                    v-model.number="form.no_work"
                                    placeholder="заказов"
                                    title="Часы отсутствия заказов"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.no_work"/>
                                <NumberInput
                                    id="no"
                                    class="h-6 w-full text-sm"
                                    v-model.number="form.no_raw"
                                    placeholder="сырья"
                                    title="Часы отсутствия сырья"
                                    step="0.01"
                                    :readonly="!IS_ADMIN"
                                    :class="{'bg-gray-200' : !IS_ADMIN}"
                                />
                                <InputError class="mt-2" :message="form.errors.no_raw"/>
                            </div>
                        </div>

                        <div v-show="!form.is_idle">
                            <div class="mt-2 flex flex-col gap-2 mt-6">
                                <hr>
                                <input-label class="col-span-2 text-center font-semibold" for="tech_service">Разница
                                    тиража
                                </input-label>
                                <div class="flex w-full justify-between gap-5 items-center">
                                    <NumberInput
                                        id="diff_circulation"
                                        class="h-6 block w-1/3"
                                        v-model.number="form.diff_circulation"
                                        placeholder="... кг"
                                        title="Разница тиража кг"
                                        readonly
                                        :class="{'bg-gray-200' : !IS_ADMIN}"
                                        step="0.01"
                                    />
                                    <button @click="getCirculation"
                                            class="w-2/3 hover:text-gray-800 p-1 italic text-sm rounded-md shadow-inner text-white font-sans transition duration-200"
                                            :class="form.diff_circulation ? 'bg-gray-400' : 'bg-red-700'"
                                            type="button">
                                <span
                                    class="border rounded-full px-1 py-0.5 bg-yellow-100 border-orange-600 text-xs text-black not-italic">3</span>
                                        💫 рассчитать разницу тиража
                                    </button>
                                </div>
                                <InputError class="mt-2" :message="form.errors.diff_circulation"/>
                                <hr>
                            </div>
                        </div>
                    </fieldset>
                </Transition>
            </article>

            <div class="flex justify-center gap-5 items-center w-full p-4">
                <span v-if="form.is_idle"
                      class="absolute bottom-6 italic w-max flex items-center gap-1 text-sm text-red-700 left-1/2 transform -translate-x-1/2">
        Общий простой 12 часов - смена простоя
        <SadSmile/>
                </span>

                <SecondaryButton v-if="!form.is_complete" @click="form.is_complete = true"
                                 class="flex bg-white items-center gap-3">
                    <span class="border rounded-full px-1 py-0.5 bg-yellow-100 border-orange-600 text-xs">2</span>
                    Перейти к завершению
                </SecondaryButton>

                <SecondaryButton @click="goBack">Назад</SecondaryButton>

                <PrimaryButton class="flex gap-2">
        <span
            class="border bg-white rounded-full px-1 py-0.5 bg-yellow-100 border-orange-600 text-xs text-black not-italic">
            {{ intButtonStep }}
        </span>
                    {{ form.is_complete ? 'Сохранить и закрыть карту' : 'Сохранить как черновик' }}
                </PrimaryButton>
            </div>

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

    <datalist id="coverings">
        <option v-for="covering in coverings" :value="covering"></option>
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

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
