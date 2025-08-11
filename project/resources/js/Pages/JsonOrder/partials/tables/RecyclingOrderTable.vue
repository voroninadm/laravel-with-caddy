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
import Modal from "@/components/Modal.vue";
import {reactive, ref, watch} from "vue";

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


const isUserHasFullPermissions = props.auth.permissions.includes('full_recycling_orders_permission');
const isUserHasMediumPermissions = props.auth.permissions.includes('medium_recycling_orders_permission');


const isEditedOrder = ref(false); // имеется ли распоряжение для редактирования
const isModalOpen = ref(false);

//== форма для создания-изменения распоряжений
const orderForm = reactive({
    orderIndex: '',
    CARD_NUMBER: '',
    order: '',
    NOTES: '',
});

const resetOrderForm = () => {
    for (let key in orderForm) {
        if (orderForm.hasOwnProperty(key)) {
            orderForm[key] = '';
        }
    }
};


const prepareNewTask = (machineKey, orderIndex) => {
    orderForm.taskType = 'recycling';
    orderForm.machineKey = machineKey;
    orderForm.orderIndex = orderIndex;
    orderForm.CARD_NUMBER = dateForJsonOrder();
}

const editOrder = (task, machine, orderIndex) => {
    isModalOpen.value = true;
    isEditedOrder.value = true;
    orderForm.orderIndex = orderIndex;
    orderForm.taskType = 'recycling';
    orderForm.machineKey = machine;

    orderForm.CARD_NUMBER = task.CARD_NUMBER;
    orderForm.order = task.order;
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
</script>

<template>
    <header class="sticky top-1 z-10">
        <div class="flex shadow-lg shadow-gray-800/20 gap-4 bg-gray-200 text-gray-500 uppercase text-sm leading-normal">
            <p class="w-7 text-center"></p>
            <p class="w-6/12 text-center">Распоряжение</p>
            <p class="w-6/12 text-center">Примечание</p>
        </div>
    </header>

    <div v-for="machine in machines">
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
                <PasteTaskButton v-if="isTaskInLocalStorage['recycling'] && isUserHasFullPermissions"
                                 @click="$emit('putTask',machine, orderIndex)"
                                 class="opacity-50 hover:opacity-100"
                />
                <span class="w-full h-0.5 gradient"></span>
            </div>

            <draggable
                :list="order['recycling'][machine]"
                item-key="order.id"
                :animation="700"
                :group="`order-${order.date}-${machine}`"
                ghost-class="bg-green-200"
                :move="() => isUserHasFullPermissions"
                @change="$emit('submit', orderIndex, machine)"
            >
                <template v-slot:item="{ element: order }">
                    <div class="flex gap-4 items-center justify-center w-full border rounded-lg cursor-pointer"
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
                                    <button v-if="isUserHasFullPermissions"
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
                        <p class="w-6/12 px-5 text-center pointer">{{ order.order }}</p>
                        <p class="w-6/12 px-5 italic text-sm text-center">{{ order.NOTES }}</p>
                    </div>
                </template>
            </draggable>
        </div>
    </div>

    <Modal :show="isModalOpen" @close="isModalOpen = !isModalOpen; cancelOrder()" :maxWidth="'large'">
        <h2 class="uppercase text-center font-bold mt-4">
            {{ isEditedOrder ? 'Изменение' : 'Создание нового ' }} распоряжения
        </h2>
        <div class="px-6 py-1">
            <div class="flex flex-col gap-2 items-center">
                <input-label for="order">Распоряжение</input-label>
                <TextareaInput
                    id="order"
                    class="h-20 block w-full text-sm italic"
                    v-model="orderForm.order"
                    autocomplete="off"
                />
            </div>
        </div>
        <div class="px-6 py-1">
            <div class="flex flex-col gap-2 items-center">
                <input-label for="notes">Примечание</input-label>
                <TextareaInput
                    id="notes"
                    class="h-10 block w-full text-sm italic"
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
    </Modal>

</template>
