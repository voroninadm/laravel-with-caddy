<script setup>
import {summarize} from "@/common/elogTaskHelper.js";

const props = defineProps({
    tasks: {
        required: false
    }
})
</script>

<template>
<!--    <div class="overflow-x-auto">-->
    <div>
        <div class="w-full">
            <div class=" flex justify-center bg-white rounded-sm my-6">
                <table class="w-2/3 shadow-md table-auto">
                    <caption class=" text-red-800 font-bold uppercase text-sm mb-2">Ламинация</caption>
                    <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-xs leading-normal">
                        <th class="py-2 min-w-35 text-center">Машина</th>
                        <th class="py-1 px-3 text-center">Электрика</th>
                        <th class="py-1 px-3 text-center">Механика</th>
                        <th class="py-1 px-3 text-center">Приправка</th>
                        <th class="py-1 px-3 text-center">Тех обслуживание</th>
                        <th class="py-1 px-3 text-center">Тех чистка</th>
                        <th class="py-1 px-3 text-center">Замена клея</th>
                        <th class="py-1 px-3 text-center">Персонал</th>
                        <th class="py-1 px-3 text-center">Заказы</th>
                        <th class="py-1 px-3 text-center">Сырье</th>
                        <th class="py-1 px-3 text-center">Краткосрочный простой</th>
                        <th class="py-1 px-3 text-center">Без причины</th>
                        <th class="py-1 px-3 text-center bg-emerald-50">Простой по журналу</th>
                        <th class="py-1 px-3 text-center bg-lime-100">Простой по Энкосту</th>
                        <th class="py-1 px-3 text-center bg-yellow-100">Работа по Энкосту</th>
                    </tr>
                    </thead>

                    <tbody class="text-gray-600 text-sm font-light">

                    <tr v-for="(task, index) in tasks" :key="task.id"
                        class="border-b border-gray-200"
                        :class="{ 'bg-slate-50': index % 2 === 1}">
                        <td class="py-1 px-1 text-left">
                            <p>{{ task.machine_name }}</p>
                        </td>

                        <td class="py-1 px-1 text-center"><p>{{ task.electro ? task.electro.toFixed(2) : '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.mechanical ? task.mechanical.toFixed(2): '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.prepare_hours ? task.prepare_hours.toFixed(2): '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.tech_service ? task.tech_service.toFixed(2): '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.tech_clean ? task.tech_clean.toFixed(2): '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.change_glue ? task.change_glue.toFixed(2): '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.no_human ? task.no_human.toFixed(2) : '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.no_work ? task.no_work.toFixed(2) : '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.no_raw ? task.no_raw.toFixed(2) : '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.short_downtime ? task.short_downtime.toFixed(2) : '' }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ task.no_reason_downtime ? task.no_reason_downtime.toFixed(2) : '' }}</p></td>
                        <td class="py-1 px-1 text-center bg-emerald-50"><p>{{ task.sum ? task.sum.toFixed(2) : '' }}</p></td>
                        <td class="py-1 px-1 text-center bg-lime-100"><p>{{ task.total_downtime ? task.total_downtime.toFixed(2) : '' }}</p></td>
                        <td class="py-1 px-1 text-center bg-yellow-100"><p>{{ task.work_productive ? task.work_productive.toFixed(2) : '' }}</p></td>
                    </tr>
                    <tr class="bg-red-50">
                        <td class="py-1 px-1 font-bold"><b>ИТОГО</b></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'electro')}}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'mechanical') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'prepare_hours') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'tech_service') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'tech_clean') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'change_glue') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'no_human') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'no_work') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'no_raw') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'short_downtime') }}</p></td>
                        <td class="py-1 px-1 text-center"><p>{{ summarize(tasks, 'no_reason_downtime') }}</p></td>
                        <td class="py-1 px-1 text-center bg-emerald-50"><p>{{ summarize(tasks, 'sum') }}</p></td>
                        <td class="py-1 px-1 text-center bg-lime-100"><p></p>{{ summarize(tasks, 'total_downtime') }}</td>
                        <td class="py-1 px-1 text-center bg-yellow-100"><p></p></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>



<style scoped>

</style>
