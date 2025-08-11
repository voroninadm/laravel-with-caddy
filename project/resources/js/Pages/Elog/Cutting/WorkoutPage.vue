<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import {normalizeDate} from '@/common/dateTimeHelper.js';
import PrintTableButton from '@/components/buttons/PrintTableButton.vue';
import {ref} from "vue";


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
    let calc =  (
        task.electro +
        task.mechanical +
        task.tech_service +
        task.knifes_barbell
    );
    return calc !== 0 ? calc.toFixed(2) : ''
};

const calculatePrepare = (task) => {
    let calc =  (
        task.prepare +
        task.prepare_shirt
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
        :header="'Выработка резка'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'cutting.workout'"
        :auth="props.auth"
        :is-excel-button-disabled="props.tasks === null"
        :excel-path-to-convert="'/cutting/workout'"

    />

    <div v-if="props.tasks && tasks.length > 0" class="flex flex-col mt-4 h-full overflow-auto text-center">
        <table class="mx-4 mb-20 printable-table" ref="printableTable">
            <thead>
            <tr class="text-sm bg-gray-100">
                <th class="border px-2">Машина</th>
                <th class="border px-6">Дата смены</th>
                <th class="border px-2">Cмена</th>
                <th class="border px-2">Оператор</th>
                <th class="border px-2">Упаковщик</th>
                <th class="border px-2">Заказчик</th>
                <th class="border px-2">Материал-1</th>
                <th class="border px-2">Материал-2</th>
                <th class="border px-2">Материал-3</th>
                <th class="border px-2">Количество ручьев</th>
                <th class="border px-2">Выработка пог.м</th>
                <th class="border px-2">Выработка кг</th>
                <th class="border px-2">Перестройка</th>
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
                <td class="border" title="Оператор">{{ task.operator_id }}</td>
                <td class="border" title="Упаковщик">{{ task.packer_id }}</td>
                <td class="border" title="Заказчик">{{ task.customer }}</td>
                <td class="border" title="Материал1">{{ task.mat1_id }}</td>
                <td class="border" title="Материал2">{{ task.mat2_id }}</td>
                <td class="border" title="Материал3">{{ task.mat3_id }}</td>
                <td class="border" title="Количество ручьев">{{ task.streams }}</td>
                <td class="border" title="Выработка пог.м">{{ task.workout_length }}</td>
                <td class="border" title="Выработка кг">{{ task.workout_mass }}</td>
                <td class="border" title="Перестройка">{{ task.reconfig }}</td>
                <td class="border" title="Тех операции">{{calculateTechOpps(task)}}</td>
                <td class="border italic" title="Примечание">{{ task.notes }}</td>
            </tr>
            </tbody>
            <PrintTableButton @click="printTable"/>
        </table>
    </div>
    <div v-else-if="props.tasks && !tasks.length" class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи резки по заданным параметрам.</p>
    </div>
    <div v-else class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <p >Для выбора всех машин резки оставьте поле "Машина" пустым</p>
    </div>

</template>
