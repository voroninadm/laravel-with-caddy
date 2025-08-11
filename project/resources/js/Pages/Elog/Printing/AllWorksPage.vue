<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import {normalizeDate, normalizeDatetime} from '@/common/dateTimeHelper.js';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import Modal from '@/components/Modal.vue';
import {ref} from "vue";
import DangerButton from '@/components/DangerButton.vue';
import {router} from "@inertiajs/vue3";
import ElogCardIsOpen from "@/components/icons/ElogCardIsOpen.vue";
import OpenTaskModalIcon from "@/components/icons/OpenTaskModalIcon.vue";
import {averaging, summarize, uniqueCardCount} from "@/common/elogTaskHelper.js";
import {useAuth} from "@/common/useElogAuthComposable.js";


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

const {isManagerCanSee, isUserCanEditDelete, isUserCanChangeTask} = useAuth(props);

const confirmingTaskDeletion = ref(false);
const taskToDelete = ref(null);
const destroyTask = () => {
    router.delete(route('printing.destroy', taskToDelete.value));
    confirmingTaskDeletion.value = false;
}
</script>

<template>
    <MachinesReportFilter
        :header="'Вся печать'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'printing.allWorks'"
        :localStorageKey="'printingAllWorks'"
        :auth="props.auth"
        :is-excel-button-disabled="props.tasks === null"
    />

    <div v-if="props.tasks && tasks.length > 0" class="flex flex-col mt-4 h-full overflow-auto text-center">
        <table class="mx-4 mb-20">
            <thead>
            <tr class="text-sm bg-gray-100 sticky top-0 z-20 opacity-100">
                <th v-if="isUserCanChangeTask('printing')" class="border"></th>
                <th class="border px-2 min-w-40">Машина</th>
                <th class="border px-6">Дата смены</th>
                <th class="border px-2">Cмена</th>
                <th v-if="isManagerCanSee()" class="border px-2">Мастер</th>
                <th v-if="isManagerCanSee()" class="border px-2">Операторы</th>
                <th v-if="isManagerCanSee()" class="border px-2">Помощник</th>
                <th class="border px-2">Номер карты</th>
                <th v-if="isManagerCanSee()" class="border px-2">Плановое время</th>
                <th v-if="isManagerCanSee()" class="border px-2">Начало работ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Окончание работ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Фактическое время</th>
                <th v-if="isManagerCanSee()" class="border px-2">Время выпуска</th>
                <th class="border px-2 min-w-50">Заказчик</th>
                <th class="border px-2 min-w-70">Заказ</th>
                <th v-if="isManagerCanSee()" class="border px-2">Тираж</th>
                <th v-if="isManagerCanSee()" class="border px-2">Материал</th>
                <th v-if="isManagerCanSee()" class="border px-2">План материала</th>
                <th v-if="isManagerCanSee()" class="border px-2">Факт материала</th>
                <th v-if="isManagerCanSee()" class="border px-2">Ширина</th>
                <th v-if="isManagerCanSee()" class="border px-2">Толщина</th>
                <th v-if="isManagerCanSee()" class="border px-2">Краски</th>
                <th class="border px-2">Выработка кг</th>
                <th class="border px-2">Выработка м.пог</th>
                <th class="border px-2">Выработка м<sup>2</sup></th>
                <th class="border px-2">Выработка ОТК</th>
                <th v-if="isManagerCanSee()" class="border px-2">Разница тиража</th>
                <th v-if="isManagerCanSee()" class="border px-2">Скорость печати</th>

                <th v-if="isManagerCanSee()" class="border px-2">Отходы план</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы печать</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы сырье</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отходы итог</th>
                <th v-if="isManagerCanSee()" class="border px-2">Приправка кг</th>
                <th v-if="isManagerCanSee()" class="border px-2">Приправка час</th>
                <th v-if="isManagerCanSee()" class="border px-2">Коррекция PN</th>
                <th v-if="isManagerCanSee()" class="border px-2">Коррекция CMYK</th>
                <th v-if="isManagerCanSee()" class="border px-2">Простой электрика</th>
                <th v-if="isManagerCanSee()" class="border px-2">Простой механика</th>
                <th v-if="isManagerCanSee()" class="border px-2">Переклейка форм</th>
                <th v-if="isManagerCanSee()" class="border px-2">Замена расходников</th>
                <th v-if="isManagerCanSee()" class="border px-2">Техническое обслуживание</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие людей</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие работы</th>
                <th v-if="isManagerCanSee()" class="border px-2">Отсутствие сырья</th>
                <th v-if="isManagerCanSee()" class="border px-2">Краткосрочный простой</th>
                <th v-if="isManagerCanSee()" class="border px-2">Простой без причины</th>
                <th v-if="isManagerCanSee()" class="border px-2">Общий простой</th>
                <th v-if="isManagerCanSee()" class="border px-2 min-w-150">Примечание</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, index) in tasks"
                :key="task.id"
                :class="{ 'bg-slate-100/40': index % 2 === 1, 'bg-yellow-50' : task.is_idle }">
                <td v-if="isUserCanChangeTask('printing')" class="border">
                    <Dropdown :open="false">
                        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700  focus:outline-hidden transition ease-in-out duration-150">
                                            <OpenTaskModalIcon
                                                class="w-6 h-6 hover:scale-105 hover:opacity-100 duration-200 opacity-40"/>
                                            </button>
                                        </span>
                        </template>

                        <template #content>
                            <div v-if="isUserCanEditDelete(task.created_at, 'printing')">
                                <DropdownLink :href="route('printing.edit', task.id)">
                                    Изменить
                                </DropdownLink>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="confirmingTaskDeletion = !confirmingTaskDeletion; taskToDelete = task.id">
                                    Удалить
                                </button>
                            </div>
                            <div v-else class="text-sm p-3 text-center">
                                <p class="pb-2">Недостаточно прав или с момента создания задачи прошло более 48
                                    часов.</p>
                                <p>Для изменения или удаления обратитесь к
                                    администратору участка печати</p>
                            </div>
                        </template>
                    </Dropdown>
                </td>
                <td class="border px-1 sticky left-0 z-10 bg-slate-50" title="Машина">
                    {{ task.machine_id }}
                    <span v-if="task.is_complete === 0">
                    <ElogCardIsOpen class="w-8 h-8 m-auto -mt-1"></ElogCardIsOpen>
                </span>
                </td>
                <td class="border px-1" title="Дата смены">{{ normalizeDate(task.work_date) }}</td>
                <td class="border px-1 text-center" title="Смена">{{ task.work_shift }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Мастер">{{ task.master_id }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Операторы">
                    {{ [task.operator1_id, task.operator2_id, task.operator3_id].filter(Boolean).join(', ') }}
                </td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Помощник">{{ task.operator_helper }}</td>
                <td class="border px-1" title="Номер карты">{{ task.tkn }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Плановое время">
                    {{ task.work_plan ? task.work_plan.toFixed(2) : '' }}
                </td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Начало работ">
                    {{ task.work_start ? normalizeDatetime(task.work_start) : '' }}
                </td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Окончание работ">
                    {{ task.work_finish ? normalizeDatetime(task.work_finish) : '' }}
                </td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Фактическое время">{{ task.work_fact }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Время выпуска">{{ task.work_productive }}</td>
                <td class="border px-1" title="Заказчик">{{ task.customer }}</td>
                <td class="border px-1" title="Заказ">{{ task.print_title }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Тираж">{{ task.circulation }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Материал">{{ task.mat1_id }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="План материала">{{ task.mat1count_plan }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Факт материала">{{ task.mat1count }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Ширина">{{ task.width1 }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Толщина">{{ task.thickness1 }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Краски">{{ task.colors }}</td>
                <td class="border px-1 bg-yellow-200/50" title="Выработка кг">{{ task.workout_mass }}</td>
                <td class="border px-1 bg-yellow-200/50" title="Выработка м.пог">{{ task.workout_length }}</td>
                <td class="border px-1 bg-yellow-200/50" title="Выработка м2">{{ task.workout_m2 }}</td>
                <td class="border px-1" title="Выработка ОТК">{{ task.otk_mass }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Разница тиража">{{ task.diff_circulation }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Скорость печати">{{ task.speed }}</td>

                <td v-if="isManagerCanSee()" class="border px-1" title="Отходы план">{{ task.waste_plan }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Отходы печать">{{ task.waste_print }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Отходы сырье">{{ task.waste_raw }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Отходы итог">{{ task.waste_sum }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Приправка кг">{{ task.prepare_mass }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Приправка час">{{ task.prepare_hours }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Коррекция PN">{{ task.correction_PN }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Коррекция CMYK">{{ task.correction_CMYK }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Простой электрика">{{ task.electro }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Простой механика">{{ task.mechanical }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Переклейка форм">{{ task.form_glue }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Замена расходников">{{
                        task.service_replacement
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Техническое обслуживание">{{
                        task.tech_service
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Отсутствие людей">{{ task.no_human }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Отсутствие работы">{{ task.no_work }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Отсутствие сырья">{{ task.no_raw }}</td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Краткосрочный простой">{{
                        task.short_downtime
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Простой без причины">{{
                        task.no_reason_downtime
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border px-1" title="Общий простой">{{ task.total_downtime }}</td>
                <td v-if="isManagerCanSee()" class="border px-1 italic" title="Примечание">{{ task.notes }}</td>
            </tr>
            <tr class="bg-gray-100 text-gray-600">
                <td v-if="isUserCanChangeTask('printing')" class="font-bold">ИТОГО</td>
                <td class="border" title="Машина"></td>
                <td class="border" title="Дата смены"></td>
                <td class="border" title="Смена"></td>
                <td v-if="isManagerCanSee()" class="border" title="Мастер"></td>
                <td v-if="isManagerCanSee()" class="border" title="Операторы"></td>
                <td v-if="isManagerCanSee()" class="border" title="Помощник"></td>
                <td class="border" title="Номер карты">Карт: {{ uniqueCardCount(tasks, 'tkn') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Плановое время"></td>
                <td v-if="isManagerCanSee()" class="border" title="Начало работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Окончание работ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Фактическое время">{{
                        summarize(tasks, 'work_fact')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Время выпуска">{{
                        summarize(tasks, 'work_productive')
                    }}
                </td>
                <td class="border" title="Заказчик"></td>
                <td class="border" title="Заказ"></td>
                <td v-if="isManagerCanSee()" class="border" title="Тираж"></td>
                <td v-if="isManagerCanSee()" class="border" title="Материал"></td>
                <td v-if="isManagerCanSee()" class="border" title="План материала"></td>
                <td v-if="isManagerCanSee()" class="border" title="Факт материала">{{
                        summarize(tasks, 'mat1count')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Ширина">{{ averaging(tasks, 'width1') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Толщина">{{ averaging(tasks, 'thickness1') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Краски">{{ averaging(tasks, 'colors') }}</td>
                <td class="border bg-yellow-200/50" title="Выработка кг2">{{ summarize(tasks, 'workout_mass') }}</td>
                <td class="border bg-yellow-200/50" title="Выработка м.пог">{{
                        summarize(tasks, 'workout_length')
                    }}
                </td>
                <td class="border bg-yellow-200/50" title="Выработка м2">{{ summarize(tasks, 'workout_m2') }}</td>
                <td class="border" title="Выработка ОТК">{{ summarize(tasks, 'otk_mass') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Разница тиража"></td>
                <td v-if="isManagerCanSee()" class="border" title="Скорость печати">{{ averaging(tasks, 'speed') }}</td>

                <td v-if="isManagerCanSee()" class="border" title="Отходы план"></td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы печать">{{
                        summarize(tasks, 'waste_print')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы сырье">{{
                        summarize(tasks, 'waste_raw')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Отходы итог">{{ summarize(tasks, 'waste_sum') }}</td>
                <td v-if="isManagerCanSee()" class="border" title="Приправка кг">{{
                        summarize(tasks, 'prepare_mass')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Приправка час">{{
                        summarize(tasks, 'prepare_hours')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Коррекция PN">{{
                        summarize(tasks, 'correction_PN')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Коррекция CMYK">
                    {{ summarize(tasks, 'correction_CMYK') }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Простой электрика">{{
                        summarize(tasks, 'electro')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Простой механика">{{
                        summarize(tasks, 'mechanical')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Переклейка форм">{{
                        summarize(tasks, 'form_glue')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Замена расходников">
                    {{ summarize(tasks, 'service_replacement') }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Техническое обслуживание">
                    {{ summarize(tasks, 'tech_service') }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие людей">{{
                        summarize(tasks, 'no_human')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие работы">{{
                        summarize(tasks, 'no_work')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Отсутствие сырья">{{
                        summarize(tasks, 'no_raw')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Кратковременный простой">
                    {{ summarize(tasks, 'short_downtime') }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Простой без причины">
                    {{ summarize(tasks, 'no_reason_downtime') }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Общий простой">{{
                        summarize(tasks, 'total_downtime')
                    }}
                </td>
                <td v-if="isManagerCanSee()" class="border" title="Примечание"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div v-else-if="props.tasks && !tasks.length"
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи печати по заданным параметрам.</p>
    </div>
    <div v-else
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <p>Для выбора всех машин печати оставьте поле "Машина" пустым</p>
    </div>

    <Modal :show="confirmingTaskDeletion" @close="confirmingTaskDeletion = !confirmingTaskDeletion">
        <div class="p-6 flex flex-col">
            <p class="text-lg text-center font-medium text-gray-900">
                Вы подтверждаете, что не случайно нажали на кнопку удаления?
            </p>

            <DangerButton
                class="flex mt-10 justify-center"
                @click=destroyTask>
                Удаляем
            </DangerButton>
        </div>
    </Modal>
</template>
