<script setup>
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import TextareaInput from '@/components/TextareaInput.vue';
import InputError from '@/components/InputError.vue';
import Checkbox from '@/components/Checkbox.vue';
import {useForm} from "@inertiajs/vue3";
import {onMounted, onUpdated, ref, watch} from "vue";
import PrimaryButton from '@/components/PrimaryButton.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {calculateMinDatepickerDate} from '@/common/dateTimeHelper.js'

import {showNotification} from "@/common/notifications.js";
import axios from "axios";
import SadSmile from "@/components/icons/SadSmile.vue";
import {calculateWorkFacts, goBack} from "@/common/elogTaskHelper.js";
import NumberInput from "@/components/NumberInput.vue";
import SelectInput from "@/components/SelectInput.vue";

const props = defineProps({
    auth: Object,
    recyclingTask: {
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
    granulates: {
        required: true
    },
    ingots_types: {
        required: true
    },
});

const form = useForm({
    machine_id: props.recyclingTask ? props.recyclingTask.machine_id : '',
    work_date: props.recyclingTask ? props.recyclingTask.work_date : new Date(),
    work_shift: '',
    master_id: props.recyclingTask ? props.recyclingTask.master_id : '',
    machinist_id: props.recyclingTask ? props.recyclingTask.machinist_id : '',
    helper_id: props.recyclingTask ? props.recyclingTask.helper_id : '',
    work_start: props.recyclingTask ? props.recyclingTask.work_start : '',
    work_finish: props.recyclingTask ? props.recyclingTask.work_finish : '',
    work_fact: props.recyclingTask ? props.recyclingTask.work_fact : null,

    nomenclature: props.recyclingTask ? props.recyclingTask.nomenclature : '',
    workout_mass: props.recyclingTask ? props.recyclingTask.workout_mass : null,
    ingots_type: props.recyclingTask ? props.recyclingTask.ingots_type : '',
    waste_mass: props.recyclingTask ? props.recyclingTask.waste_mass : null,

    prepare_hours: props.recyclingTask ? props.recyclingTask.prepare_hours : null,
    electro: props.recyclingTask ? props.recyclingTask.electro : null,
    mechanical: props.recyclingTask ? props.recyclingTask.mechanical : null,
    electronics: props.recyclingTask ? props.recyclingTask.electronics : null,
    tech_service: props.recyclingTask ? props.recyclingTask.tech_service : null,
    change_net: props.recyclingTask ? props.recyclingTask.change_net : null,
    tech_clean: props.recyclingTask ? props.recyclingTask.tech_clean : null,
    clean_machine: props.recyclingTask ? props.recyclingTask.clean_machine : null,
    change_knifes: props.recyclingTask ? props.recyclingTask.change_knifes : null,
    change_cutter: props.recyclingTask ? props.recyclingTask.change_cutter : null,
    no_human: props.recyclingTask ? props.recyclingTask.no_human : null,
    no_work: props.recyclingTask ? props.recyclingTask.no_work : null,
    no_raw: props.recyclingTask ? props.recyclingTask.no_raw : null,
    prepare_ok: props.recyclingTask ? props.recyclingTask.prepare_ok : null,
    notes: props.recyclingTask ? props.recyclingTask.notes : '',
    is_idle: props.recyclingTask ? Boolean(props.recyclingTask.is_idle) : false,
});

const submit = () => {
    // если изменяем задачу
    if (props.recyclingTask) {
        form.patch((`/recycling/update/${props.recyclingTask.id}`), {
            onSuccess: () => {
                showNotification('success', 'Задача успешно изменена!')
            }
        })
        // или если создаем новую
    } else {
        form.post(route('recycling.store'), {
            onSuccess: () => {
                form.reset();
                showNotification('success', 'Задача успешно создана!')
            }
        })
    }
}


// задаем номер смены по умолчанию исходя из текущего часа
function setWorkShiftBasedOnTime(form) {
    if (!props.recyclingTask) {
        const currentHour = new Date().getHours();

        if (currentHour >= 7 && currentHour < 19) {
            form.work_shift = 1;
        } else {
            form.work_shift = 2;
        }
    } else {
        form.work_shift = props.recyclingTask.work_shift
    }
}

setWorkShiftBasedOnTime(form);

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

// если вся смена простой - все заблокированные поля очищаем от данных, если они занесены
watch(() => form.is_idle, (newValue, oldValue) => {
    if (newValue) {
        form.machinist_id = form.helper_id = form.ingots_type  = form.work_start = form.work_finish = form.nomenclature = '';
        form.work_fact = form.workout_mass = form.waste_mass = form.prepare_ok = null;
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
            <span class="ml-2">{{ recyclingTask ? 'Изменение задания ' : 'Новое задание ' }} переработки</span>
        </h1>

        <form @submit.prevent="submit">
            <article class="flex justify-center px-4 gap-8 mt-5">
                <fieldset class="flex flex-col w-1/3 p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                    <legend class=" mt-2 w-full text-center font-semibold text-md mb-6">✎ График работ</legend>

                    <div class="flex gap-4">
                        <div class="flex flex-col items-center m-auto">
                            <input-label for="invoice">Дата начала смены*</input-label>
                            <VueDatePicker v-model="form.work_date" day-picker :format="'dd.MM.yyyy'" locale="ru"
                                           text-input :enable-time-picker="false"
                                           :min-date="!props.auth.permissions.includes('full_recycling_permission') ? calculateMinDatepickerDate() : null"
                                           auto-apply/>
                            <InputError class="mt-2" :message="form.errors.work_date"/>
                        </div>

                        <div class="flex flex-col items-center m-auto">
                            <input-label>Смена*</input-label>
                            <div class="flex gap-6 border border-white rounded-sm p-2">
                                <label class="cursor-pointer text-sm">
                                    <input type="radio" class="input-radio green" v-model="form.work_shift" value="1">
                                    с 7 до 19
                                </label>
                                <label class="cursor-pointer text-sm">
                                    <input type="radio" class="input-radio green" v-model="form.work_shift" value="2">
                                    с 19 до 7
                                </label>
                            </div>
                            <InputError class="mt-2" :message="form.errors.work_shift"/>
                        </div>
                    </div>


                    <div class="flex items-center">
                        <input-label class="w-1/2" for="machine_id">Машина*</input-label>
                        <SelectInput :options="machines" v-model="form.machine_id" id="machine_id" required
                                     class="h-7 w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     :placeholder-option="'выберите машину переработки'"
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
                        <input-label class="w-1/2" for="machinist_id">Машинист</input-label>
                        <TextInput
                            id="machinist_id"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.machinist_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.machinist_id"/>
                    </div>

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="helper_id">Подсобный рабочий</input-label>
                        <TextInput
                            id="helper_id"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model="form.helper_id"
                            autocomplete="off"
                            list="workers"
                        />
                        <InputError class="mt-2" :message="form.errors.helper_id"/>
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

                    <div class="flex items-center">
                        <input-label class="w-1/2" for="work_fact">Фактическое время</input-label>
                        <NumberInput
                            step="0.01"
                            class="h-6 block w-full bg-gray-200"
                            v-model.number="form.work_fact"
                            disabled
                        />
                        <InputError class="mt-2" :message="form.errors.work_fact"/>
                    </div>

                    <legend class="mt-2 w-full text-center font-semibold text-md mt-5 mb-2">🧻 Расход материала</legend>
                    <div class="flex items-center">
                        <input-label class="w-1/2" for="nomenclature">Тип гранулята</input-label>
                        <SelectInput :options="granulates" v-model="form.nomenclature" id="nomenclature"
                                     class="h-7 w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     :placeholder-option="'выберите тип гранулята'"
                        />
                        <InputError class="mt-2" :message="form.errors.nomenclature"/>
                    </div>

                    <div class="flex">
                        <input-label class="w-1/2" for="workout_mass">Выработка кг</input-label>
                        <NumberInput
                            id="workout_mass"
                            class="h-6 block w-full text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model.number="form.workout_mass"
                            step="0.01"
                            placeholder="... кг"
                        />
                        <InputError class="mt-2" :message="form.errors.workout_mass"/>
                    </div>

                    <div class="flex mt-2">
                        <input-label class="w-1/2" for="ingots_type">Тип литников</input-label>
                        <SelectInput :options="ingots_types" v-model="form.ingots_type" id="ingots_type"
                                     class="h-7 w-full text-sm flex pt-0.5 rounded-lg pr-0.5"
                                     :placeholder-option="'выберите тип литников'"
                        />
                        <InputError class="mt-2" :message="form.errors.ingots_type"/>
                    </div>

                    <div class="flex">
                        <input-label class="w-1/2" for="waste_mass">Масса литников</input-label>
                        <NumberInput
                            id="waste_mass"
                            class="h-6 w-full block text-sm"
                            :class="{'bg-gray-200': form.is_idle}"
                            :disabled=" form.is_idle"
                            v-model.number="form.waste_mass"
                            placeholder="... кг"
                            title="Масса литников"
                            step="0.01"
                            min="0"
                        />
                        <InputError class="mt-2" :message="form.errors.waste_mass"/>
                    </div>

                </fieldset>

                <fieldset class="flex flex-col w-1/3 p-2 gap-2 rounded-lg shadow-2xl text-base bg-white">
                    <legend class="mt-2 w-full text-center font-semibold text-md mb-5">⚒ Технические операции</legend>

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
                                min="0"
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
                                min="0"
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
                                min="0"
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
                                min="0"
                            />
                            <InputError class="mt-2" :message="form.errors.tech_service"/>
                        </div>
                    </div>

                    <div class="flex flex-col items-center mb-5 mt-2">
                        <input-label for="change">Технические операции</input-label>
                        <div class="flex gap-4 mt-1">
                            <NumberInput
                                id="change"
                                class="h-6 w-full text-sm"
                                v-model.number="form.change_net"
                                placeholder="... замена сеток"
                                title="замена сеток"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.change_net"/>
                            <NumberInput
                                id="change"
                                class="h-6 w-full text-sm"
                                v-model.number="form.change_knifes"
                                placeholder="... замена ножей"
                                title="замена ножей"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.change_knifes"/>
                            <NumberInput
                                id="change"
                                class="h-6 w-full text-sm"
                                v-model.number="form.change_cutter"
                                placeholder="... замена резцов"
                                title="замена резцов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.change_cutter"/>
                        </div>
                    </div>

                    <div class="flex gap-5 justify-center">
                        <input-label class="self-center" for="prepare_ok">Приправка</input-label>
                        <div class="flex gap-3">
                            <label class="radio-label text-sm text-gray-600 ml-4">
                                <input type="radio" class="input-radio green"
                                       id="prepare_ok"
                                       :class="{'bg-gray-200': form.is_idle}"
                                       :disabled=" form.is_idle"
                                       v-model="form.prepare_ok" value="1">
                                Принята
                            </label>
                            <label class="radio-label text-sm text-gray-600">
                                <input type="radio" class="input-radio red" :class="{'bg-gray-200': form.is_idle}"
                                       :disabled=" form.is_idle"
                                       id="prepare_ok"
                                       v-model="form.prepare_ok" value="0">
                                Не принята
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="flex gap-4">
                            <NumberInput
                                id="no"
                                class="h-6 w-full text-sm"
                                v-model.number="form.clean_machine"
                                placeholder="... мойка"
                                title="мойка машины"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.clean_machine"/>
                            <NumberInput
                                id="no"
                                class="h-6 w-full text-sm"
                                v-model.number="form.tech_clean"
                                placeholder="... чистка"
                                title="чистка машины"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.tech_clean"/>
                            <NumberInput
                                id="no"
                                class="h-6 w-full text-sm"
                                v-model.number="form.prepare_hours"
                                placeholder="... приправка"
                                title="приправка"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.prepare_hours"/>
                        </div>
                    </div>

                    <div class="flex flex-col items-center mb-5 mt-3">
                        <input-label for="no">Часы отсутствия</input-label>
                        <div class="flex gap-4 mt-2">
                            <NumberInput
                                id="no"
                                class="h-6 w-full text-sm"
                                v-model.number="form.no_human"
                                placeholder="... людей"
                                title="Часы отсутствия людей"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_human"/>
                            <NumberInput
                                id="no"
                                class="h-6 w-full text-sm"
                                v-model.number="form.no_work"
                                placeholder="... заказов"
                                title="Часы отсутствия заказов"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_work"/>
                            <NumberInput
                                id="no"
                                class="h-6 w-full text-sm"
                                v-model.number="form.no_raw"
                                placeholder="... сырья"
                                title="Часы отсутствия сырья"
                                step="0.01"
                            />
                            <InputError class="mt-2" :message="form.errors.no_raw"/>
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
