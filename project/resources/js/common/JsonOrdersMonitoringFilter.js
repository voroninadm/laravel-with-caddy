import {computed} from "vue";

function createFinalOrder() {
    return {
        CARD_NUMBER: "-",
        CREATED_AT: "-",
        CUSTOMER: "-",
        DEADLINE: null,
        FINISHED_AT: "",
        NOTES: "ДАЛЕЕ РАСПОРЯЖЕНИЯ НЕ СОЗДАНЫ",
        PLAN_PLEN_WELD_KG: "-",

    };
}

function filterMachineTasks(index, order, nextOrder, props) {
    props.machines.forEach((machine) => {
        let tasks = order[machine];
        let filteredTasks = [];

        let inWorkIndex = tasks.findIndex(task => task.STATUS === 'inWork');

        if (inWorkIndex !== -1) {
            filteredTasks.push(tasks[inWorkIndex]);

            // Если есть следующая задача в этом массиве, добавить её
            if (inWorkIndex + 1 < tasks.length) {
                filteredTasks.push(tasks[inWorkIndex + 1]);
            } else if (nextOrder && nextOrder[machine][0]) { // Если текущая задача последняя, взять первую задачу из следующего дня
                const nextTasks = nextOrder[machine];
                filteredTasks.push(nextTasks[0]);
            } else if (nextOrder && !nextOrder[machine][0]) { // Если текущая задача последняя, взять первую задачу из следующего дня
                filteredTasks.push(createFinalOrder());
            }
        }

        // Обновить задачи машины
        order[machine] = [...filteredTasks];
    });
}

export function useFilteredOrders(ordersData, monitoringMode, props) {
    return computed(() => {
        if (!monitoringMode.value) {
            return ordersData.value;
        }

        // Режим мониторинга включен. Создаем глубокую копию данных заказов, чтобы не изменять оригинальные данные
        let data = JSON.parse(JSON.stringify(ordersData.value));

        // Пройти по всем заказам и применить фильтрацию
        data.forEach((order, index) => {
            // Передаем текущий заказ и следующий заказ в функцию
            const nextOrder = ordersData.value[index + 1];
            filterMachineTasks(index, order[props.machineType], nextOrder ? nextOrder[props.machineType] : null, props);
        });

        return data;
    });
}
