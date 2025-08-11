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
    searchedSpoolcutter: {
        require: false
    },
    workers: {
        required: true
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

const spools = [
    '77/8',
    '77/10',
    '77/14',
    '153',
    '152.5/15 лам'
]

const selectedNotificationKey = ref('');
const notificateOnCreateOrder =  {
    'PE торф': "Проверь! Шпули 77/14, ширина шпули +1 мм",
    'PE гигиена': "Проверь! Шпули 77/14",
    'Триплекс/квадриплекс': "Проверь! Шпули 77/14",
    'Дуплекс PET+PE': "Проверь! Шпули 77/14",
    'Дуплекс BOPP+BOPP/CPP': "Проверь! Шпули 77/10",
    'Однослойный BOPP/CPP': "Проверь! Шпули 77/10",
    'Ламинация': "Проверь! Шпули 152.5 усиленная"
}

const isUserHasFullPermissions = props.auth.permissions.includes('full_spoolcutting_orders_permission');
const isUserHasMediumPermissions = props.auth.permissions.includes('medium_spoolcutting_orders_permission');

const isEditedOrder = ref(false); // имеется ли распоряжение для редактирования
const isModalOpen = ref(false);


//== форма для создания-изменения распоряжений
const orderForm = reactive({
    orderIndex: '',
    CARD_NUMBER: '',
    MASTER: '',
    SPOOLCUTTER: '',
    SPOOL_TYPE: '',
    SPOOL_WIDTH: '',
    SPOOL_PLAN: '',
    SPOOL_FACT: '',
    NOTES: '',
    CREATED_AT: ''
});

const cardNumberValid = ref(true);
const masterValid = ref(true);
const spoolcutterValid = ref(true);
const spooltypeValid = ref(true);
const spoolFactValid = ref(true);

const resetOrderForm = () => {
    for (let key in orderForm) {
        if (orderForm.hasOwnProperty(key)) {
            orderForm[key] = '';
        }
    }
};


const prepareNewTask = (machineKey, orderIndex) => {
    orderForm.taskType = 'spoolcutting';
    orderForm.machineKey = machineKey;
    orderForm.orderIndex = orderIndex;
    orderForm.CREATED_AT = dateForJsonOrder();
}

const editOrder = (task, machine, orderIndex) => {
    isModalOpen.value = true;
    isEditedOrder.value = true;
    orderForm.orderIndex = orderIndex;
    orderForm.taskType = 'spoolcutting';
    orderForm.machineKey = machine;
    orderForm.CREATED_AT = task.CREATED_AT;

    orderForm.CARD_NUMBER = task.CARD_NUMBER;
    orderForm.MASTER = task.MASTER;
    orderForm.SPOOLCUTTER = task.SPOOLCUTTER;
    orderForm.SPOOL_TYPE = task.SPOOL_TYPE;
    orderForm.SPOOL_WIDTH = task.SPOOL_WIDTH;
    orderForm.SPOOL_PLAN = task.SPOOL_PLAN;
    orderForm.SPOOL_FACT = task.SPOOL_FACT;
    orderForm.NOTES = task.NOTES;
}

const cancelOrder = () => {
    isModalOpen.value = false;
    isEditedOrder.value = false
    selectedNotificationKey.value = '';
    cardNumberValid.value = true;
    masterValid.value = true;
    spoolcutterValid.value = true;
    spooltypeValid.value = true;
    spoolFactValid.value = true;
    resetOrderForm();
}

const isValidForm = () => {
    return cardNumberValid.value &&
        masterValid.value &&
        spoolcutterValid.value &&
        spooltypeValid.value &&
        spoolFactValid.value
};

const saveOrder = () => {
    validation();
    if (!isValidForm()) {
        return;
    }
    emit('saveOrder', orderForm, isEditedOrder);
    isModalOpen.value = false;
    selectedNotificationKey.value = '';
    resetOrderForm();
}

function validation() {
    cardNumberValid.value = !!orderForm.CARD_NUMBER.trim();
    masterValid.value = !!props.workers.includes(orderForm.MASTER);
    spooltypeValid.value = !!spools.includes(orderForm.SPOOL_TYPE);
    spoolFactValid.value = +orderForm.SPOOL_FACT <= +orderForm.SPOOL_PLAN;
    if (orderForm.SPOOLCUTTER) {
    spoolcutterValid.value = !!props.workers.includes(orderForm.SPOOLCUTTER);
    }
}

const { updateAndSatisfyTkn } = useTknUpdater(orderForm, 'CARD_NUMBER');

</script>

<template>
    <header class="sticky top-1 z-10">
        <div class="flex shadow-lg shadow-gray-800/20 gap-4 bg-gray-200 text-gray-500 uppercase text-sm leading-normal">
            <p class="w-7 text-center"></p>
            <p class="w-1/12 text-center">Карта</p>
            <p class="w-2/12 text-center">Мастер</p>
            <p class="w-2/12 text-center">Шпулерез</p>
            <p class="w-1/12 text-center">Тип шпули</p>
            <p class="w-1/12 text-center">Ширина</p>
            <p class="w-2/12 text-center">Количество</p>
            <p class="w-3/12 text-center">Примечание</p>
        </div>
    </header>

    <div v-for="machine in machines">
        <div
            class="flex shadow-md shadow-gray-800/50 items-center border justify-center rounded-lg gap-2 w-40 my-2 mx-auto sticky top-7 p-1 text-gray-700 bg-gray-200 z-10">
            <p class="font-semibold">{{ machine }}</p>
        </div>
        <div v-for="(order, orderIndex) in props.order">

            <div class="rounded-lg flex items-center my-1 px-1 transition duration-200 cursor-pointer">
                <AddTaskButton v-if="isUserHasMediumPermissions || isUserHasFullPermissions"
                               @click="isModalOpen = !isModalOpen; prepareNewTask (machine, orderIndex)"
                               class="opacity-50 hover:opacity-100"
                />
                <p class="ml-4 leading-3 text-gray-600 opacity-50 hover:text-stone-800 hover:opacity-70 text-xs italic w-24 rounded-lg">
                    {{ normalizeDate(order.date) }}</p>
                <PasteTaskButton v-if="isTaskInLocalStorage['spoolcutting']"
                                 @click="$emit('putTask',machine, orderIndex)"
                                 class="opacity-50 hover:opacity-100"
                />
                <span class="w-full h-0.5 gradient"></span>
            </div>

            <draggable
                :list="order['spoolcutting'][machine]"
                item-key="order.id"
                :animation="700"
                :group="`order-${order.date}-${machine}`"
                ghost-class="bg-green-200"
                :move="() =>isUserHasMediumPermissions || isUserHasFullPermissions"
                @change="$emit('submit', orderIndex, machine)"
            >
                <template v-slot:item="{ element: order }">
                    <div class="flex my-1 gap-4 items-center justify-center w-full border rounded-lg cursor-pointer"
                         :class="orderStatuses[order.STATUS], {'border-double border-4 border-red-400' : order.CARD_NUMBER === props.searchedOrder || order.SPOOLCUTTER === props.searchedSpoolcutter}"
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
                                    <button v-if="order.STATUS !== 'Finished'"
                                            @click="editOrder(order, machine, orderIndex)"
                                            class="mt-4 block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150"
                                            type="button">
                                        Изменить
                                    </button>
                                    <button v-if="isUserHasFullPermissions"
                                            @click="$emit('cutTask',order, machine, orderIndex)"
                                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                            type="button">
                                        Вырезать
                                    </button>
                                    <button v-if="isUserHasFullPermissions"
                                            @click="$emit('destroy',order,  machine, orderIndex)"
                                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                            type="button">
                                        Удалить
                                    </button>
                                </div>
                            </template>
                        </Dropdown>
                        <p class="w-1/12 text-center pointer">{{ order.CARD_NUMBER }}</p>
                        <p class="w-2/12 text-center text-sm text-gray-500">{{ order.MASTER }}</p>
                        <p class="w-2/12 text-center text-sm text-gray-500">{{ order.SPOOLCUTTER }}</p>
                        <p class="w-1/12 text-center font-bold">{{ order.SPOOL_TYPE ?? '' }}</p>
                        <p class="w-1/12 text-center font-bold">{{ order.SPOOL_WIDTH ?? '' }}</p>
                        <p class="w-2/12 font-bold text-center">{{ order.SPOOL_FACT ?? 0 }} /
                            {{ order.SPOOL_PLAN ?? 0 }}</p>
                        <p class="w-3/12 italic text-sm text-center">{{ order.NOTES }}</p>
                    </div>
                </template>
            </draggable>
        </div>
    </div>

    <Modal :show="isModalOpen" @close="isModalOpen = !isModalOpen; cancelOrder()" :maxWidth="'large'">
        <h2 class="uppercase text-center font-bold mt-4">
            {{ isEditedOrder ? 'Изменение' : 'Создание нового ' }} распоряжения
        </h2>
        <form class="flex flex-col" @keyup.enter="saveOrder()" @submit.prevent="saveOrder()">
            <div class="flex flex-col p-6">
                <section class="text-gray-800">
                    <div class="flex gap-4">
                        <div class="flex flex-col w-1/2 ">
                            <input-label class="w-1/2" for="tkn">Номер карты</input-label>
                            <TextInput
                                id="tkn"
                                type="text"
                                class="h-6 block w-full text-sm"
                                :class="{'bg-gray-200' : isEditedOrder, 'border-red-500' : !!cardNumberValid.value}"
                                v-model="orderForm.CARD_NUMBER"
                                @input="updateAndSatisfyTkn"
                                autocomplete="off"
                                :disabled="isEditedOrder"
                                required
                            />
                        </div>
                        <div class="flex flex-col w-1/2">
                            <input-label class="w-1/2" for="master">Мастер</input-label>
                            <TextInput
                                id="master"
                                type="text"
                                class="h-6 block w-full  text-sm"
                                v-model="orderForm.MASTER"
                                :disabled="isEditedOrder && isUserHasMediumPermissions"
                                :class="{'bg-gray-200': isEditedOrder && isUserHasMediumPermissions, 'border-red-500' : !masterValid}"
                                autocomplete="off"
                                required
                                list="workers"
                            />
                        </div>
                        <div class="flex flex-col w-1/2">
                            <input-label class="w-1/2" for="tkn">Шпулерез</input-label>
                            <TextInput
                                id="tkn"
                                type="text"
                                class="h-6 block w-full text-sm"
                                :class="{'border-red-500' : !spoolcutterValid, 'bg-gray-200': !isEditedOrder}"
                                v-model="orderForm.SPOOLCUTTER"
                                :disabled="!isEditedOrder"
                                autocomplete="off"
                                list="workers"
                            />
                        </div>
                    </div>
                    <div class="flex justify-center gap-4 mt-4">
                        <div class="flex gap-4 items-center">
                            <input-label class="w-1/4" for="tkn">Тип шпули</input-label>
                            <TextInput
                                id="tkn"
                                type="text"
                                class="h-6 block w-full  text-sm"
                                :class="{'border-red-500' : !spooltypeValid}"
                                v-model="orderForm.SPOOL_TYPE"
                                autocomplete="off"
                                required
                                list="spool_types"
                            />
                        </div>
                        <div class="flex gap-11 items-center">
                            <input-label class="w-1/4" for="width">Ширина, мм</input-label>
                            <TextInput
                                id="width"
                                type="number"
                                class="h-6 block w-20 text-sm"
                                v-model="orderForm.SPOOL_WIDTH"
                                autocomplete="off"
                                required
                                min="1"
                            />
                        </div>
                        <div class="flex gap-11 items-center">
                            <input-label class="w-1/4" for="plan">План</input-label>
                            <TextInput
                                id="plan"
                                type="number"
                                class="h-6 block w-20 text-sm"
                                v-model="orderForm.SPOOL_PLAN"
                                autocomplete="off"
                                required
                                min="1"
                            />
                        </div>
                        <div class="flex gap-4 items-center">
                            <input-label class="w-1/4" for="tkn">Факт</input-label>
                            <TextInput
                                id="tkn"
                                type="number"
                                class="h-6 block w-20 text-sm"
                                :class="{'bg-gray-200': !isEditedOrder}"
                                :disabled="!isEditedOrder"
                                v-model="orderForm.SPOOL_FACT"
                                autocomplete="off"
                                min="0"
                                :max="orderForm.SPOOL_PLAN"
                            />
                        </div>
                    </div>
                </section>
            </div>

            <div v-if="!isEditedOrder">
                <div class="relative flex flex-col items-center justify-between gap-2 pb-12">
                    <select v-model="selectedNotificationKey"
                            class="cursor-pointer text-sm border border-gray-300 ring-offset-black ring-0 focus:ring-0 h-9 rounded-sm text-gray-500 ring-red-200 select-custom">
                        <option value="" disabled class="text-gray-600 italic">-- Выберите тип материала--</option>
                        <option v-for="(value, key) in notificateOnCreateOrder"
                                :key="key"
                                :value="key"
                                class="text-sm"
                        >
                            {{ key }}
                        </option>
                    </select>

                    <p class="absolute top-8 text-orange-600 italic   my-2 animate-pulse">{{ notificateOnCreateOrder[selectedNotificationKey] ?? '' }}</p>
                </div>
            </div>

            <div class="px-6">
                <div class="flex flex-col gap-2 items-center">
                    <input-label for="notes">Примечание</input-label>
                    <TextareaInput
                        id="notes"
                        type="text"
                        class="h-20 block w-full text-sm italic"
                        v-model="orderForm.NOTES"
                        autocomplete="off"
                    />
                </div>
            </div>

            <div class="m-auto">
                <p v-if="!isValidForm()" class="text-red-800 text-sm">Форма содержит одну или несколько ошибок и не
                    может быть сохранена</p>
            </div>

            <div class="flex gap-8 justify-center p-4">
                <PrimaryButton @click="saveOrder()" class="flex justify-center">
                    Сохранить
                </PrimaryButton>
                <SecondaryButton @click="cancelOrder" class="flex justify-center">Отменить</SecondaryButton>
            </div>

            <datalist id="spool_types">
                <option v-for="spool in spools" :value="spool"></option>
            </datalist>

            <datalist id="workers">
                <option v-for="worker in workers" :value="worker"></option>
            </datalist>            <datalist id="workers">
                <option v-for="worker in workers" :value="worker"></option>
            </datalist>
        </form>
    </Modal>

</template>

<style lang="scss" scoped>
.select-custom {
    appearance: none;
    outline-offset: 0;

    &:focus-within {
        border: none;
        outline-color: rgba(240, 128, 128, 0.71);
    }

    &:focus-visible {
        outline: none;
        border-color: lightgray;

    }
}
</style>
