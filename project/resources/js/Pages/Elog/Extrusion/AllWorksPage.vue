<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import {
    normalizeDate,
    normalizeDatetime
} from '@/common/dateTimeHelper.js';
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
    router.delete(`/extrusion/destroy/${taskToDelete.value}`);
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
    return props.auth.permissions.includes('medium_extrusion_permission') || props.auth.permissions.includes('full_extrusion_permission')
};

//=== rules
const isUserCanEditDelete = (taskWorkDate) => {
    if ((props.auth.permissions.includes('medium_extrusion_permission'))) {
        const currentDateTime = new Date();
        const taskDateTime = new Date(taskWorkDate);

        const timeDifference = currentDateTime - taskDateTime;
        const hoursDifference = timeDifference / (1000 * 60 * 60);
        return hoursDifference <= 48;
    } else if (props.auth.permissions.includes('full_extrusion_permission') ) {
        return true
    }
}
</script>

<template>
    <MachinesReportFilter
        :header="'Вся экструзия'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'extrusion.allWorks'"
        :localStorageKey="'extrusionAllWorks'"
        :auth="props.auth"
        :is-excel-button-disabled="props.tasks === null"
        :excel-path-to-convert="'/extrusion/allWorks'"
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
                <th  v-if="isManagerCanSee()" class="border px-2">Машинист-1</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Машинист-2</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Машинист-3</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Ученик</th>
                <th class="border px-2">Номер карты</th>
                <th class="border px-2">Номер рецептуры</th>
                <th class="border px-2">Номер партии</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Плановое время</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Начало работ</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Окончание работ</th>
                <th  v-if="isManagerCanSee()" class="border px-2">Фактическое время</th>
                <th class="border px-2">Заказчик</th>
                <th class="border px-2">Заказ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Тираж кг</th>
                <th v-if="isManagerCanSee()" class="border px-2">Тираж пог.м.</th>

                <th v-if="isManagerCanSee()" class="border px-2">Тип продукта</th>
                <th v-if="isManagerCanSee()" class="border px-2">Ширина</th>
                <th v-if="isManagerCanSee()" class="border px-2">Толщина</th>
                <th v-if="isManagerCanSee()" class="border px-2">Поле активации</th>
                <th v-if="isManagerCanSee()" class="border px-2">Кромка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Количество потоков</th>

                <th v-if="isManagerCanSee()" class="border px-2">Масса рулона</th>
                <th v-if="isManagerCanSee()" class="border px-2">Длина рулона</th>
                <th v-if="isManagerCanSee()" class="border px-2">Диаметр рулона</th>

                <th class="border px-2">Выработка кг</th>
                <th class="border px-2">Выработка м.пог</th>
                <th class="border px-2">Выработка м<sup>2</sup></th>
                <th class="border px-2">Выработка ОТК</th>

                <th v-if="isManagerCanSee()" class="border px-2">Отходы план рулонные</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы план кромки</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы план слитки</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы рулонные</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы переходные</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы кромки</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы слитки</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы срезы</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы итог</th>

                <th v-if="isManagerCanSee()" class="border px-2">Простой электрика</th>
                <th v-if="isManagerCanSee()" class="border px-2">Простой механика</th>
                <th v-if="isManagerCanSee()" class="border px-2">Простой электроника</th>
                <th v-if="isManagerCanSee()" class="border px-2">Техническое облуживание</th>
                <th v-if="isManagerCanSee()" class="border px-2">Замена сеток</th>
                <th v-if="isManagerCanSee()" class="border px-2">Замена сырья</th>
                <th v-if="isManagerCanSee()" class="border px-2">Замена заказа</th>
                <th v-if="isManagerCanSee()" class="border px-2">Мойка машины</th>
                <th v-if="isManagerCanSee()" class="border px-2">Приправка</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие людей</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие работы</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие сырья</th>
                <th v-if="isManagerCanSee()" class="border px-2">Разница тиража</th>
                <th v-if="isManagerCanSee()" class="border px-2">Приправка принята</th>
                <th v-if="isManagerCanSee()" class="border px-2">Примечание</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, index) in tasks"
                :key="task.id"
                :class="{ 'bg-slate-100/40': index % 2 === 1,
                 'bg-yellow-50' : task.is_idle,
                 'bg-red-200/50' : Boolean(task.prepare_ok) === false && task.prepare_ok != null}">
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
                                <DropdownLink :href="`/extrusion/edit/${task.id}`">
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
                <td v-if="isManagerCanSee()" class="border" title="Машинист-1">{{ task.machinist1_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Машинист-2">{{ task.machinist2_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Машинист-3">{{ task.machinist3_id }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Ученик">{{ task.student_id }}</td>
                <td class="border" title="Номер карты">{{ task.tkn }}</td>
                <td class="border" title="Номер рецептуры">{{ task.recipe_number }}</td>
                <td class="border" title="Номер партии">{{ task.part_number }}</td>
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
                <td  v-if="isManagerCanSee()" class="border" title="Тираж кг">{{ task.circulation_kg }}</td>
                <td  v-if="isManagerCanSee()" class="border" title="Тираж пог м">{{ task.circulation_length }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Тип продукта">{{ task.product_type }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Ширина">{{ task.width }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина">{{ task.thickness }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Поле активации">{{ task.activation_pole }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Кромка">{{ task.edge }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Кол-во потоков">{{ task.streams }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Масса рулона">{{ task.roll_mass }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Длина рулона">{{ task.roll_length }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Диаметр рулона">{{ task.roll_diameter }}</td>

                <td class="border bg-yellow-200/50" title="Выработка кг">{{ task.workout_mass }}</td>
                <td class="border bg-yellow-200/50" title="Выработка м.пог">{{ task.workout_length }}</td>
                <td class="border" title="Выработка м2">{{ task.workout_m2 }}</td>
                <td class="border" title="Выработка ОТК">{{ task.workout_otk }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Отходы план рулон">{{ task.waste_plan_roll }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы план кромка">{{ task.waste_plan_edge }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы план слитки">{{ task.waste_plan_ingets }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы рулон">{{ task.waste_roll }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы переходные">{{ task.waste_trans }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы кромка">{{ task.waste_edge }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы слитки">{{ task.waste_ingets }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы срезы">{{ task.waste_slice }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы итог">{{ task.waste_sum }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Простой электрика">{{ task.electro }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Простой механика">{{ task.mechanical }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Простой электроника">{{ task.electronics }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тех.обслуживание">{{ task.tech_service }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена сеток">{{ task.change_net }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена сырья">{{ task.change_raw }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена заказа">{{ task.change_order }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Мойка машины">{{ task.clean_machine }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Приправка">{{ task.prepare_hours }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие людей">{{ task.no_human }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие работы">{{ task.no_work }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие сырья">{{ task.no_raw }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Разница тиража">{{ task.diff_circulation }}</td>
                <td v-if="isManagerCanSee()" v-html="isOkSvgShow(task.prepare_ok)" class="border" title="Приправка принята"></td>
                <td v-if="isManagerCanSee()" class="border italic" title="Примечание">{{ task.notes }}</td>
            </tr>
            <tr class="bg-gray-100 text-gray-600">
                <td v-if="isUserCanChange()" class="font-bold">ИТОГО</td>
                <td class="border sticky left-0 z-10 bg-inherit" title="Машина"></td>
                <td class="border" title="Дата смены"></td>
                <td class="border text-center" title="Смена"></td>
                <td v-if="isManagerCanSee()" class="border" title="Мастер"></td>
                <td v-if="isManagerCanSee()" class="border" title="Машинист-1"></td>
                <td v-if="isManagerCanSee()" class="border" title="Машинист-2"></td>
                <td v-if="isManagerCanSee()" class="border" title="Машинист-3"></td>
                <td v-if="isManagerCanSee()" class="border" title="Ученик"></td>
                <td class="border text-sm" title="Номер карты">Карт: {{ uniqueCardCount(tasks, 'tkn') }}</td>
                <td class="border text-sm" title="Номер рецептуры"></td>
                <td class="border text-sm" title="Номер партии"></td>
                <td v-if="isManagerCanSee()" class="border" title="Плановое время"></td>
                <td v-if="isManagerCanSee()" class="border" title="Начало работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Окончание работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Фактическое время"></td>
                <td class="border" title="Заказчик"></td>
                <td class="border" title="Заказ"></td>
                <td  v-if="isManagerCanSee()" class="border" title="Тираж кг"></td>
                <td  v-if="isManagerCanSee()" class="border" title="Тираж м.пог"></td>

                <td v-if="isManagerCanSee()" class="border" title="Тип продукта"></td>
                <td v-if="isManagerCanSee()" class="border" title="Ширина">{{ averaging(tasks, 'width') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина">{{ averaging(tasks, 'thickness') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Поле активации"></td>
                <td v-if="isManagerCanSee()" class="border" title="Кромка"></td>
                <td v-if="isManagerCanSee()" class="border" title="Кол-во потоков"></td>

                <td v-if="isManagerCanSee()" class="border" title="Масса рулона"></td>
                <td v-if="isManagerCanSee()" class="border" title="Длина рулона"></td>
                <td v-if="isManagerCanSee()" class="border" title="Диаметр рулона"></td>

                <td class="border bg-yellow-200/50" title="Выработка кг">{{ summarize(tasks, 'workout_mass') }}</td>
                <td class="border bg-yellow-200/50" title="Выработка пог.м.">{{ summarize(tasks, 'workout_length') }}</td>
                <td class="border" title="Выработка м2">{{ summarize(tasks, 'workout_m2') }}</td>
                <td class="border" title="Выработка ОТК">{{ summarize(tasks, 'workout_otk') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Отходы план рулон"></td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы план кромка"></td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы план слитки"></td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы рулон">{{ summarize(tasks, 'waste_roll') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы переходные">{{ summarize(tasks, 'waste_trans') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы кромка">{{ summarize(tasks, 'waste_edge') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы слитки">{{ summarize(tasks, 'waste_ingets') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы срезы">{{ summarize(tasks, 'waste_slice') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы итог">{{ summarize(tasks, 'waste_sum') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Простой электрика">{{ summarize(tasks, 'electro') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Простой механика">{{ summarize(tasks, 'mechanical') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Простой электроника">{{ summarize(tasks, 'electronics') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Тех обслуживание">{{ summarize(tasks, 'tech_service') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена сеток">{{ summarize(tasks, 'change_net') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена сырья">{{ summarize(tasks, 'change_raw') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Замена заказа">{{ summarize(tasks, 'change_order') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Мойка машины">{{ summarize(tasks, 'clean_machine') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Приправка">{{ summarize(tasks, 'prepare_hours') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Отсутствие людей">{{ summarize(tasks, 'no_human') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие работы">{{ summarize(tasks, 'no_work') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие сырья">{{ summarize(tasks, 'no_raw') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Разница тиража"></td>
                <td v-if="isManagerCanSee()" class="border" title="Разница тиража"></td>
                <td v-if="isManagerCanSee()" class="border" title="Примечание"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div v-else-if="props.tasks && !tasks.length" class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи экструзии по заданным параметрам.</p>
    </div>
    <div v-else class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <p >Для выбора всех машин экструзии оставьте поле "Машина" пустым</p>
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
