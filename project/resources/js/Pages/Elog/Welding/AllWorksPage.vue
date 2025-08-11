<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import {normalizeDate, normalizeDatetime} from '@/common/dateTimeHelper.js';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import Modal from '@/components/Modal.vue';
import {ref} from "vue";
import DangerButton from '@/components/DangerButton.vue';
import {router} from "@inertiajs/vue3";

import {averaging, isOkSvgShow, summarize, uniqueCardCount} from "@/common/elogTaskHelper.js";



const props = defineProps({
    auth: Object,
    machines: {
        required: true
    },
    workers: {
        required: true
    },
    tasks: {
        required: false
    }
})
const confirmingTaskDeletion = ref(false);
const taskToDelete = ref(null);
const destroyTask = () => {
    router.delete(`/welding/destroy/${taskToDelete.value}`);
    confirmingTaskDeletion.value = false;
}

/**
 * Для отчета менеджера - в таблице отражаются только некоторые строки
 * @returns {boolean}
 */
const isManagerCanSee = () => {
    return !props.auth.role.includes('manager')
};
const isUserCanChange = () => {
    return props.auth.permissions.includes('medium_welding_permission') || props.auth.permissions.includes('full_welding_permission')
};

//=== rules
const isUserCanEditDelete = (taskWorkDate) => {
    if (props.auth.permissions.includes('medium_welding_permission')) {
        const currentDateTime = new Date();
        const taskDateTime = new Date(taskWorkDate);

        const timeDifference = currentDateTime - taskDateTime;
        const hoursDifference = timeDifference / (1000 * 60 * 60);
        return hoursDifference <= 48;
    } else if (props.auth.permissions.includes('full_welding_permission')) {
        return true
    }
}
</script>

<template>
    <MachinesReportFilter
        :header="'Вся сварка'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'welding.allWorks'"
        :localStorageKey="'weldingAllWorks'"
        :auth="props.auth"
        :is-excel-button-disabled="props.tasks === null"
        :excel-path-to-convert="'/welding/allWorks'"
    />

    <div v-if="props.tasks && tasks.length > 0" class="flex flex-col mt-4 h-full overflow-auto text-center">
        <table class="mx-4 mb-20">
            <thead>
            <tr class="text-sm bg-gray-100 sticky top-0 z-20 opacity-100">
                <th v-if="isUserCanChange()" class="border"></th>
                <th class="border px-2">Машина</th>
                <th class="border px-6">Дата смены</th>
                <th class="border px-2">Cмена</th>
                <th v-if="isManagerCanSee()" class="border px-2">Мастер</th>
                <th v-if="isManagerCanSee()" class="border px-2">Оператор-1</th>
                <th v-if="isManagerCanSee()" class="border px-2">Оператор-2</th>
                <th v-if="isManagerCanSee()" class="border px-2">Оператор-3</th>
                <th v-if="isManagerCanSee()" class="border px-2">Оператор-4</th>
                <th v-if="isManagerCanSee()" class="border px-2">Оператор-5</th>
                <th v-if="isManagerCanSee()" class="border px-2">Ученик-1</th>
                <th v-if="isManagerCanSee()" class="border px-2">Ученик-2</th>
                <th v-if="isManagerCanSee()" class="border px-2">Приёмщик</th>
                <th class="border px-2">Номер карты</th>
                <th v-if="isManagerCanSee()" class="border px-2">Плановое время</th>
                <th v-if="isManagerCanSee()" class="border px-2">Начало работ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Окончание работ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Фактическое время</th>
                <th class="border px-2">Заказчик</th>
                <th class="border px-2">Заказ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Тираж</th>
                <th class="border px-2">Выработка факт</th>
                <th class="border px-2">Выработка ОТК</th>
                <th v-if="isManagerCanSee()" class="border px-2">Тип продукции</th>
                <th v-if="isManagerCanSee()" class="border px-2">Материал-1</th>
                <th class="border px-2">Толщина-1</th>
                <th v-if="isManagerCanSee()" class="border px-2">Материал-2</th>
                <th class="border px-2">Толщина-2</th>
                <th v-if="isManagerCanSee()" class="border px-2">Материал-3</th>
                <th class="border px-2">Толщина-3</th>
                <th v-if="isManagerCanSee()" class="border px-2">План материала</th>
                <th v-if="isManagerCanSee()" class="border px-2">Факт материала</th>
                <th v-if="isManagerCanSee()" class="border px-2">Тип дна</th>
                <th v-if="isManagerCanSee()" class="border px-2">Дно</th>
                <th v-if="isManagerCanSee()" class="border px-2">Ширина</th>
                <th v-if="isManagerCanSee()" class="border px-2">Длина</th>

                <th class="border px-2">Карман</th>
                <th class="border px-2">Ручка</th>
                <th class="border px-2">Кромка</th>
                <th class="border px-2">Перфорация</th>
                <th class="border px-2">Zip lock</th>

                <th v-if="isManagerCanSee()" class="border px-2">Отходы план</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы печать</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы кромка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы сварка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы итог</th>

                <th v-if="isManagerCanSee()" class="border px-2">Простой электрика</th>
                <th v-if="isManagerCanSee()" class="border px-2">Простой механика</th>
                <th v-if="isManagerCanSee()" class="border px-2">Скорость</th>
                <th v-if="isManagerCanSee()" class="border px-2">Перестройка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Наладка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Замена тефлона</th>
                <th v-if="isManagerCanSee()" class="border px-2">Тех обслуживание</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие людей</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие работы</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие сырья</th>
                <th v-if="isManagerCanSee()" class="border px-2">Разница тиража</th>
                <th v-if="isManagerCanSee()" class="border px-2">Приправка принята</th>
                <th v-if="isManagerCanSee()" class="border px-2">Упаковка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Примечание</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, index) in tasks"
                :key="task.id"
                :class="{ 'bg-slate-100/40': index % 2 === 1,
                'bg-yellow-50' : task.is_idle,
                 'bg-red-200/50' : Boolean(task.prepare_ok) === false && task.prepare_ok != null}">
                <td v-if="isUserCanChange()" class="border">
                    <Dropdown :open="false">
                        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700  focus:outline-hidden transition ease-in-out duration-150">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 viewBox="0 0 24 24">
                                                <path stroke="#000" stroke-linecap="round" stroke-linejoin="round"
                                                      stroke-width="2" d="M4 4v16m4-8h12m0 0-4-4m4 4-4 4"/>
                                            </svg>
                                            </button>
                                        </span>
                        </template>

                        <template #content>
                            <div v-if="isUserCanEditDelete(task.created_at)">
                                <DropdownLink :href="`/welding/edit/${task.id}`">
                                    Изменить
                                </DropdownLink>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="confirmingTaskDeletion = !confirmingTaskDeletion; taskToDelete = task.id">
                                    Удалить
                                </button>
                            </div>
                            <div v-else class="text-sm p-3 text-center">
                                <p class="pb-2">Недостаточно прав или с момента создания задачи прошло более 48 часов.</p>
                                <p>Для изменения или удаления обратитесь к
                                    администратору</p>
                            </div>
                        </template>
                    </Dropdown>
                </td>
                <td class="border sticky left-0 z-10 bg-slate-50" title="Машина">{{ task.machine_id }}</td>
                <td class="border" title="Дата смены">{{ normalizeDate(task.work_date) }}</td>
                <td class="border text-center" title="Смена">{{ task.work_shift }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Мастер">{{ task.master_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-1">{{ task.operator1_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-2">{{ task.operator2_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-3">{{ task.operator3_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-4">{{ task.operator4_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-5">{{ task.operator5_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Ученик-1">{{ task.student1_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Ученик-2">{{ task.student2_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Приемщик">{{ task.acceptor_id }}</td>
                <td class="border" title="Номер карты">{{ task.tkn }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Плановое время">{{ task.work_plan ? task.work_plan.toFixed(2) : '' }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Начало работ">{{
                        task.work_start ? normalizeDatetime(task.work_start) : ''
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Окончание работ">
                    {{ task.work_finish ? normalizeDatetime(task.work_finish) : '' }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Фактическое время">{{ task.work_fact }}</td>
                <td class="border" title="Заказчик">{{ task.customer }}</td>
                <td class="border" title="Заказ">{{ task.print_title }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тираж">{{ task.circulation }}</td>
                <td class="border bg-yellow-200/50" title="Выработка факт">{{ task.workout_count }}</td>
                <td class="border" title="Выработка ОТК">{{ task.workout_otk }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тип продукции">{{ task.product_type }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Материал1">{{ task.mat1_id }}</td>
                <td class="border" title="Толщина1">{{ task.thickness1 }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Материал2">{{ task.mat2_id }}</td>
                <td class="border" title="Толщина2">{{ task.thickness2 }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Материал3">{{ task.mat3_id }}</td>
                <td class="border" title="Толщина3">{{ task.thickness3 }}</td>

                <td v-if="isManagerCanSee()" class="border" title="План материала">{{ task.count_plan }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Факт материала">{{ task.count }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Тип дна">{{ task.bottom_type }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Дно">{{ task.bottom }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Ширина">{{ task.product_width }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Длина">{{ task.length }}</td>
                <td class="border" title="Карман">{{ task.is_pocket ? 'Да' : 'Нет' }}</td>
                <td class="border" title="Ручка">{{ task.is_handle ? 'Да' : 'Нет' }}</td>
                <td class="border" title="Кромка">{{ task.is_edge ? 'Да' : 'Нет' }}</td>
                <td class="border" title="Перфорация">{{ task.is_perforation ? 'Да' : 'Нет' }}</td>
                <td class="border" title="zip lock">{{ task.is_ziplock ? 'Да' : 'Нет' }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Отходы план">{{ task.waste_plan }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы печать">{{ task.waste_print }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы кромка">{{ task.waste_edge }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы сварка">{{ task.waste_welding }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы итог">{{ task.waste_sum }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Простой электрика">{{ task.electro }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Простой механика">{{ task.mechanical }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Скорость">{{ task.speed }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Перестройка">{{ task.reconfig }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Наладка">{{ task.calibrating }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена тефлона">{{ task.change_teflon }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тех обслуживание">{{ task.tech_service }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие людей">{{ task.no_human }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие работы">{{ task.no_work }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие сырья">{{ task.no_raw }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Разница тиража">{{ task.diff_circulation }}</td>
                <td v-if="isManagerCanSee()" v-html="isOkSvgShow(task.prepare_ok)" class="border" title="Приправка принята"></td>
                <td v-if="isManagerCanSee()" class="border" title="Упаковка">{{ task.box_size_id }}</td>
                <td v-if="isManagerCanSee()" class="border italic" title="Примечание">{{ task.notes }}</td>
            </tr>
            <tr class="bg-gray-100 text-gray-600">
                <td v-if="isUserCanChange()" class="font-bold">ИТОГО</td>
                <td class="border sticky left-0 z-10 bg-inherit" title="Машина"></td>
                <td class="border" title="Дата смены"></td>
                <td class="border text-center" title="Смена"></td>
                <td v-if="isManagerCanSee()" class="border" title="Мастер"></td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-1"></td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-2"></td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-3"></td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-4"></td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор-5"></td>
                <td v-if="isManagerCanSee()" class="border" title="Ученик-1"></td>
                <td v-if="isManagerCanSee()" class="border" title="Ученик-2"></td>
                <td v-if="isManagerCanSee()" class="border" title="Приемщик"></td>
                <td class="border" title="Номер карты">Карт: {{ uniqueCardCount(tasks, 'tkn') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Плановое время"></td>
                <td v-if="isManagerCanSee()" class="border" title="Начало работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Окончание работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Фактическое время">{{ summarize(tasks, 'work_fact') }}</td>
                <td class="border" title="Заказчик"></td>
                <td class="border" title="Заказ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Тираж"></td>
                <td class="border bg-yellow-200/50" title="Выработка факт">{{ summarize(tasks, 'workout_count') }}</td>
                <td class="border" title="Выработка ОТК">{{ summarize(tasks, 'workout_otk') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тип продукции"></td>
                <td v-if="isManagerCanSee()" class="border" title="Материал1"></td>
                <td class="border" title="Толщина1"></td>
                <td v-if="isManagerCanSee()" class="border" title="Материал2"></td>
                <td class="border" title="Толщина2"></td>
                <td v-if="isManagerCanSee()" class="border" title="Материал3"></td>
                <td class="border" title="Толщина3"></td>

                <td v-if="isManagerCanSee()" class="border" title="План материала"></td>
                <td v-if="isManagerCanSee()" class="border" title="Факт материала">{{ summarize(tasks, 'count') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Тип дна"></td>
                <td v-if="isManagerCanSee()" class="border" title="Дно"></td>
                <td v-if="isManagerCanSee()" class="border" title="Ширина">{{ averaging(tasks, 'product_width') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Длина">{{ averaging(tasks, 'length') }}</td>
                <td class="border" title="Карман"></td>
                <td class="border" title="Ручка"></td>
                <td class="border" title="Кромка"></td>
                <td class="border" title="Перфорация"></td>
                <td class="border" title="zip lock"></td>

                <td v-if="isManagerCanSee()" class="border" title="Отходы план"></td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы печать">{{ summarize(tasks, 'waste_print') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы кромка">{{ summarize(tasks, 'waste_edge') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы сварка">{{ summarize(tasks, 'waste_welding') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы итог">{{ summarize(tasks, 'waste_sum') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Простой электрика">{{ summarize(tasks, 'electro') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Простой механика">{{ summarize(tasks, 'mechanical') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Скорость">{{ averaging(tasks, 'speed') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Перестройка">{{ summarize(tasks, 'reconfig') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Наладка">{{ summarize(tasks, 'calibrating') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена тефлона">{{ summarize(tasks, 'change_teflon') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тех обслуживание">{{ summarize(tasks, 'tech_service') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие людей">{{ summarize(tasks, 'no_human') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие работы">{{ summarize(tasks, 'no_work') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие сырья">{{ summarize(tasks, 'no_raw') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Разница тиража"></td>
                <td v-if="isManagerCanSee()" class="border" title="Приправка принята"></td>
                <td v-if="isManagerCanSee()" class="border" title="Упаковка"></td>
                <td v-if="isManagerCanSee()" class="border" title="Примечание"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div v-else-if="props.tasks && !tasks.length"
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи сварки по заданным параметрам.</p>
    </div>
    <div v-else
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <p>Для выбора всех сварочных машин оставьте поле "Машина" пустым</p>
    </div>

    <Modal :show="confirmingTaskDeletion" @close="confirmingTaskDeletion = !confirmingTaskDeletion">
        <div class="p-6 flex flex-col">
            <p class="text-lg text-center font-medium text-gray-900">
                Вы подтверждаете что не случайно нажали на кнопку удаления?
            </p>

            <DangerButton
                class="flex mt-10 justify-center"
                @click=destroyTask>
                Удаляем
            </DangerButton>
        </div>
    </Modal>
</template>

<style scoped>

</style>
