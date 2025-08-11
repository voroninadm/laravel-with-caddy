<script setup>

import {dateForJsonOrder, normalizeDate} from "@/common/dateTimeHelper.js";
import AddTaskButton from "@/components/buttons/AddTaskButton.vue";
import Draggable from "vuedraggable";
import SecondaryButton from "@/components/SecondaryButton.vue";
import PasteTaskButton from "@/components/buttons/PasteTaskButton.vue";
import Dropdown from "@/components/Dropdown.vue";
import InputLabel from "@/components/InputLabel.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import TextareaInput from "@/components/TextareaInput.vue";
import TextInput from "@/components/TextInput.vue";
import Modal from "@/components/Modal.vue";
import {reactive, ref, watch} from "vue";
import {getTknDataFromDb} from "@/common/getTknDataFromDb.js";
import NumberInput from "@/components/NumberInput.vue";
import {useTknUpdater} from "@/common/useTknUpdater.js";

const props = defineProps({
    auth: Object,
    machines: Array,
    order: {
        required: true
    },
    searchedOrder: {
        require: false
    },
    orderStatuses: Object,
    isTaskInLocalStorage: Object
})

const emit = defineEmits(['prepareNewTask',
    'putTask',
    'submit',
    'orderToWork',
    'orderToStop',
    'orderToFinish',
    'destroy',
    'prepareNewTask',
    'putTask',
    'cutTask',
    'editOrder',
    'saveOrder'])


const isUserHasFullPermissions = props.auth.permissions.includes('full_extrusion_orders_permission');
const isUserHasMediumPermissions = props.auth.permissions.includes('medium_extrusion_orders_permission');

const isEditedOrder = ref(false); // имеется ли распоряжение для редактирования
const isModalOpen = ref(false);
const currentMachine = ref('cast');
const castConfig = {
    STREAMS: 2,
    machine: "КАСТ"
};


//== форма для создания-изменения распоряжений
const orderForm = reactive({
    orderIndex: '',
    STREAM: null,
    CARD_NUMBER: '',
    CUSTOMER: '',
    PRODUCT_TITLE: '',
    CIRCULATION_KG: null,
    CIRCULATION_LENGTH: null,
    WIDTH: null,
    STREAMS: null,
    THICKNESS: null,
    COLOR: '',
    NOTES: '',
    CREATED_AT: ''
});

const resetOrderForm = () => {
    for (let key in orderForm) {
        if (orderForm.hasOwnProperty(key)) {
            orderForm[key] = '';
        }
    }
};


const prepareNewTask = (machineKey, orderIndex) => {
    orderForm.taskType = 'extrusion';
    orderForm.machineKey = machineKey;
    orderForm.orderIndex = orderIndex;
    orderForm.CREATED_AT = dateForJsonOrder();
}

const editOrder = (task, machine, orderIndex) => {
    isModalOpen.value = true;
    isEditedOrder.value = true;
    orderForm.orderIndex = orderIndex;
    orderForm.taskType = 'extrusion';
    orderForm.machineKey = machine;
    orderForm.CREATED_AT = task.CREATED_AT;

    orderForm.CARD_NUMBER = task.CARD_NUMBER;
    orderForm.CUSTOMER = task.CUSTOMER;
    orderForm.PRODUCT_TITLE = task.PRODUCT_TITLE;
    orderForm.CIRCULATION_KG = task.CIRCULATION_KG ?? null;
    orderForm.CIRCULATION_LENGTH = task.CIRCULATION_LENGTH;
    orderForm.WIDTH = task.WIDTH ?? null;
    orderForm.STREAMS = task.STREAMS ?? null;
    orderForm.THICKNESS = task.THICKNESS ?? null;
    orderForm.COLOR = task.COLOR;
    orderForm.STREAM = task.STREAM;
    orderForm.NOTES = task.NOTES;
}

const cancelOrder = () => {
    isModalOpen.value = false;
    isEditedOrder.value = false
    resetOrderForm();
}

const saveOrder = () => {
    emit('saveOrder', orderForm, isEditedOrder);
    isModalOpen.value = false;
    resetOrderForm();
}

// отслеживание изменений поля tkn и обращение к DB
watch(
    () => orderForm.CARD_NUMBER,
    (newNumber, oldNumber) => {
        if (!isEditedOrder.value && orderForm.CARD_NUMBER) {
            getTknDataFromDb(newNumber, oldNumber, (data) => {
                orderForm.CUSTOMER = data.customer;
                orderForm.PRODUCT_TITLE = data.print_title;
                orderForm.WIDTH = data.activation_width;
                orderForm.STREAMS = data.extrusion_streams;
                orderForm.THICKNESS = data.thickness;
            });
        }
    }
);

const { updateAndSatisfyTkn } = useTknUpdater(orderForm, 'CARD_NUMBER');

</script>

<template>
    <div class="flex justify-center items-center gap-20 p-2">
        <button class="px-2 py-1 border shadow-sm rounded-full"
                :class="{'scale-105 shadow-inner bg-gray-50 text-gray-700 shadow-gray-800/50': currentMachine === 'cast'}"
                @click="currentMachine = 'cast'"
                type="button">КАСТ-линия
        </button>
        <button class="px-2 py-1 border shadow-sm rounded-full"
                :class="{'scale-105 shadow-inner bg-gray-50 text-gray-700 shadow-gray-800/50': currentMachine === 'blow'}"
                @click="currentMachine = 'blow'"
                type="button">blow-экструзия
        </button>
    </div>

    <header class="sticky top-1 z-10 mb-4">
        <div v-if="currentMachine === 'cast'"
             :class="`grid grid-cols-${castConfig.STREAMS} gap-4 text-gray-500 uppercase text-sm leading-normal text-xs`">
            <div v-for="stream in castConfig.STREAMS"
                 class="border-bottom">
                <div class="flex flex-col">
                    <p class="text-center text-sm text-gray-500 font-bold pb-2">{{ stream }} поток</p>
                    <div class="flex px-4 bg-gray-200">
                        <p class="w-14"></p>
                        <p class="w-1/12 text-center">Карта</p>
                        <p class="w-5/12 text-center">Заказчик</p>
                        <p class="w-2/12 text-center">Толщина</p>
                        <p class="w-2/12 text-center">Ширина</p>
                        <p class="w-2/12 text-center">Тираж, пог.м.</p>
                        <p class="w-2/12 text-center">Тираж, кг</p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="currentMachine === 'blow'"
             class="mt-2 flex shadow-lg shadow-gray-800/20 gap-4 bg-gray-200 text-gray-500 uppercase text-sm leading-normal">
            <p class="w-7 text-center"></p>
            <p class="w-1/12 text-center">Карта</p>
            <p class="w-2/12 text-center">Заказчик</p>
            <p class="w-2/12 text-center">Заказ</p>
            <p class="w-1/12 text-center">Толщина</p>
            <p class="w-1/12 text-center">Ширина раздува</p>
            <p class="w-1/12 text-center">Потоки</p>
            <p class="w-1/12 text-center">Цвет</p>
            <p class="w-1/12 text-center">Тираж пог.м.</p>
            <p class="w-1/12 text-center">Тираж кг</p>
            <p class="w-1/12 text-center">Примечание</p>
        </div>
    </header>

    <!--КАСТ линия-->
    <section v-if="currentMachine === 'cast'">
        <div v-for="(order, orderIndex) in props.order" :key="order.tkn">
            <div class="rounded-lg flex items-center  my-1 px-10 transition duration-200 cursor-pointer">
                <AddTaskButton v-if="isUserHasFullPermissions"
                               @click="isModalOpen = !isModalOpen; prepareNewTask (castConfig.machine, orderIndex)"
                               class="opacity-50 hover:opacity-100"
                />
                <p class="ml-4 leading-3 text-gray-600 opacity-50 hover:text-stone-800 hover:opacity-70 text-xs italic w-24 rounded-lg">
                    {{ normalizeDate(order.date) }}</p>
                <PasteTaskButton v-if="isTaskInLocalStorage['extrusion'] && isUserHasFullPermissions"
                                 @click="$emit('putTask', castConfig.machine, orderIndex)"
                                 class="opacity-50 hover:opacity-100"
                />
                <span class="w-full h-0.5 gradient"></span>
            </div>

            <draggable
                :class="`grid grid-cols-${castConfig.STREAMS} gap-x-4`"
                :list="order['extrusion'][castConfig.machine]"
                item-key="order.id"
                :animation="700"
                :group="`order-${order.date}-${castConfig.machine}`"
                ghost-class="bg-green-200"
                :move="() => isUserHasFullPermissions"
                @change="$emit('submit', orderIndex, castConfig.machine)"
            >
                <template v-slot:item="{ element: order }">
                    <div class="flex my-0.5 flex-col items-center justify-center border rounded-lg cursor-pointer"
                         :class="orderStatuses[order.STATUS],
                                 {'col-start-1 col-end-2' : +order.STREAM === 1},
                                 {'col-start-2 col-end-3' : +order.STREAM === 2},
                                 {'col-start-1 col-end-3' : order.STREAM === 'multi'},
                                 {'border-double border-4 border-red-400' : order.CARD_NUMBER === props.searchedOrder }"
                         :data-card="order.CARD_NUMBER">

                        <div class="flex w-full items-center">
                            <Dropdown :open="false"
                                      v-if="isUserHasFullPermissions || isUserHasMediumPermissions">
                                <template #trigger>
                                    <div class="flex rounded-md py-1">
                                        <button
                                            type="button"
                                            class="border ml-1 border-transparent flex items-center justify-center h-6 w-6 text-sm opacity-70 rounded-md hover:scale-105 hover:opacity-100 focus:outline-hidden transition duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 viewBox="0 0 24 24">
                                                <path fill="#000"
                                                      d="M9 12a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                                <path stroke="#000" stroke-linecap="round" stroke-width="1.5"
                                                      d="M22 12c0 4.7 0 7-1.5 8.5C19.1 22 16.7 22 12 22s-7 0-8.5-1.5C2 19.1 2 16.7 2 12s0-7 1.5-8.5C4.9 2 7.3 2 12 2s7 0 8.5 1.5c1 1 1.3 2.3 1.4 4.5"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                <template #content>
                                    <div>
                                        <button v-if="order.STATUS !== 'inWork'"
                                                @click="$emit('orderToWork', order, castConfig.machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-hidden transition duration-300"
                                                type="button">
                                            Пустить в работу
                                        </button>
                                        <button v-if="order.STATUS !== 'Stopped' && order.STATUS !== 'Finished'"
                                                @click="$emit('orderToStop',order, castConfig.machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 bg-orange-200 hover:bg-orange-200 focus:outline-hidden transition duration-300"
                                                type="button">
                                            Приостановить
                                        </button>
                                        <button v-if="order.STATUS !== 'Finished'"
                                                @click="$emit('orderToFinish', order, castConfig.machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 bg-sky-100 hover:bg-sky-200 focus:outline-hidden transition duration-300"
                                                type="button">
                                            Завершить
                                        </button>
                                        <button v-if="isUserHasFullPermissions"
                                                @click="editOrder(order, castConfig.machine, orderIndex)"
                                                class="mt-4 block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150"
                                                type="button">
                                            Изменить
                                        </button>
                                        <button v-if="isUserHasFullPermissions"
                                                @click="$emit('cutTask',order, castConfig.machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                                type="button">
                                            Вырезать
                                        </button>
                                        <button v-if="isUserHasFullPermissions"
                                                @click="$emit('destroy',order,  castConfig.machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                                type="button">
                                            Удалить
                                        </button>
                                    </div>
                                </template>
                            </Dropdown>
                            <p class="w-2/12 text-center pointer">{{ order.CARD_NUMBER }}</p>
                            <p class="w-4/12 text-center font-bold text-gray-500">{{ order.CUSTOMER }}</p>
                            <p class="w-2/12 text-center text-sm">
                                {{
                                    order.STREAM === 'multi' && order.THICKNESS ? `Толщина: ${order.THICKNESS} ` : order.THICKNESS
                                }}</p>
                            <p class="w-2/12 text-center text-sm">
                                {{
                                    order.STREAM === 'multi' && order.WIDTH ? `Ширина: ${order.WIDTH}` : order.WIDTH
                                }}</p>
                            <p class="w-2/12 text-center text-sm">
                                {{
                                    order.STREAM === 'multi' && order.CIRCULATION_LENGTH ? `Пог.м: ${order.CIRCULATION_LENGTH}` : order.CIRCULATION_LENGTH
                                }}</p>
                            <p class="w-2/12 text-center text-sm">{{ order.CIRCULATION_KG ?? '' }}</p>
                        </div>
                        <p class="italic text-xs text-gray-600">{{
                                order.NOTES ? "Примечание: " + order.NOTES : ''
                            }}</p>
                    </div>
                </template>
            </draggable>
        </div>
    </section>

    <!--Машины blow экструзии-->
    <section v-if="currentMachine === 'blow'">
        <div v-for="machine in machines.filter(machine => machine !== castConfig.machine)">
            <div
                class="flex shadow-md shadow-gray-800/50 items-center border justify-center rounded-lg gap-2 w-40 my-2 mx-auto sticky top-7 p-1 text-gray-700 bg-gray-200 z-10">
                <p class="font-semibold">{{ machine }}</p>
            </div>
            <div v-for="(order, orderIndex) in props.order">

                <div class="rounded-lg flex items-center my-1 px-1 transition duration-200 cursor-pointer">
                    <AddTaskButton v-if="isUserHasFullPermissions"
                                   @click="isModalOpen = !isModalOpen; prepareNewTask (machine, orderIndex)"
                                   class="opacity-50 hover:opacity-100"
                    />
                    <p class="ml-4 leading-3 text-gray-600 opacity-50 hover:text-stone-800 hover:opacity-70 text-xs italic w-24 rounded-lg">
                        {{ normalizeDate(order.date) }}</p>
                    <PasteTaskButton v-if="isTaskInLocalStorage['extrusion']"
                                     @click="$emit('putTask',machine, orderIndex)"
                                     class="opacity-50 hover:opacity-100"
                    />
                    <span class="w-full h-0.5 gradient"></span>
                </div>

                <draggable
                    :list="order['extrusion'][machine]"
                    item-key="order.id"
                    :animation="700"
                    :group="`order-${order.date}-${machine}`"
                    ghost-class="bg-green-200"
                    :move="() => isUserHasFullPermissions"
                    @change="$emit('submit', orderIndex, machine)"
                >
                    <template v-slot:item="{ element: order }">
                        <div class="flex my-1 gap-4 items-center justify-center w-full border rounded-lg cursor-pointer"
                             :class="orderStatuses[order.STATUS], {'border-double border-4 border-red-400' : order.CARD_NUMBER === props.searchedOrder }"
                             :data-card="order.CARD_NUMBER">

                            <Dropdown :open="false"
                                      v-if="isUserHasFullPermissions || isUserHasMediumPermissions">
                                <template #trigger>
                                    <div class="flex rounded-md py-1">
                                        <button
                                            type="button"
                                            class="border ml-1 border-transparent flex items-center justify-center h-6 w-6 text-sm opacity-70 rounded-md hover:scale-105 hover:opacity-100 focus:outline-hidden transition duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 viewBox="0 0 24 24">
                                                <path fill="#000"
                                                      d="M9 12a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                                <path stroke="#000" stroke-linecap="round" stroke-width="1.5"
                                                      d="M22 12c0 4.7 0 7-1.5 8.5C19.1 22 16.7 22 12 22s-7 0-8.5-1.5C2 19.1 2 16.7 2 12s0-7 1.5-8.5C4.9 2 7.3 2 12 2s7 0 8.5 1.5c1 1 1.3 2.3 1.4 4.5"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                <template #content>
                                    <div>
                                        <button v-if="order.STATUS !== 'inWork'"
                                                @click="$emit('orderToWork', order, machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-hidden transition duration-300"
                                                type="button">
                                            Пустить в работу
                                        </button>
                                        <button v-if="order.STATUS !== 'Stopped' && order.STATUS !== 'Finished'"
                                                @click="$emit('orderToStop',order, machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 bg-orange-200 hover:bg-orange-200 focus:outline-hidden transition duration-300"
                                                type="button">
                                            Приостановить
                                        </button>
                                        <button v-if="order.STATUS !== 'Finished'"
                                                @click="$emit('orderToFinish', order, machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 bg-sky-100 hover:bg-sky-200 focus:outline-hidden transition duration-300"
                                                type="button">
                                            Завершить
                                        </button>
                                        <button  v-if="isUserHasFullPermissions"
                                                @click="editOrder(order, machine, orderIndex)"
                                                class="mt-4 block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150"
                                                type="button">
                                            Изменить
                                        </button>
                                        <button v-if="isUserHasFullPermissions" @click="$emit('cutTask',order, machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                                type="button">
                                            Вырезать
                                        </button>
                                        <button v-if="isUserHasFullPermissions" @click="$emit('destroy',order,  machine, orderIndex)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                                type="button">
                                            Удалить
                                        </button>
                                    </div>
                                </template>
                            </Dropdown>
                            <p class="w-1/12 text-center pointer">{{ order.CARD_NUMBER }}</p>
                            <p class="w-2/12 text-center font-bold text-gray-500">{{ order.CUSTOMER }}</p>
                            <p class="w-2/12 text-center text-sm text-gray-700">{{ order.PRODUCT_TITLE }}</p>
                            <p class="w-1/12 text-center text-sm">{{ order.THICKNESS ?? '' }}</p>
                            <p class="w-1/12 text-center text-sm">{{ order.WIDTH ?? '' }}</p>
                            <p class="w-1/12 text-center text-sm">{{ order.STREAMS ?? '' }}</p>
                            <p class="w-1/12 text-center text-sm">{{ order.COLOR ?? '' }}</p>
                            <p class="w-1/12 text-center text-sm">{{ order.CIRCULATION_LENGTH ?? '' }}</p>
                            <p class="w-1/12 text-center text-sm">{{ order.CIRCULATION_KG ?? '' }}</p>
                            <p class="w-1/12 italic text-sm text-center">{{ order.NOTES }}</p>
                        </div>
                    </template>
                </draggable>
            </div>
        </div>
    </section>

    <Modal :show="isModalOpen" @close="isModalOpen = !isModalOpen; cancelOrder()" :maxWidth="'large'">
        <h2 class="uppercase text-center font-bold mt-4">
            {{ isEditedOrder ? 'Изменение' : 'Создание нового ' }} распоряжения
        </h2>
        <div class="flex justify-center" @keyup.enter="saveOrder()">
            <div class="flex flex-col p-6">
                <section class="text-gray-800">

                    <div class="flex gap-4 items-center mb-4 mt-2">
                        <input-label class="w-1/3" for="tkn">Номер карты</input-label>
                        <TextInput
                            id="tkn"
                            class="h-6 block w-full text-sm"
                            :class="{'bg-gray-200' : isEditedOrder}"
                            v-model="orderForm.CARD_NUMBER"
                            @input="updateAndSatisfyTkn"
                            autocomplete="off"
                            :disabled="isEditedOrder"
                            required
                        />
                    </div>

                    <div class="flex gap-4 items-center mb-4 mt-2">
                        <input-label class="w-1/3" for="customer">Заказчик</input-label>
                        <TextInput
                            id="customer"
                            class="h-6 block w-full text-sm"
                            :class="{'bg-gray-200' : isEditedOrder}"
                            v-model="orderForm.CUSTOMER"
                            autocomplete="off"
                            :disabled="isEditedOrder"
                            required
                        />
                    </div>

                    <div class="flex gap-4 mb-4">
                        <input-label class="w-1/3" for="print_title">Заказ</input-label>
                        <TextInput
                            id="print_title"
                            class="h-6 block w-full  text-sm"
                            :class="{'bg-gray-200' : isEditedOrder}"
                            v-model="orderForm.PRODUCT_TITLE"
                            autocomplete="off"
                            :disabled="isEditedOrder"
                            required
                        />
                    </div>
                </section>

                <div class="flex  justify-center items-center gap-10">
                    <div class="w-4/12">
                        <div class="flex gap-4 mt-2 items-center">
                            <input-label class="w-1/2" for="width">Ширина</input-label>
                            <NumberInput
                                id="width"
                                class="h-6 block w-full text-sm"
                                v-model.number="orderForm.WIDTH"
                                step="0.01"
                            />
                        </div>
                        <div class="flex gap-4 mt-2 items-center">
                            <input-label class="w-1/2" for="thickness">Толщина</input-label>
                            <NumberInput
                                id="thickness"
                                class="h-6 block w-full text-sm"
                                v-model.number="orderForm.THICKNESS"
                                step="0.01"
                            />
                        </div>
                        <div class="flex gap-4 mt-2 items-center">
                            <input-label class="w-1/2" for="streams">Кол-во потоков</input-label>
                            <NumberInput
                                id="streams"
                                class="h-6 block w-full text-sm"
                                v-model.number="orderForm.STREAMS"
                            />
                        </div>
                    </div>
                    <div class="w-6/12">
                        <div class="flex gap-4 mt-2 items-center">
                            <input-label class="w-1/2" for="kg">Тираж, кг</input-label>
                            <NumberInput
                                id="kg"
                                class="h-6 block w-full text-sm"
                                v-model.number="orderForm.CIRCULATION_KG"
                                step="0.01"
                            />
                        </div>
                        <div class="flex gap-4 mt-2 items-center">
                            <input-label class="w-1/2" for="length">Тираж, пог.м.</input-label>
                            <NumberInput
                                id="length"
                                v-model.number="orderForm.CIRCULATION_LENGTH"
                                class="h-6 block w-full text-sm"
                                step="0.01"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="currentMachine !== 'cast'" class="flex flex-col items-center justify-center m-auto mb-4">
            <p class="text-sm italic pt-3">Выберите цветность материала:</p>
            <div class="flex gap-6 border border-white rounded-sm p-2">
                <label class="flex flex-col items-center radio-label text-sm italic">
                    <input type="radio" class="input-radio transparent" v-model="orderForm.COLOR"
                           value="Прозрачный">
                    <span :class="{'underline' : orderForm.COLOR === 'Прозрачный'}">Прозрачный</span>
                </label>
                <label class="flex flex-col items-center  radio-label text-sm italic">
                    <input type="radio" class="input-radio matte" v-model="orderForm.COLOR" value="Матовый">
                    <span :class="{'underline' : orderForm.COLOR === 'Матовый'}">Матовый</span>
                </label>
                <label class="flex flex-col items-center radio-label text-sm italic">
                    <input type="radio" class="input-radio white" v-model="orderForm.COLOR" value="Белый">
                    <span :class="{'underline' : orderForm.COLOR === 'Белый'}">Белый</span>
                </label>
                <label class="flex flex-col items-center radio-label text-sm italic">
                    <input type="radio" class="input-radio black-white" v-model="orderForm.COLOR"
                           value="Черно-белый">
                    <span :class="{'underline' : orderForm.COLOR === 'Черно-белый'}">Черно-белый</span>
                </label>
                <label class="flex flex-col items-center radio-label text-sm italic">
                    <input type="radio" class="input-radio colored" v-model="orderForm.COLOR" value="Цветной">
                    <span :class="{'underline' : orderForm.COLOR === 'Цветной'}">Цветной</span>
                </label>
            </div>
        </div>

        <div v-if="currentMachine === 'cast'" class="flex items-center justify-center m-auto mb-4">
            <input-label class="mr-10 italic">Выберите поток</input-label>
            <div class="flex gap-6 border border-white rounded-sm p-2">
                <label class="flex flex-col items-center radio-label text-sm italic">
                    <input type="radio" class="input-radio green" v-model="orderForm.STREAM" value="1">
                    Первый
                </label>
                <label class="flex flex-col items-center  radio-label text-sm italic">
                    <input type="radio" class="input-radio green" v-model="orderForm.STREAM" value="2">
                    Второй
                </label>
                <label class="flex flex-col items-center radio-label text-sm italic">
                    <input type="radio" class="input-radio golden" v-model="orderForm.STREAM" value="multi">
                    Многопоточный
                </label>
            </div>
        </div>

        <div class="px-6 py-1">
            <div class="flex flex-col gap-2 items-center">
                <input-label for="notes">Примечание</input-label>
                <TextareaInput
                    id="notes"
                    class="h-20 block w-full text-sm italic"
                    v-model="orderForm.NOTES"
                    autocomplete="off"
                />
            </div>
        </div>

        <div class="flex gap-8 justify-center p-4">
            <PrimaryButton @click="saveOrder()" class="flex justify-center">
                Сохранить
            </PrimaryButton>
            <SecondaryButton @click="cancelOrder" class="flex justify-center">Отменить</SecondaryButton>
        </div>

        <datalist id="streams">
            <option value="1">1</option>
            <option value="1">2</option>
            <option value="multi"></option>
        </datalist>
    </Modal>

</template>

<style scoped>
.active {
}

.stream-1 {
    grid-column-start: 1;
}

/* Стили для второго потока */
.stream-2 {
    grid-column-start: 2;
}

.transparent:checked {
    box-shadow: 0 0 0 2px rgb(255, 255, 255);
    background-image: linear-gradient(to right, rgb(197, 197, 192), rgb(255, 255, 255));
}

.matte:checked {
    box-shadow: 0 0 0 2px rgb(190, 185, 185);
    background-image: linear-gradient(to right, rgb(197, 197, 192), rgb(187, 184, 184));
}

.white:checked {
    box-shadow: 0 0 0 2px rgb(145, 143, 143);
    background-image: linear-gradient(to right, rgb(255, 255, 255), rgb(255, 255, 255));
}

.black-white:checked {
    box-shadow: 0 0 0 2px rgb(0, 0, 0);
    background-image: linear-gradient(to right, rgb(0, 0, 0), rgb(255, 255, 255));
}

.colored:checked {
    box-shadow: 0 0 0 2px rgb(49, 136, 37);
    background-image: linear-gradient(to right, rgb(132, 246, 0), rgb(255, 0, 47));
}

.input-radio.green:checked {
    box-shadow: 0 0 0 2px rgb(49, 136, 37);
    background-color: rgb(49, 136, 37);
}

.input-radio.golden:checked {
    box-shadow: 0 0 0 2px rgb(245, 189, 6);
    background-color: rgb(245, 189, 6);
}
</style>
