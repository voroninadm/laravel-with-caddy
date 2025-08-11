<script setup>

import {computed, onMounted, onUnmounted, onUpdated, reactive, ref, shallowRef, toRefs} from "vue";
import {showNotification} from "@/common/notifications.js";
import {useForm} from "@inertiajs/vue3";
import axios from "axios";
import JsonOrderFilter from "@/Pages/JsonOrder/partials/JsonOrderFilter.vue";
import LaminationOrderTable from "@/Pages/JsonOrder/partials/tables/LaminationOrderTable.vue";
import RecyclingOrderTable from "@/Pages/JsonOrder/partials/tables/RecyclingOrderTable.vue";
import ExtrusionOrderTable from "@/Pages/JsonOrder/partials/tables/ExtrusionOrderTable.vue";
import SpoolcuttingOrderTable from "@/Pages/JsonOrder/partials/tables/SpoolcuttingOrderTable.vue";
import {useFilteredOrders} from "@/common/JsonOrdersMonitoringFilter.js";
import PrintingOrderTable from "@/Pages/JsonOrder/partials/tables/PrintingOrderTable.vue";
import WeldingOrderTable from "@/Pages/JsonOrder/partials/tables/WeldingOrderTable.vue";
import CuttingOrderTable from "@/Pages/JsonOrder/partials/tables/CuttingOrderTable.vue";
import {normalizeDate} from "@/common/dateTimeHelper.js";

const props = defineProps({
    auth: Object,
    machines: Array,
    order: {
        required: true
    },
    searchedOrder: {
        require: false
    },
    machineType: {
        required: false
    },
    workers: {
        required: true
    },
    searchedSpoolcutter: {
        require: false
    },
    spoolcutterWorkout: {
        require: false
    }
})

const isTaskInLocalStorage = reactive({
    printing: false,
    lamination: false,
    welding: false,
    cutting: false,
    extrusion: false,
    recycling: false,
    spoolcutting: false,
})


// === Orders Scripts ===
const reactiveProps = toRefs(props); // делаем пропсы реактивными для работы
const ordersData = reactiveProps.order; //  делаем реактивные распоряжения
const monitoringMode = ref(false);

const filteredOrders = useFilteredOrders(ordersData, monitoringMode, props); // фильтрация для режима мониторинга
// //== основная форма
const form = useForm({
    id: '',
    date: '',
    payload: '',
    filteredUpdate: '',
    machine: ''
});

// сохраняем-применяем все изменения
const submit = (orderIndex, machine) => {
    form.id = ordersData.value[orderIndex].id;
    form.date = ordersData.value[orderIndex].date;
    form.payload = ordersData.value[orderIndex][props.machineType][machine];
    form.machine = machine;
    form.filteredUpdate = props.machineType;

    axios.post(`/order/update/${form.id}`, form)
        .then((response) => {
            showNotification('success', 'Изменения успешно применены!');
            form.reset();
        })
        .catch((error) => {
            showNotification('error', `Произошла ошибка при сохранении распоряжения: ${error.message}`);
        });
}

// сохраняем новое или измененное распоряжение
const saveOrder = (orderForm, isEditedOrder) => {
    // в переменные сохраняем служебные параметры и удаляем их из объекта
    const orderIndex = orderForm.orderIndex;
    delete orderForm.orderIndex;

    const orderMachineKey = orderForm.machineKey;
    delete orderForm.machineKey;

    const orderTaskType = orderForm.taskType;
    delete orderForm.taskType;

    const orders = ordersData.value[orderIndex];

    if (!isEditedOrder.value) {
        orders[props.machineType][orderMachineKey].push(orderForm);
        // Обновляем существующее распоряжение
    } else {
        const taskIndex = orders[orderTaskType][orderMachineKey].findIndex((task) => {
            if (orderForm.CREATED_AT) {
                return task.CREATED_AT === orderForm.CREATED_AT;
                // до переработки было только по номеру карты, потом добавлено поле created_at
            } else {
                return task.CARD_NUMBER === orderForm.CARD_NUMBER;
            }
        });

        if (taskIndex !== -1) {
            Object.assign(orders[orderTaskType][orderMachineKey][taskIndex], orderForm);
        }

        isEditedOrder.value = false;
    }
    submit(orderIndex, orderMachineKey);
}


const destroy = (order, machine, orderIndex) => {
    const orders = ordersData.value[orderIndex];
    const machineOrders = orders[props.machineType][machine];
    const index = machineOrders.findIndex(item => item === order);

    if (index !== -1) {
        // Если элемент найден, удаляем его из массива
        machineOrders.splice(index, 1);
    } else {
        showNotification('error', `Распоряжение с номером ${order.CARD_NUMBER} не найдено в массиве.`);
    }
    submit(orderIndex, machine);
};

const cutTask = (order, machine, orderIndex) => {
    storeToLS(order);
    isTaskInLocalStorage[props.machineType] = true;
    destroy(order, machine, orderIndex);
}

const putTask = (machine, orderIndex) => {
    let order = checkLocalStorage();
    const orders = ordersData.value[orderIndex];
    const machineOrders = orders[props.machineType][machine];
    if (order) {
        machineOrders.push(order);
        localStorage.removeItem(props.machineType);
        isTaskInLocalStorage[props.machineType] = false;
        submit(orderIndex, machine);
    }
}

// сохраняем распоряжение в local storage

const storeToLS = (order) => {
    localStorage.setItem(props.machineType, JSON.stringify(order));
}

const checkLocalStorage = () => {
    let order = localStorage.getItem(props.machineType);
    if (order) {
        isTaskInLocalStorage[props.machineType] = true;
        order = JSON.parse(order)
    }
    return order || false;
}

//== изменения статусов распоряжения
const orderStatuses = {
    "inWork": 'bg-yellow-200',
    "Stopped": 'bg-orange-200',
    "Finished": 'bg-sky-200'
};
const orderToWork = (order, machine, orderIndex) => {
    order.STATUS = 'inWork';
    submit(orderIndex, machine);
}
const orderToStop = (order, machine, orderIndex) => {
    order.STATUS = 'Stopped';
    submit(orderIndex, machine);
}
const orderToFinish = (order, machine, orderIndex) => {
    order.STATUS = 'Finished';
    order.FINISHED_AT = new Date();
    submit(orderIndex, machine);
}

let listener;
const connectionLost = ref(false);

onMounted(() => {
    // Обработчик обновления распоряжений
    const order_channel = Echo.channel('order-updated');
    listener = order_channel.listen('.order-updated', (e) => {
        props.order.forEach(order => {
            if (order.id === e.id) {
                order[e.type][e.machine] = e.order;
            }
        });
        if (props.auth.user.id !== e.user_id) {
            showNotification('default', 'Распоряжения обновлены');
        }
    });

    // Обработчик потери соединения
    Echo.connector.pusher.connection.bind('disconnected', () => {
        connectionLost.value = true;
        showNotification('error', 'Соединение с сервером потеряно');
    });

    // Обработчик восстановления соединения
    Echo.connector.pusher.connection.bind('connected', () => {
        connectionLost.value = false;
        showNotification('success', 'Соединение с сервером установлено');
    });

    // слушатель добавления для в графике
    const schedule_channel = Echo.channel('daily-schedule-created');
    schedule_channel.listen('.daily-schedule-created', (e) => {
        props.order.push(e.order);
        showNotification('default', `Создан график распоряжений на ` + normalizeDate(e.order.date), 8000);
    })
});

onUnmounted(() => {
    listener.stopListening('.order-updated');
});

const containerRef = ref(null);
onUpdated(() => {
    if (props.order.length !== 0 && props.searchedOrder) {
        const cardElement = containerRef.value.querySelector(`[data-card="${props.searchedOrder}"]`);
        if (cardElement) {
            setTimeout(() => {
                cardElement.scrollIntoView({block: 'nearest', inline: 'nearest', behavior: 'smooth'})
            }, 1000);
        }
    }
    checkLocalStorage()
});

const componentsMap = {
    printing: PrintingOrderTable,
    welding: WeldingOrderTable,
    cutting: CuttingOrderTable,
    lamination: LaminationOrderTable,
    extrusion: ExtrusionOrderTable,
    recycling: RecyclingOrderTable,
    spoolcutting: SpoolcuttingOrderTable
};
const currentTableComponent = computed(() => componentsMap[props.machineType]);
</script>


<template>

    <JsonOrderFilter
        :auth="auth"
        :is-card-field-disabled="props.machineType === 'recycling'"
        :workers="workers"
        :spoolcutterWorkout="spoolcutterWorkout"
        :machine-type="props.machineType"
        @toggle-monitoring-mode="monitoringMode = !monitoringMode"

    />

    <section v-if="props.order.length !== 0" :class="monitoringMode ? 'm-auto w-full' : ''">
        <div class="border rounded-lg"
             :class="monitoringMode ? 'mt-3 h-max' : 'px-3 pb-56 mx-4'"
             ref="containerRef"
        >
            <div v-if="connectionLost && monitoringMode">
                <div class="absolute top-0 left-0 w-screen h-screen bg-gray-800/70 z-10">

                </div>
                <div
                    class="absolute top-1/2 p-2 w-1/2 left-1/4 z-20 m-auto text-center bg-red-50 border-2 border-red-400 rounded-xl animate-pulse">
                    <p class="text-red-800 font-bold text-lg ">Соединение с сервером прервано! Обновите страницу и
                        проверьте сетевое подключение!</p>
                </div>
            </div>
            <component
                :is="currentTableComponent"
                :auth="auth"
                :machines="machines"
                :order-statuses="orderStatuses"
                :order="filteredOrders"
                :searchedOrder="searchedOrder"
                :is-task-in-local-storage="isTaskInLocalStorage"
                :monitoringMode="monitoringMode"
                :workers="workers"
                :searchedSpoolcutter="searchedSpoolcutter"

                @submit="submit"
                @save-order="saveOrder"
                @order-to-work="orderToWork"
                @order-to-stop="orderToStop"
                @order-to-finish="orderToFinish"
                @destroy="destroy"
                @cut-task="cutTask"
                @put-task="putTask"
            />
        </div>
    </section>

    <section v-else>
        <div
            class="flex flex-col mx-auto mt-10 text-center p-2 italic outline-dotted outline-3 outline-offset-8 outline-slate-300 rounded-lg">
            <p class="mb-2">Не найдено распоряжений по заданным параметрам.</p>
        </div>
    </section>

</template>

<style scoped>
.input-radio.green:checked {
    box-shadow: 0 0 0 2px rgb(49, 136, 37);
    background-color: rgba(49, 136, 37, 0.99);
}
</style>

<style lang="scss">
.dp-custom-input-date {
    color: gray;

    &:focus {
        border-color: rgba(253, 9, 9, 0.3);
        --tw-ring-color: rgb(245, 6, 6, 0.3);
    }
}

.dp__theme_light {
    --dp-primary-color: rgba(248, 7, 7, 0.3);
    --dp-primary-text-color: black;
    --dp-hover-color: #ffeeee;
    --tw-ring-color: rgba(248, 7, 7, 0.3);
}

.icon-hover {
    fill: yellow;
}

.gradient {
    background: linear-gradient(288deg, rgba(22, 221, 106, 0.2825) 0%, rgba(79, 76, 76, 0.023) 100%);
}

.noselect {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
}

</style>
