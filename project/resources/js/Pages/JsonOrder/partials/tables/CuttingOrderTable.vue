<script setup>

import {dateForJsonOrder, normalizeDate, normalizeStringDate} from "@/common/dateTimeHelper.js";
import AddTaskButton from "@/components/buttons/AddTaskButton.vue";
import Draggable from "vuedraggable";
import VueDatePicker from '@vuepic/vue-datepicker';
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
import moment from "moment";
import Smile from "@/components/icons/Smile.vue";
import AngrySmile from "@/components/icons/AngrySmile.vue";
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
    monitoringMode: Boolean,
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
    'saveOrder'
])

const isEditedOrder = ref(false); // имеется ли распоряжение для редактирования
const isModalOpen = ref(false);

const isUserHasFullPermissions = props.auth.permissions.includes(`full_cutting_orders_permission`);
const isUserHasMediumPermissions = props.auth.permissions.includes(`medium_cutting_orders_permission`);

//== форма для создания-изменения распоряжений
const orderForm = reactive({
    orderIndex: '',
    CARD_NUMBER: '',
    PLAN_PLEN_WELD_KG: null,
    CUSTOMER: '',
    PRODUCT_TITLE: '',
    NOTES: '',
    CREATED_AT: '',
    DEADLINE: ''
});

const resetOrderForm = () => {
    for (let key in orderForm) {
        if (orderForm.hasOwnProperty(key)) {
            orderForm[key] = '';
        }
    }
};


const prepareNewTask = (machineKey, orderIndex) => {
    orderForm.taskType = 'cutting';
    orderForm.machineKey = machineKey;
    orderForm.orderIndex = orderIndex;
    orderForm.CREATED_AT = dateForJsonOrder();
}

const editOrder = (task, machine, orderIndex) => {
    isModalOpen.value = true;
    isEditedOrder.value = true;
    orderForm.orderIndex = orderIndex;
    orderForm.taskType = 'cutting';
    orderForm.machineKey = machine;
    orderForm.CREATED_AT = task.CREATED_AT;
    orderForm.DEADLINE = task.DEADLINE;

    orderForm.CARD_NUMBER = task.CARD_NUMBER;
    orderForm.PLAN_PLEN_WELD_KG = task.PLAN_PLEN_WELD_KG ?? null;
    orderForm.CUSTOMER = task.CUSTOMER;
    orderForm.PRODUCT_TITLE = task.PRODUCT_TITLE;
    orderForm.FINISHED_AT = task.FINISHED_AT;
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

const showStatusImage = (order) => {
    if (order.STATUS === 'Finished' && order.DEADLINE) {
        const deadline_date = order.DEADLINE;
        const day = deadline_date.split('.')[0];
        const month = deadline_date.split('.')[1];
        const year = deadline_date.split('.')[2];
        const deadline_formatted = moment(`${year}-${month}-${day}`, 'YYYY-MM-DD');
        const finishing = new Date(order.FINISHED_AT);

        if (deadline_formatted.isAfter(finishing)) {
            return Smile;
        } else {
            return AngrySmile;
        }
    }
    return ''
}

// отслеживание изменений поля tkn и обращение к DB
watch(
    () => orderForm.CARD_NUMBER,
    (newNumber, oldNumber) => {
        if (!isEditedOrder.value && orderForm.CARD_NUMBER) {
            getTknDataFromDb(newNumber, oldNumber, (data) => {
                orderForm.CUSTOMER = data.customer;
                orderForm.PRODUCT_TITLE = data.print_title;
                orderForm.PLAN_PLEN_WELD_KG = +data.circulation_kg;
                orderForm.DEADLINE = data.finish_date.split(' ')[0];
            });
        }
    }
);

const { updateAndSatisfyTkn } = useTknUpdater(orderForm, 'CARD_NUMBER');

</script>

<template>
    <header class="sticky top-1 z-10">
        <div class="flex shadow-lg shadow-gray-800/20 gap-4 bg-gray-200 text-gray-500 uppercase text-sm leading-normal">
            <p class="w-7 text-center"></p>
            <p class="w-1/12 text-center">Карта</p>
            <p class="w-2/12 text-center">Заказчик</p>
            <p class="w-3/12 text-center">Заказ</p>
            <p class="w-2/12 text-center">Тираж</p>
            <p class="w-3/12 text-center">Примечание</p>
            <p class="w-1/12 text-center">Сроки работ</p>
        </div>
    </header>

    <div v-for="machine in machines">
        <div
            class="flex shadow-md shadow-gray-800/50 items-center border justify-center rounded-lg gap-2 w-40 my-2 mx-auto sticky top-7 p-1 text-gray-700 bg-gray-200 z-10">
            <p class="font-semibold">{{ machine }}</p>
        </div>
        <div v-for="(order, orderIndex) in props.order">

            <div v-show="!monitoringMode"
                 class="rounded-lg flex items-center my-1 px-1 transition duration-200 cursor-pointer">
                <AddTaskButton v-if="isUserHasFullPermissions"
                               @click="isModalOpen = !isModalOpen; prepareNewTask (machine, orderIndex)"
                               class="opacity-50 hover:opacity-100"
                />
                <p class="ml-4 leading-3 text-gray-600 opacity-50 hover:text-stone-800 hover:opacity-70 text-xs italic w-24 rounded-lg">
                    {{ normalizeDate(order.date) }}</p>
                <PasteTaskButton
                    v-if="isTaskInLocalStorage['cutting'] && isUserHasFullPermissions"
                    @click="$emit('putTask',machine, orderIndex)"
                    class="opacity-50 hover:opacity-100"
                />
                <span class="w-full h-0.5 gradient"></span>
            </div>

            <draggable
                :list="order['cutting'][machine]"
                item-key="order.id"
                :animation="700"
                :group="`order-${order.date}-${machine}`"
                ghost-class="bg-green-200"
                :move="() => isUserHasFullPermissions && !monitoringMode"
                @change="$emit('submit', orderIndex, machine)"
            >
                <template v-slot:item="{ element: order }">
                    <div class="flex my-1 gap-4 items-center justify-center w-full border rounded-lg cursor-pointer"
                         :class="orderStatuses[order.STATUS], {'border-double border-4 border-red-400' : order.CARD_NUMBER === props.searchedOrder },
{'text-lg' : monitoringMode}"
                         :data-card="order.CARD_NUMBER">

                        <Dropdown :open="false" v-if="isUserHasFullPermissions || isUserHasMediumPermissions">
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
                                    <button @click="editOrder(order, machine, orderIndex)"
                                            class="mt-4 block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150"
                                            type="button">
                                        Изменить
                                    </button>
                                    <button
                                        v-if="isUserHasFullPermissions"
                                        @click="$emit('cutTask',order, machine, orderIndex)"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                        type="button">
                                        Вырезать
                                    </button>
                                    <button
                                        v-if="isUserHasFullPermissions"
                                        @click="$emit('destroy',order,  machine, orderIndex)"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
                                        type="button">
                                        Удалить
                                    </button>
                                </div>
                            </template>
                        </Dropdown>
                        <p class="w-1/12 text-center pointer">{{ order.CARD_NUMBER }}</p>
                        <p class="w-2/12 text-center font-bold text-gray-500">{{ order.CUSTOMER }}</p>
                        <p class="w-3/12 text-center text-gray-700" :class="monitoringMode ? 'text-md' : 'text-sm'">{{ order.PRODUCT_TITLE }}</p>
                        <p class="w-1/12 text-center" :class="monitoringMode ? 'text-lg' : 'tтext-sm'">{{ order.PLAN_PLEN_WELD_KG ?? '' }}</p>
                        <p class="w-4/12 italic text-center" :class="monitoringMode ? 'text-md' : 'text-sm'">{{ order.NOTES }}</p>

                        <p v-if="order.STATUS !== 'Finished'"
                           class="w-1/12 text-right text-sm text-gray-500 ml-auto">
                            <span class="flex flex-col px-1 text-right">{{
                                    order.DEADLINE ? normalizeStringDate(order.DEADLINE) : ''
                                }}</span>
                        </p>
                        <p v-if="order.STATUS === 'Finished'"
                           class="flex w-1/12 text-right text-sm  ml-auto text-gray-500">
                            <component :is="showStatusImage(order)" class="m-auto"/>
                            <span class="flex flex-col ml-auto px-2">
                                {{ order.DEADLINE ? normalizeStringDate(order.DEADLINE) : '' }}
                                <br v-if="order.STATUS === 'Finished'">
                                {{ order.FINISHED_AT ? normalizeDate(order.FINISHED_AT) : '' }}
                            </span>
                        </p>
                    </div>
                </template>
            </draggable>
        </div>
    </div>

    <Modal :show="isModalOpen" @close="isModalOpen = !isModalOpen; cancelOrder()" :maxWidth="'large'">
        <h2 class="uppercase text-center font-bold mt-4">
            {{ isEditedOrder ? 'Изменение' : 'Создание нового ' }} распоряжения
        </h2>
        <div class="flex justify-center" @keyup.enter="saveOrder()">
            <div class="flex flex-col p-6">
                <section class="text-gray-800">

                    <div class="flex gap-4 mt-2">
                        <div class="flex gap-11 items-center">
                            <input-label class="w-11/12" for="tkn">Номер карты</input-label>
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
                        <div class="flex gap-4 items-center">
                            <input-label class="w-1/2" for="plan">План тиража</input-label>
                            <NumberInput
                                id="plan"
                                class="h-6 block w-full  text-sm"
                                v-model.number="orderForm.PLAN_PLEN_WELD_KG"
                                required
                                step="0.01"
                            />
                        </div>
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

                    <div v-if="isEditedOrder && isUserHasFullPermissions && orderForm.FINISHED_AT"
                         class="flex gap-10 relative">
                        <input-label class="" for="edit_finish">Изменить время завершения*</input-label>

                        <VueDatePicker v-model="orderForm.FINISHED_AT" day-picker :format="'dd.MM.yyyy'" locale="ru"
                                       text-input :enable-time-picker="false" id="edit_finish"
                                       :clearable="false" auto-apply/>
                        <span class="absolute top-10 text-xs text-orange-800 left-48">* Изменяйте время выполнения ответственно и только в крайних случаях</span>
                    </div>
                </section>
            </div>

        </div>
        <div class="px-6 py-1">
            <div class="flex flex-col gap-2 items-center">
                <input-label for="notes">Примечание</input-label>
                <TextareaInput
                    id="notes"
                    class="h-40 block w-full text-sm italic"
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
