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
        :header="'Краткий отчет печать'"
        :machines="machines"
        :workers="workers"
        :routeToSubmit="'printing.worksReport'"
        :localStorageKey="'printingReport'"
        :auth="props.auth"
        :is-excel-button-disabled="props.data.length === 0"
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
            <td class="border px-4 py-1">Факт материала</td>
            <td class="border px-4 py-1">{{ data['mat1count'] ? data['mat1count'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Ширина, среднее</td>
            <td class="border px-4 py-1">{{ data['width1'] ? data['width1'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Толщина, среднее</td>
            <td class="border px-4 py-1">{{ data['thickness1'] ? data['thickness1'].toFixed(2) : '' }}</td>
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
            <td class="border px-4 py-1"> Выработка ОТК</td>
            <td class="border px-4 py-1">{{ data['otk_mass'] ? data['otk_mass'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Краски, среднее</td>
            <td class="border px-4 py-1">{{ data['colors'] ? data['colors'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Скорость печати, среднее</td>
            <td class="border px-4 py-1"> {{ data['speed'] ? data['speed'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Приправка кг</td>
            <td class="border px-4 py-1">{{ data['prepare_mass'] ? data['prepare_mass'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы печать</td>
            <td class="border px-4 py-1">{{ data['waste_print'] ? data['waste_print'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы сырье</td>
            <td class="border px-4 py-1">{{ data['waste_raw'] ? data['waste_raw'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Отходы итог</td>
            <td class="border px-4 py-1">{{ data['waste_sum'] ? data['waste_sum'].toFixed(2) : '' }}</td>
        </tr>


        <tr>
            <td class="py-2 text-gray-500 text-sm font-bold">Простои: </td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Приправка час</td>
            <td class="border px-4 py-1">{{ data['prepare_hours'] ? data['prepare_hours'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Коррекция PN</td>
            <td class="border px-4 py-1">{{ data['correction_PN'] ? data['correction_PN'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Коррекция CMYK</td>
            <td class="border px-4 py-1">{{ data['correction_CMYK'] ? data['correction_CMYK'].toFixed(2) : '' }}</td>
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
            <td class="border px-4 py-1">Переклейка форм</td>
            <td class="border px-4 py-1">{{ data['form_glue'] ? data['form_glue'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Замена расходников</td>
            <td class="border px-4 py-1">{{ data['service_replacement'] ? data['service_replacement'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Техническое обслуживание</td>
            <td class="border px-4 py-1">{{ data['tech_service'] ? data['tech_service'].toFixed(2) : '' }}</td>
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
            <td class="border px-4 py-1">Краткосрочный простой</td>
            <td class="border px-4 py-1">{{ data['short_downtime'] ? data['short_downtime'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1">Простой без причины</td>
            <td class="border px-4 py-1">{{ data['no_reason_downtime'] ? data['no_reason_downtime'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1 flex align-center bg-emerald-50">
                <span class="mr-3">Сумма по простоям</span>
                <Popover :title="'Как складывается?'"
                         :message="'Сумма указанных в журнале полей с простоями'"/>
            </td>
            <td class="border px-4 py-1 bg-emerald-50">{{ data['idles'] ? data['idles'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1 flex align-center bg-lime-100">
                <span class="mr-3">Простой по Энкосту</span>
                <Popover :title="'Что это?'"
                         :message="'Сумма простоя, автоматически получаемая из Энкоста при создании задачи мастерами'"/></td>
            <td class="border px-4 py-1  bg-lime-100">{{ data['total_downtime'] ? data['total_downtime'].toFixed(2) : '' }}</td>
        </tr>
        <tr>
            <td class="border px-4 py-1 flex align-center bg-yellow-100">
                <span class="mr-3">Время выпуска по Энкосту</span>
                <Popover :title="'Что это?'"
                         :message="'Сумма чистой работы, автоматически получаемая из Энкоста при создании задачи мастерами'"/></td>
            <td class="border px-4 py-1  bg-yellow-100">{{ data['work_productive'] ? data['work_productive'].toFixed(2) : '' }}</td>
        </tr>
        </tbody>
    </table>

    <div v-else-if="props.data && !props.data.length === 0"
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Не найдены задачи печати по заданным параметрам.</p>
    </div>
    <div v-else
         class="flex flex-col mx-auto mt-10 text-lg text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
        <p class="mb-2">Выберите нужные параметры для поиска работ и сформируйте запрос.</p>
        <div>Для выбора всех машин печати оставьте поле "Машина" пустым</div>
    </div>
</template>
