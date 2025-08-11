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
        task.correction_PN +
        task.correction_CMYK +
        task.electro +
        task.mechanical +
        task.tech_service +
        task.form_glue +
        task.service_replacement
    );
    return calc !== 0 ? calc.toFixed(2) : ''
};

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
    <MachinesReportFilter
        :header="'Выработка печать'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'printing.workout'"
        :auth="props.auth"
        :is-excel-button-disabled="props.tasks === null"
    />

    <div v-if="props.tasks && tasks.length > 0" class="flex flex-col mt-4 h-full overflow-auto text-center">
        <table class="mx-4 mb-20 printable-table" ref="printableTable">
            <thead>
            <tr class="text-sm bg-gray-100">
                <th class="border px-2">Машина</th>
                <th class="border px-6">Дата смены</th>
                <th class="border px-2">Cмена</th>
                <th class="border px-2">Операторы</th>
                <th class="border px-2">Номер карты</th>
                <th class="border px-2">Материал</th>
                <th class="border px-2">Ширина</th>
                <th class="border px-2">Толщина</th>
                <th class="border px-2">Краски</th>
                <th class="border px-2">Выработка кг</th>
                <th class="border px-2">Выработка м.пог</th>
                <th class="border px-2">Выработка м<sup>2</sup></th>
                <th class="border px-2">Приправка час</th>
                <th class="border px-2">Тех операции</th>
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
                <td class="border" title="Оператор-1">
                    {{ [task.operator1_id, task.operator2_id, task.operator3_id].filter(Boolean).join(', ') }}
                </td>
                <td class="border" title="Номер карты">{{ task.tkn }}</td>
                <td class="border" title="Материал">{{ task.mat1_id }}</td>
                <td class="border" title="Ширина">{{ task.width1 }}</td>
                <td class="border" title="Толщина">{{ task.thickness1 }}</td>
                <td class="border" title="Краски">{{ task.colors }}</td>
                <td class="border" title="Выработка кг2">{{ task.workout_mass }}</td>
                <td class="border" title="Выработка м.пог">{{ task.workout_length }}</td>
                <td class="border" title="Выработка м2">{{ task.workout_m2 }}</td>
                <td class="border" title="Приправка час">{{ task.prepare_hours }}</td>
                <td class="border" title="Тех операции">{{ calculateTechOpps(task) }}</td>
                <td class="border italic" title="Примечание">{{ task.notes }}</td>
            </tr>
            </tbody>
        </table>
        <PrintTableButton @click="printTable"/>
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

</template>
