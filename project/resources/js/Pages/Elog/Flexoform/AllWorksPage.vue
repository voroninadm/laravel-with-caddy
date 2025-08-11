<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import {
    normalizeDate,
    normalizeDatetime
} from '@/common/dateTimeHelper.js';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import Modal from '@/components/Modal.vue';
import {ref} from "vue";
import DangerButton from '@/components/DangerButton.vue';
import {router} from "@inertiajs/vue3";
import RollLiteralDown from "@/components/icons/RollLiteralDown.vue";
import RollLiteralUp from "@/components/icons/RollLiteralUp.vue";
import RollLiteralLeft from "@/components/icons/RollLiteralLeft.vue";
import RollLiteralRight from "@/components/icons/RollLiteralRight.vue";
import {isOkSvgShow, summarize, uniqueCardCount} from "@/common/elogTaskHelper.js";


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
    router.delete(`/flexoform/destroy/${taskToDelete.value}`);
    confirmingTaskDeletion.value = false;
}

//=== rules
const isUserCanEditDelete = (taskWorkDate) => {
    if (props.auth.permissions.includes('medium_upff_permission')) {
        const currentDateTime = new Date();
        const taskDateTime = new Date(taskWorkDate);

        const timeDifference = currentDateTime - taskDateTime;
        const hoursDifference = timeDifference / (1000 * 60 * 60);
        return hoursDifference <= 48;
    } else if (props.auth.permissions.includes('full_upff_permission')) {
        return true
    }
}

// выдаем svg для направления печати (монтаж флексоформ)
const showUnwindSvg = (unwind_position) => {
    switch (unwind_position) {
        case 'flow':
            return RollLiteralDown;
        case 'against_flow':
            return RollLiteralUp;
        case 'flow_left':
            return RollLiteralLeft;
        case 'flow_right':
            return RollLiteralRight;
    }
}

/**
 * Для отчета менеджера - в таблице отражаются только некоторые строки
 * @returns {boolean}
 */
const isManagerCanSee = () => {
    return !props.auth.role.includes('manager')
};

const isUserCanChange = () => {
    return props.auth.permissions.includes('medium_upff_permission') || props.auth.permissions.includes('full_upff_permission')
};
</script>

<template>
    <MachinesReportFilter
        :header="'Все работы по монтажу флексоформ'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'flexoform.allWorks'"
        :localStorageKey="'flexoformAllWorks'"
        :auth="props.auth"
        :is-excel-button-disabled="props.tasks === null"
        :excel-path-to-convert="'/flexoform/allWorks'"
    />

    <div v-if="props.tasks && tasks.length > 0" class="flex flex-col mt-4 h-full overflow-auto text-center">
        <table class="mx-4 mb-20">
            <thead>
            <tr class="text-sm bg-gray-100 sticky top-0 z-20">
                <th v-if="isUserCanChange()" class="border"></th>
                <th class="border px-2">Машина</th>
                <th class="border px-6">Дата смены</th>
                <th class="border px-2">Cмена</th>
                <th v-if="isManagerCanSee()" class="border px-2">Мастер</th>
                <th v-if="isManagerCanSee()" class="border px-2">Сборщик</th>
                <th class="border px-2">Номер карты</th>
                <th v-if="isManagerCanSee()" class="border px-2">Начало работ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Окончание работ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Фактическое время</th>
                <th class="border px-2">Заказчик</th>
                <th class="border px-2">Заказ</th>

                <th class="border px-2">Номер дизайна</th>
                <th class="border px-2">Кол-во ручьев</th>
                <th v-if="isManagerCanSee()" class="border px-2">Количество красок по заказу</th>
                <th class="border px-2">Диаметр сливс</th>
                <th class="border px-2">Количество сливс, факт</th>
                <th v-if="isManagerCanSee()" class="border px-2">Новые формы</th>
                <th v-if="isManagerCanSee()" class="border px-2">Чистка анилоксов, шт</th>
                <th v-if="isManagerCanSee()" class="border px-2">Краски и липкая лента</th>
                <th class="border px-2">Печатная машина</th>
                <th class="border px-2">Направление печати</th>
                <th v-if="isManagerCanSee()" class="border px-2">Расстояние между ручьями проверено</th>
                <th v-if="isManagerCanSee()" class="border px-2">Проверка монтажных точек проведена</th>
                <th v-if="isManagerCanSee()" class="border px-2">Примечание</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, index) in tasks"
                :key="task.id"
                :class="{ 'bg-slate-100/40': index % 2 === 1,
                 'bg-yellow-50' : task.is_idle,
                  'bg-red-100/50' : Boolean(task.prepare_ok) === false && task.prepare_ok != null }">
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
                                <DropdownLink :href="`/flexoform/edit/${task.id}`">
                                    Изменить
                                </DropdownLink>
                                <button
                                    class=" block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="confirmingTaskDeletion = !confirmingTaskDeletion; taskToDelete = task.id">
                                    Удалить
                                </button>
                            </div>
                            <div v-else class="text-sm p-3 text-center">
                                <p class="pb-2">Недостаточно прав или с момента создания задачи прошло более 48
                                    часов.</p>
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
                <td v-if="isManagerCanSee()" class="border" title="Сборщик">{{ task.collector_id }}</td>
                <td class="border" title="Номер карты">{{ task.tkn }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Начало работ">
                    {{ task.work_start ? normalizeDatetime(task.work_start) : '' }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Окончание работ">
                    {{ task.work_finish ? normalizeDatetime(task.work_finish) : '' }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Фактическое время">{{ task.work_fact }}</td>
                <td class="border" title="Заказчик">{{ task.customer }}</td>
                <td class="border" title="Заказ">{{ task.print_title }}</td>

                <td class="border" title="Номер дизайна">{{ task.design_number }}</td>
                <td class="border" title="Количество ручьев">{{ task.streams }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Краски по карте">{{ task.paints_count }}</td>
                <td class="border" title="Диаметр сливс">{{ task.sleeves_diameter }}</td>
                <td class="border" title="Сливс, шт">{{ task.sleeves_fact }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Новые формы">{{ task.new_forms }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Чистка анилоксов">{{ task.aniloks }}</td>
                <td v-if="isManagerCanSee()" class="border w-max" title="Краски и липкая лента">
                    <p v-for="data in task.paints_and_sticky" class="w-max">
                        - <span class="underline decoration-dotted ">{{ data.paints }} </span> на липкой
                        {{ data.sticky_brand }}, {{ data.sticky_name }}({{ data.sticky_thickness }})
                    </p>
                </td>
                <td class="border" title="Печатная машина">{{ task.print_machine_id }}</td>
                <td class="border" title="Направление печати">
                    <component
                        :is="showUnwindSvg(task.unwind_direction)"
                        :class="'w-14 m-auto'"
                    />
                </td>
                <td v-if="isManagerCanSee()" v-html="isOkSvgShow(task.is_streams_distance_ok)" class="border"
                    title="Факт покрытия"></td>
                <td v-if="isManagerCanSee()" v-html="isOkSvgShow(task.is_mounting_dots_ok)" class="border"
                    title="Ширина покрытия"></td>
                <td v-if="isManagerCanSee()" class="border italic" title="Примечание">{{ task.notes }}</td>
            </tr>
            <tr class="bg-gray-100 text-gray-600">
                <td v-if="isUserCanChange()" class="font-bold">ИТОГО</td>
                <td class="border sticky left-0 z-10 bg-inherit" title="Машина"></td>
                <td class="border" title="Дата смены"></td>
                <td class="border text-center" title="Смена"></td>
                <td v-if="isManagerCanSee()" class="border" title="Мастер"></td>
                <td v-if="isManagerCanSee()" class="border" title="Сборщик"></td>
                <td class="border" title="Номер карты">Карт: {{ uniqueCardCount(tasks, 'tkn') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Начало работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Окончание работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Фактическое время"></td>
                <td class="border" title="Заказчик"></td>
                <td class="border" title="Заказ"></td>

                <td class="border" title="Номер дизайна"></td>
                <td class="border" title="Количество ручьев"></td>
                <td v-if="isManagerCanSee()" class="border" title="Количество красок по заказу"></td>
                <td class="border" title="Диаметр сливс"></td>
                <td class="border" title="Количество сливс, факт">
                    {{ summarize(tasks, 'sleeves_fact') }}
                </td>
                <td class="border" title="Краски"></td>
                <td v-if="isManagerCanSee()" class="border" title="Новые формы"></td>
                <td v-if="isManagerCanSee()" class="border" title="Чистка анилоксов, шт">{{
                        summarize(tasks, 'aniloks')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Производитель липкой ленты"></td>
                <td v-if="isManagerCanSee()" class="border" title="Марка липкой ленты"></td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина липкой ленты"></td>
                <td class="border" title="Печатная машина"></td>
                <td class="border" title="Направление печати"></td>
                <td v-if="isManagerCanSee()" class="border" title="Расстояние между ручьями проверено"></td>
                <td v-if="isManagerCanSee()" class="border" title="Проверка монтажных точек проведена"></td>
                <td v-if="isManagerCanSee()" class="border" title="Примечание"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div v-else-if="props.tasks && !tasks.length"
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи монтажа флексоформ по заданным параметрам.</p>
    </div>
    <div v-else
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <p>Для выбора всех монтажных машин оставьте поле "Машина" пустым</p>
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
