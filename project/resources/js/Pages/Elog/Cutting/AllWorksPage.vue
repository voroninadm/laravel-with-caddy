<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import {normalizeDate, normalizeDatetime} from '@/common/dateTimeHelper.js';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import Modal from '@/components/Modal.vue';
import { ref} from "vue";
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
    router.delete(`/cutting/destroy/${taskToDelete.value}`);
    confirmingTaskDeletion.value=false;
}


/**
 * Для отчета менеджера - в таблице отражаются только некоторые строки
 * @returns {boolean}
 */
const isManagerCanSee = () => {
    return !props.auth.role.includes('manager')
};
const isUserCanChange = () => {
    return props.auth.permissions.includes('medium_cutting_permission') || props.auth.permissions.includes('full_cutting_permission')
};

//=== rules
const isUserCanEditDelete = (taskWorkDate) => {
    if (props.auth.permissions.includes('medium_cutting_permission')) {
        const currentDateTime = new Date();
        const taskDateTime = new Date(taskWorkDate);

        const timeDifference = currentDateTime - taskDateTime;
        const hoursDifference = timeDifference / (1000 * 60 * 60);
        return hoursDifference <= 48;
    } else if (props.auth.permissions.includes('full_cutting_permission')) {
        return true
    }
}
</script>

<template>
    <MachinesReportFilter
        :header="'Вся резка'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'cutting.allWorks'"
        :localStorageKey="'cuttingAllWorks'"
        :auth="props.auth"
        :is-excel-button-disabled="props.tasks === null"
        :excel-path-to-convert="'/cutting/allWorks'"
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
                <th  v-if="isManagerCanSee()" class="border px-2">Оператор</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Упаковщик</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Ученик</th>
                <th class="border px-2">Номер карты</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Плановое время</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Начало работ</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Окончание работ</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Фактическое время</th>
                <th class="border px-2">Заказчик</th>
                <th class="border px-2">Заказ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Тираж</th>

                <th v-if="isManagerCanSee()" class="border px-2">Тип продукта</th>
                <th v-if="isManagerCanSee()" class="border px-2">Материал-1</th>
                <th v-if="isManagerCanSee()" class="border px-2">Толщина-1</th>
                <th v-if="isManagerCanSee()" class="border px-2">Материал-2</th>
                <th v-if="isManagerCanSee()" class="border px-2">Толщина-2</th>
                <th v-if="isManagerCanSee()" class="border px-2">Материал-3</th>
                <th v-if="isManagerCanSee()" class="border px-2">Толщина-3</th>
                <th v-if="isManagerCanSee()" class="border px-2">Ширина полотна</th>
                <th v-if="isManagerCanSee()" class="border px-2">План материала</th>
                <th v-if="isManagerCanSee()" class="border px-2">Факт материала</th>
                <th v-if="isManagerCanSee()" class="border px-2">Количествово ручьев</th>
                <th v-if="isManagerCanSee()" class="border px-2">Ширина ручья</th>

                <th class="border px-2">Выработка кг</th>
                <th class="border px-2">Выработка м.пог</th>
                <th class="border px-2">Выработка м<sup>2</sup></th>
                <th class="border px-2">Выработка ОТК</th>
                <th class="border px-2">Выработка м.сырьевые</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы план</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы печать</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы ламинация</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы кромка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы итог</th>

                <th v-if="isManagerCanSee()" class="border px-2">Простой электрика</th>
                <th v-if="isManagerCanSee()" class="border px-2">Простой механика</th>
                <th v-if="isManagerCanSee()" class="border px-2">Скорость</th>
                <th v-if="isManagerCanSee()" class="border px-2">Техническое обслуживание</th>
                <th v-if="isManagerCanSee()" class="border px-2">Замена ножей/штанги</th>
                <th v-if="isManagerCanSee()" class="border px-2">Перенастройка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие людей</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие работы</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие сырья</th>
                <th v-if="isManagerCanSee()" class="border px-2">Разница тиража</th>
                <th v-if="isManagerCanSee()" class="border px-2">Приправка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Примечание</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, index) in tasks"
                :key="task.id"
                :class="{ 'bg-slate-100/40': index % 2 === 1,
                 'bg-yellow-50' : task.is_idle,
                  'bg-red-50' : Boolean(task.prepare_ok) === false && task.prepare_ok != null }">
                <td  v-if="isUserCanChange()" class="border">
                    <Dropdown :open="false">
                        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700  focus:outline-hidden transition ease-in-out duration-150">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16m4-8h12m0 0-4-4m4 4-4 4"/>
                                            </svg>
                                            </button>
                                        </span>
                        </template>

                        <template #content>
                            <div v-if="isUserCanEditDelete(task.created_at)">
                                <DropdownLink :href="`/cutting/edit/${task.id}`">
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
                <td v-if="isManagerCanSee()" class="border" title="Оператор">{{ task.operator_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Упаковщик">{{ task.packer_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Ученик">{{ task.student_id }}</td>
                <td class="border" title="Номер карты">{{ task.tkn }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Плановое время">{{ task.work_plan ? task.work_plan.toFixed(2) : '' }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Начало работ">{{
                        task.work_start ? normalizeDatetime(task.work_start) : ''
                    }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Окончание работ">{{
                        task.work_finish ? normalizeDatetime(task.work_finish) : ''
                    }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Фактическое время">{{ task.work_fact }}</td>
                <td class="border" title="Заказчик">{{ task.customer }}</td>
                <td class="border" title="Заказ">{{ task.print_title }}</td>
                <td  v-if="isManagerCanSee()" class="border" title="Тираж">{{ task.circulation }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Тип продукта">{{ task.product_type }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Материал">{{ task.mat1_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина">{{ task.thickness1 }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Материал-2">{{ task.mat2_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина-2">{{ task.thickness2 }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Материал-3">{{ task.mat3_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина-3">{{ task.thickness3 }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Ширина полотна">{{ task.canvas_width }}</td>
                <td v-if="isManagerCanSee()" class="border" title="План">{{ task.count_plan }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Факт">{{ task.count }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Количество ручьев">{{ task.streams }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Ширина ручья">{{ task.stream_width }}</td>

                <td class="border bg-yellow-200/50" title="Выработка кг">{{ task.workout_mass }}</td>
                <td class="border bg-yellow-200/50" title="Выработка м.пог">{{ task.workout_length }}</td>
                <td class="border bg-yellow-200/50" title="Выработка м2">{{ task.workout_m2 }}</td>
                <td class="border" title="Выработка ОТК">{{ task.otk_mass }}</td>
                <td class="border" title="Сырьевые метры">{{ task.raw_meters }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Отходы план">{{ task.waste_plan }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы печать">{{ task.waste_print }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы ламинация">{{ task.waste_lam }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы кромка">{{ task.waste_edge }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы итог">{{ task.waste_sum }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Простой электрика">{{ task.electro }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Простой механика">{{ task.mechanical }}</td>
                <td v-if="isManagerCanSee()" class="border" title="скорость">{{ task.speed }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тех обслуживание">{{ task.tech_service }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена ножей/штанги">{{ task.knifes_barbell }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Перестройка">{{ task.reconfig }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие людей">{{ task.no_human }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие работы">{{ task.no_work }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие сырья">{{ task.no_raw }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Разница тиража">{{ task.diff_circulation }}</td>
                <td v-if="isManagerCanSee()" v-html="isOkSvgShow(task.prepare_ok)" class="border" title="Приправка"></td>
                <td v-if="isManagerCanSee()" class="border italic" title="Примечание">{{ task.notes }}</td>
            </tr>
            <tr class="bg-gray-100 text-gray-600">
                <td  v-if="isUserCanChange()" class="font-bold">ИТОГО</td>
                <td class="border sticky left-0 z-10 bg-inherit" title="Машина"></td>
                <td class="border" title="Дата смены"></td>
                <td class="border text-center" title="Смена"></td>
                <td v-if="isManagerCanSee()" class="border" title="Мастер"></td>
                <td v-if="isManagerCanSee()" class="border" title="Оператор"></td>
                <td v-if="isManagerCanSee()" class="border" title="Упаковщик"></td>
                <td v-if="isManagerCanSee()" class="border" title="Ученик"></td>
                <td class="border" title="Номер карты">Карт: {{ uniqueCardCount(tasks, 'tkn') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Плановое время"></td>
                <td v-if="isManagerCanSee()" class="border" title="Начало работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Окончание работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Фактическое время">{{ summarize(tasks, 'work_fact') }}</td>
                <td class="border" title="Заказчик"></td>
                <td class="border" title="Заказ"></td>
                <td  v-if="isManagerCanSee()" class="border" title="Тираж"></td>

                <td v-if="isManagerCanSee()" class="border" title="Тип продукта"></td>
                <td v-if="isManagerCanSee()" class="border" title="Материал"></td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина">{{ averaging(tasks, 'thickness1') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Материал-2"></td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина-2">{{ averaging(tasks, 'thickness2') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Материал-3"></td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина-3">{{ averaging(tasks, 'thickness3') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Ширина полотна"></td>
                <td v-if="isManagerCanSee()" class="border" title="План"></td>
                <td v-if="isManagerCanSee()" class="border" title="Факт">{{ summarize(tasks, 'count') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Количество ручьев"></td>
                <td v-if="isManagerCanSee()" class="border" title="ширина ручья"></td>

                <td class="border bg-yellow-200/50" title="Выработка кг">{{ summarize(tasks, 'workout_mass') }}</td>
                <td class="border bg-yellow-200/50" title="Выработка м.пог">{{ summarize(tasks, 'workout_length') }}</td>
                <td class="border bg-yellow-200/50" title="Выработка м2">{{ summarize(tasks, 'workout_m2') }}</td>
                <td class="border" title="Выработка ОТК">{{ summarize(tasks, 'otk_mass') }}</td>
                <td class="border" title="Выработка сырьевые метры">{{ summarize(tasks, 'raw_meters') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы план"></td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы печать">{{ summarize(tasks, 'waste_print') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы ламинация">{{ summarize(tasks, 'waste_lam') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы кромка">{{ summarize(tasks, 'waste_edge') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы итог">{{ summarize(tasks, 'waste_sum') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Простой электрика">{{ summarize(tasks, 'electro') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Простой механика">{{ summarize(tasks, 'mechanical') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Скорость">{{ averaging(tasks, 'speed') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тех обслуживание">{{ summarize(tasks, 'tech_service') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Приправка ножей/штанги">{{ summarize(tasks, 'knifes_barbell') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Перестройка">{{ summarize(tasks, 'reconfig') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Отсутствие людей">{{ summarize(tasks, 'no_human') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие работы">{{ summarize(tasks, 'no_work') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие сырья">{{ summarize(tasks, 'no_raw') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Разница тиража"></td>
                <td v-if="isManagerCanSee()" class="border" title="Приправка"></td>
                <td v-if="isManagerCanSee()" class="border" title="Примечание"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div v-else-if="props.tasks && !tasks.length" class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи резки по заданным параметрам.</p>
    </div>
    <div v-else class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <p >Для выбора всех машин резки оставьте поле "Машина" пустым</p>
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
