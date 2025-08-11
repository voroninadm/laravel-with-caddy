<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import {normalizeDate} from '@/common/dateTimeHelper.js';
import {ref} from "vue";
import PrintTableButton from '@/components/buttons/PrintTableButton.vue';


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

const calculateTechOpps = (task) => {
    let calc = (
        task.change_teflon +
        task.reconfig +
        task.calibrating
    );
    return calc !== 0 ? calc.toFixed(2) : ''
};

const calculateIdles = (task) => {
    let calc = (
        task.no_human +
        task.no_work +
        task.no_raw +
        task.electro +
        task.mechanical +
        task.tech_service
    );
    return calc !== 0 ? calc.toFixed(2) : ''
};

const calculateWorkout = (task) => {
    let calc = (
        task.workout_count +
        task.workout_otk
    );
    return calc !== 0 ? calc.toFixed(2) : ''
};

const isShowTableColumn = (taskProperty) => {
    return props.tasks && props.tasks.some(task => !!task[taskProperty]);
}

// быстрая печать таблицы
const printableTable = ref(null);
const printTable = () => {
    const tableContent = printableTable.value.outerHTML;
    const printWindow = window.open('', '_blank');

    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<style>.printable-table { border-collapse: collapse; } .printable-table th, .printable-table td { border: 2px solid black; padding: 8px; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(tableContent);
    printWindow.document.write('</body></html>');

    printWindow.print();
    printWindow.close();
};
</script>

<template>
    <MachinesReportFilter :header="'Выработка сварка'"
                          :machines="machines"
                          :workers="workers"
                          :routeToSubmit="'welding.workout'"
                          :auth="props.auth"
                          :is-excel-button-disabled="props.tasks === null"
                          :excel-path-to-convert="'/welding/workout'"
    />

    <div v-if="props.tasks && tasks.length > 0" class="flex flex-col mt-4 h-full overflow-auto text-center">
        <table class="mx-4 mb-20 printable-table" ref="printableTable">
            <thead>
            <tr class="text-sm bg-gray-100">
                <th class="border px-2">Машина</th>
                <th class="border px-6">Дата смены</th>
                <th class="border px-2">Cмена</th>
                <th class="border px-2">Оператор-1</th>
                <th class="border px-2">Оператор-2</th>
                <th v-show="isShowTableColumn('operator3_id')" class="border px-2">Оператор-3</th>
                <th v-show="isShowTableColumn('operator4_id')" class="border px-2">Оператор-4</th>
                <th v-show="isShowTableColumn('operator5_id')" class="border px-2">Оператор-5</th>
                <th class="border px-2">Приёмщик</th>
                <th class="border px-2">Номер карты</th>
                <th class="border px-2">Материал</th>
                <th class="border px-2">Толщина</th>
                <th v-show="isShowTableColumn('mat2_id')" class="border px-2">Материал-2</th>
                <th v-show="isShowTableColumn('thickness2')" class="border px-2">Толщина-2</th>
                <th v-show="isShowTableColumn('mat3_id')" class="border px-2">Материал-3</th>
                <th v-show="isShowTableColumn('thickness3')" class="border px-2">Толщина-3</th>
                <th class="border px-2">Ширина</th>
                <th class="border px-2">Длина</th>
                <th class="border px-2">Тип дна</th>
                <th v-show="isShowTableColumn('is_pocket')" class="border px-2">Карман</th>
                <th v-show="isShowTableColumn('is_handle')" class="border px-2">Ручка</th>
                <th v-show="isShowTableColumn('is_edge')" class="border px-2">Кромка</th>
                <th v-show="isShowTableColumn('is_perforation')" class="border px-2">Перфорация</th>
                <th v-show="isShowTableColumn('is_ziplock')" class="border px-2">Zip lock</th>
                <th class="border px-2">Выработка (тыс.шт)</th>
                <th class="border px-2">Тех операции</th>
                <th class="border px-2">Простои</th>
                <th class="border px-2">Примечание</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, index) in tasks"
                :key="task.id"
                :class="{ 'bg-slate-100/40': index % 2 === 0, 'bg-yellow-50' : task.is_idle }">
                <td class="border px-2" title="Машина">{{ task.machine_id }}</td>
                <td class="border px-2" title="Дата смены">{{ normalizeDate(task.work_date) }}</td>
                <td class="border text-center" title="Смена">{{ task.work_shift }}</td>
                <td class="border" title="Оператор-1">{{ task.operator1_id }}</td>
                <td class="border" title="Оператор-2">{{ task.operator2_id }}</td>
                <td v-show="isShowTableColumn('operator3_id')" class="border" title="Оператор-3">{{
                        task.operator3_id
                    }}
                </td>
                <td v-show="isShowTableColumn('operator4_id')" class="border" title="Оператор-4">{{
                        task.operator4_id
                    }}
                </td>
                <td v-show="isShowTableColumn('operator5_id')" class="border" title="Оператор-5">{{
                        task.operator5_id
                    }}
                </td>
                <td class="border" title="Приёмщик">{{ task.acceptor_id }}</td>
                <td class="border" title="Номер карты">{{ task.tkn }}</td>
                <td class="border" title="Материал">{{ task.mat1_id }}</td>
                <td class="border" title="Толщина">{{ task.thickness1 }}</td>
                <td v-show="isShowTableColumn('mat2_id')" class="border" title="Материал-2">{{ task.mat2_id }}</td>
                <td v-show="isShowTableColumn('thickness2')" class="border" title="Толщина-2">{{ task.thickness2 }}</td>
                <td v-show="isShowTableColumn('mat3_id')" class="border" title="Материал-3">{{ task.mat3_id }}</td>
                <td v-show="isShowTableColumn('thickness3')" class="border" title="Толщина-3">{{ task.thickness3 }}</td>
                <td class="border" title="Ширина">{{ task.product_width }}</td>
                <td class="border" title="Длина">{{ task.length }}</td>
                <td class="border" title="Тип дна">{{ task.bottom_type }}</td>
                <td v-show="isShowTableColumn('is_pocket')" class="border" title="Карман">{{
                        task.is_pocket ? "Да" : ''
                    }}
                </td>
                <td v-show="isShowTableColumn('is_handle')" class="border" title="Ручка">{{
                        task.is_handle ? "Да" : ''
                    }}
                </td>
                <td v-show="isShowTableColumn('is_edge')" class="border" title="Кромка">{{
                        task.is_edge ? "Да" : ''
                    }}
                </td>
                <td v-show="isShowTableColumn('is_perforation')" class="border" title="Перфорация">
                    {{ task.is_perforation ? "Да" : '' }}
                </td>
                <td v-show="isShowTableColumn('is_ziplock')" class="border" title="Zip lock">
                    {{ task.is_ziplock ? "Да" : '' }}
                </td>
                <td class="border" title="Выработка тыс.шт">{{ calculateWorkout(task) }}</td>
                <td class="border" title="Тех операции">{{ calculateTechOpps(task) }}</td>
                <td class="border" title="Простои">{{ calculateIdles(task) }}</td>
                <td class="border" title="Примечание">{{ task.notes }}</td>
            </tr>
            </tbody>
        </table>
        <PrintTableButton @click="printTable"/>
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

</template>
