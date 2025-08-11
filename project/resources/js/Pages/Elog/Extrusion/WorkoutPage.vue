<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import {normalizeDate, } from '@/common/dateTimeHelper.js';
import PrintTableButton from '@/components/buttons/PrintTableButton.vue';
import {ref} from "vue";
import {summarize} from "@/common/elogTaskHelper.js";


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
        task.change_net +
        task.change_raw +
        task.change_order +
        task.clean_machine
    );
    return calc !== 0 ? calc.toFixed(2) : ''
};


const sumTechOpps = () => {
    let calc = props.tasks.map(task =>
        task.change_net + task.change_raw + task.change_order + task.clean_machine
    ).reduce((acc, curr) => acc + curr, 0);

    return calc !== 0 ? calc.toFixed(2) : '';
};


const calculateRepair = (task) => {
    let calc =  (
        task.electro +
        task.mechanical +
        task.electronics +
        task.tech_service
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
        :header="'Выработка экструзия'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'extrusion.workout'"
        :auth="props.auth"
        :is-excel-button-disabled="props.tasks === null"
        :excel-path-to-convert="'/extrusion/workout'"

    />

    <div v-if="props.tasks && tasks.length > 0" class="flex flex-col mt-4 h-full overflow-auto text-center">
        <table class="mx-4 mb-20 printable-table" ref="printableTable">
            <thead>
            <tr class="text-sm bg-gray-100">
                <th class="border px-2">Машина</th>
                <th class="border px-6">Дата смены</th>
                <th class="border px-2">Cмена</th>
                <th class="border px-2">Машинист-1</th>
                <th class="border px-2">Машинист-2</th>
                <th class="border px-2">Машинист-3</th>
                <th class="border px-2">Номер карты</th>
                <th class="border px-2">Номер партии</th>
                <th class="border px-2">Заказчик</th>
                <th class="border px-2">Ширина</th>
                <th class="border px-2">Толщина</th>
                <th class="border px-2">Выработка кг</th>
                <th class="border px-2">Выработка пог.м</th>
                <th class="border px-2">Выработка м<sup>2</sup></th>
                <th class="border px-2">Приправка, час</th>
                <th class="border px-2">Тех операции</th>
                <th class="border px-2">Ремонт</th>
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
                <td class="border" title="Машинист-1">{{ task.machinist1_id }}</td>
                <td class="border" title="Машинист-2">{{ task.machinist2_id }}</td>
                <td class="border" title="Машинист-3">{{ task.machinist3_id }}</td>
                <td class="border" title="номер карты">{{ task.tkn }}</td>
                <td class="border" title="номер партии">{{ task.part_number }}</td>
                <td class="border" title="Заказчик">{{ task.customer }}</td>
                <td class="border" title="Ширина">{{ task.width }}</td>
                <td class="border" title="Толщина">{{ task.thickness }}</td>
                <td class="border" title="Выработка, кг">{{ task.workout_mass }}</td>
                <td class="border" title="Выработка, пог. м.">{{ task.workout_length }}</td>
                <td class="border" title="Выработка м2">{{ task.workout_m2 }}</td>
                <td class="border" title="Приправка час">{{ task.prepare_hours }}</td>
                <td class="border" title="Тех.операции">{{calculateTechOpps(task)}}</td>
                <td class="border" title="Ремонт">{{calculateRepair(task)}}</td>
                <td class="border italic" title="Примечание">{{ task.notes }}</td>
            </tr>
            <tr class="bg-gray-100 text-gray-600">
                <td class="border px-2" title="Машина"></td>
                <td class="border px-2" title="Дата смены"></td>
                <td class="border text-center" title="Смена"></td>
                <td class="border" title="Машинист-1"></td>
                <td class="border" title="Машинист-2"></td>
                <td class="border" title="Машинист-3"></td>
                <td class="border" title="номер карты"></td>
                <td class="border" title="номер партии"></td>
                <td class="border" title="Заказчик"></td>
                <td class="border" title="Ширина"></td>
                <td class="border" title="Толщина"></td>
                <td class="border" title="Выработка, кг">{{ summarize(tasks , 'workout_mass') }}</td>
                <td class="border" title="Выработка, пог. м.">{{ summarize(tasks , 'workout_length') }}</td>
                <td class="border" title="Выработка м2">{{ summarize(tasks , 'workout_m2') }}</td>
                <td class="border" title="Приправка час">{{ summarize(tasks , 'prepare_hours') }}</td>
                <td class="border" title="Тех.операции">{{ 'Σ:' + sumTechOpps() }}</td>
                <td class="border" title="Ремонт"></td>
                <td class="border italic" title="Примечание"></td>
            </tr>
            </tbody>
            <PrintTableButton @click="printTable"/>
        </table>
    </div>
    <div v-else-if="props.tasks && !tasks.length" class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи экструзии по заданным параметрам.</p>
    </div>
    <div v-else class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <p >Для выбора всех машин экструзии оставьте поле "Машина" пустым</p>
    </div>

</template>
