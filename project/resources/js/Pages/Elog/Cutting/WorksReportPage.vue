<script setup>
import MachinesReportFilter from '@/components/modules/MachinesReportFilter.vue';
import Popover from "@/components/Popover.vue";


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
        :header="'Краткий отчет резка'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'cutting.worksReport'"
        :localStorageKey="'cuttingReport'"
        :auth="props.auth"
        :is-excel-button-disabled="props.data.length === 0"
        :excel-path-to-convert="'/cutting/worksReport'"
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
            <td class="border px-4 py-1">Выработка кг</td>
            <td class="border px-4 py-1">{{ data['workout_mass'] ? data['workout_mass'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Выработка м.пог</td>
            <td class="border px-4 py-1">{{ data['workout_length'] ? data['workout_length'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Выработка м2</td>
            <td class="border px-4 py-1">{{ data['workout_m2'] ? data['workout_m2'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Выработка ОТК</td>
            <td class="border px-4 py-1">{{ data['otk_mass'] ? data['otk_mass'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Выработка сырьевые метры</td>
            <td class="border px-4 py-1">{{ data['raw_meters'] ? data['raw_meters'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы печать</td>
            <td class="border px-4 py-1">{{ data['waste_print'] ? data['waste_print'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы ламинация</td>
            <td class="border px-4 py-1">{{ data['waste_lam'] ? data['waste_lam'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы кромка</td>
            <td class="border px-4 py-1">{{ data['waste_edge'] ? data['waste_edge'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы итог</td>
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
            <td class="border px-4 py-1">Скорость</td>
            <td class="border px-4 py-1">{{ data['speed'] ? data['speed'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Тех обслуживание</td>
            <td class="border px-4 py-1">{{ data['tech_service'] ? data['tech_service'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Приправка ножей/штанги</td>
            <td class="border px-4 py-1">{{ data['knifes_barbell'] ? data['knifes_barbell'].toFixed(2) : '' }}</td>

        </tr>
        <tr>
            <td class="border px-4 py-1">Перестройка</td>
            <td class="border px-4 py-1">{{ data['reconfig'] ? data['reconfig'].toFixed(2) : '' }}</td>
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
        <tr>
            <td class="border px-4 py-1 flex align-center bg-emerald-50">
                <span class="mr-3">Итого простой</span>
                <Popover :title="'Как складывается?'"
                         :message="'Электрика + механика + ножи/штанга + перестройка + техобслуживание + отсутствие (всего 8 показателей)'"/>
            </td>
            <td class="border px-4 py-1 bg-emerald-50">{{ data['idles'] ? data['idles'].toFixed(2) : '' }}</td>
        </tr>
        </tbody>
    </table>

    <div v-else-if="props.data && !props.data.length === 0"
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи резки по заданным параметрам.</p>
    </div>
    <div v-else
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <div>Для выбора всех машин резки оставьте поле "Машина" пустым</div>
    </div>
</template>
