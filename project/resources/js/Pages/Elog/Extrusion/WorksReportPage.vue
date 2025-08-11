<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';


const props = defineProps({
    auth: Object,
    machines: {
        required: true
    },
    workers: {
        required: true
    },
    data: {
        required: false
    }
})
</script>

<template>
    <MachinesReportFilter
        :header="'Краткий отчет экструзия'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'extrusion.worksReport'"
        :localStorageKey="'extrusionReport'"
        :auth="props.auth"
        :is-excel-button-disabled="props.data.length === 0"
        :excel-path-to-convert="'/extrusion/worksReport'"
        :is-tkn-field-disabled="false"
        :is-worker-field-disabled="true"

    />

    <table v-if="props.data && props.data.length !== 0" class="w-1/3 ml-20 text-left text-sm p-10">
        <thead>
        <tr>
            <th class="px-4 py-2 text-gray-500">Показатель</th>
            <th class="px-4 py-2 text-gray-500">Значение</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td class="border px-4 py-1">Количество уникальных карт</td>
            <td class="border px-4 py-1">{{ data['uniqueTknCount'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Ширина, среднее</td>
            <td class="border px-4 py-1">{{ data['width'] ? data['width'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Толщина, среднее</td>
            <td class="border px-4 py-1">{{ data['thickness'] ? data['thickness'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Выработка, кг</td>
            <td class="border px-4 py-1">{{ data['workout_mass'] ? data['workout_mass'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Выработка, погонные метры</td>
            <td class="border px-4 py-1">{{ data['workout_length'] ? data['workout_length'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Выработка, м<sup>2</sup></td>
            <td class="border px-4 py-1">{{ data['workout_m2'] ? data['workout_m2'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Выработка на ОТК</td>
            <td class="border px-4 py-1">{{ data['workout_otk'] ? data['workout_otk'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы рулонные</td>
            <td class="border px-4 py-1">{{ data['waste_roll'] ? data['waste_roll'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы переходные</td>
            <td class="border px-4 py-1">{{ data['waste_trans'] ? data['waste_trans'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы кромка</td>
            <td class="border px-4 py-1">{{ data['waste_edge'] ? data['waste_edge'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы слитки</td>
            <td class="border px-4 py-1">{{ data['waste_ingets'] ? data['waste_ingets'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы срезы</td>
            <td class="border px-4 py-1">{{ data['waste_slice'] ? data['waste_slice'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы итоговые</td>
            <td class="border px-4 py-1">{{ data['waste_sum'] ? data['waste_sum'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Простой электрика</td>
            <td class="border px-4 py-1">{{ data['electro'] ? data['electro'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Простой механика</td>
            <td class="border px-4 py-1">{{ data['mechanical'] ? data['mechanical'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Простой электроника</td>
            <td class="border px-4 py-1">{{ data['electronics'] ? data['electronics'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Техническое обслуживание</td>
            <td class="border px-4 py-1">{{ data['tech_service'] ? data['tech_service'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Замена сеток</td>
            <td class="border px-4 py-1">{{ data['change_net'] ? data['change_net'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Замена сырья</td>
            <td class="border px-4 py-1">{{ data['change_raw'] ? data['change_raw'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Замена заказа</td>
            <td class="border px-4 py-1">{{ data['change_order'] ? data['change_order'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Мойка машины</td>
            <td class="border px-4 py-1">{{ data['clean_machine'] ? data['clean_machine'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Приправка, часы</td>
            <td class="border px-4 py-1">{{ data['prepare_hours'] ? data['prepare_hours'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отсутствие людей</td>
            <td class="border px-4 py-1">{{ data['no_human'] ? data['no_human'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отсутствие работы</td>
            <td class="border px-4 py-1">{{ data['no_work'] ? data['no_work'].toFixed(2) : '' }}</td>

        </tr>
        <tr>
            <td class="border px-4 py-1">Отсутствие сырья</td>
            <td class="border px-4 py-1">{{ data['no_raw'] ? data['no_raw'].toFixed(2) : '' }}</td>
        </tr>
        </tbody>
    </table>

    <div v-else-if="props.data && !props.data.length === 0"
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи экструзии по заданным параметрам.</p>
    </div>
    <div v-else
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <div>Для выбора всех машин экструзии оставьте поле "Машина" пустым</div>
    </div>
</template>
