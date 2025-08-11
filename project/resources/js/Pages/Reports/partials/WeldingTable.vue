<script setup>
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
                <table class="shadow-md table-auto">
                    <caption class=" text-red-800 font-bold uppercase text-sm mb-2">Сварка</caption>
                    <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-2  px-4 text-center">Машина</th>
                        <th class="py-2 px-4 text-center">Электрика</th>
                        <th class="py-2 px-4 text-center">Механика</th>
                        <th class="py-2 px-4 text-center">Наладка</th>
                        <th class="py-2 px-4 text-center">Перенастройка</th>
                        <th class="py-2 px-4 text-center">Тех обслуживание</th>
                        <th class="py-2 px-4 text-center">Замена тефлона</th>
                        <th class="py-2 px-4 text-center">Персонал</th>
                        <th class="py-2 px-4 text-center">Заказы</th>
                        <th class="py-2 px-4 text-center">Сырье</th>
                        <th class="py-2 px-4 text-center">Итог</th>
                    </tr>
                    </thead>

                    <tbody class="text-gray-600 text-sm font-light">

                    <tr v-for="(task, index) in tasks" :key="task.id"
                        class="border-b border-gray-200"
                        :class="{ 'bg-slate-50': index % 2 === 1}">
                        <td class="py-1 px-4 text-left">
                            <p>{{ task.machine_name }}</p>
                        </td>

                        <td class=" py-1 px-4 text-center">
                            <p>{{ task.electro ? task.electro.toFixed(2) : '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.mechanical ? task.mechanical.toFixed(2): '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.calibrating ? task.calibrating.toFixed(2): '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.reconfig ? task.reconfig.toFixed(2): '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.tech_service ? task.tech_service.toFixed(2): '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.change_teflon ? task.change_teflon.toFixed(2): '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.no_human ? task.no_human.toFixed(2) : '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.no_work ? task.no_work.toFixed(2) : '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.no_raw ? task.no_raw.toFixed(2) : '' }}</p>
                        </td>
                        <td class="py-1 px-4 text-center">
                            <p>{{ task.sum ? task.sum.toFixed(2) : '' }}</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>



<style scoped>

</style>
