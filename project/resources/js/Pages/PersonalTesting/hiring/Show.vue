<script setup>

import {
    daysFromCreate,
    normalizeDate,
} from "@/common/dateTimeHelper.js";
import PDFicon from "@/components/icons/PDFicon.vue";
import {Link, useForm} from "@inertiajs/vue3";
import HirePersonIcon from "@/components/icons/PersonalTesting/HirePersonIcon.vue";
import {computed, reactive, ref, watch} from "vue";
import Modal from "@/components/Modal.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import TextInput from "@/components/TextInput.vue";
import InputLabel from "@/components/InputLabel.vue";
import InputError from "@/components/InputError.vue";
import {showNotification} from "@/common/notifications.js";
import OpenBookIcon from "@/components/icons/OpenBookIcon.vue";
import DateInput from "@/components/DateInput.vue";
import Spinner from "@/components/Spinner.vue";


const props = defineProps({
    hiringData: {
        required: true
    },
    workers: {
        required: true
    },
    filterData: {
        required: true
    }
})

const hireForm = useForm({
    id: '',
    recommender_id: '',
    hired_at: ''
})

const handleFocus = ref('null');


const modalOpen = ref(false);
const setData = (person) => {
    hireForm.id = person.id;
    person.recommender_id ? hireForm.recommender_id = person.recommender_id : '';
    person.hired_at ? hireForm.hired_at = person.hired_at : '';
}

const monthlyHiredCount = computed(() => {
    return props.hiringData.filter((item) => item.hired_at).length;
});

const submit = async () => {
    hireForm.patch(`/personal-testing/hiring/update/${hireForm.id}`, {
        onSuccess: () => {
            showNotification('success', 'Информация анкетируемого успешно обновлена!');
            const person = props.hiringData.find(item => item.id === hireForm.id);
            person.recommender_id = hireForm.recommender_id;
            person.hired_at = hireForm.hired_at;
            modalOpen.value = false;
            hireForm.reset();
            handleFocus.value = null;
        },
        onError: () => {
            showNotification('error', "Ошибка при сохранении данных. Проверьте, правильно ли заполнены поля.");
        }
    });
}

const isLoading = ref(false);

const downloadPDF = async (person) => {
    isLoading.value = person.id;
    try {
        const response = await fetch(`/personal-testing/hiring/download/${person.id}`, {
            method: 'GET',
        });

        if (!response.ok) console.log('Ошибка загрузки');

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = person.about.last_name + ' ' + person.about.first_name + ' - анкета соискателя'; // Задаем имя файла
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <aside class="px-8 py-4 gap-4 mx-auto">
        <div class="grid grid-cols-3 px-2 py-1">
            <div class="flex m-auto">
                <Link v-if="filterData.hasPreviousMonth"
                      :href="`/personal-testing/hiring/show/${filterData.previousMonth}`"
                      as="button" class="p-1 border rounded-full hover:bg-gray-100 transition duration-300">
                    <svg class="w-10 h-10 rotate-90" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24">
                        <path fill="lightgray"
                              d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                    </svg>
                </Link>
            </div>
            <div class="flex flex-col items-center justify-center gap-4">
                <p class="text-white text-xl capitalize py-1 px-2 rounded-xl bg-gray-600 shadow-lg">
                    {{ filterData.currentMonth }}</p>
                <div class="flex gap-20 text-gray-700 tracking-wide text-sm">
                    <p>Анкет за месяц:
                        <span class="px-2 py-1 rounded-full border shadow-sm font-bold ">{{ hiringData.length }}</span>
                    </p>
                    <p>Трудоустроены за месяц:
                        <span class="px-2 py-1 rounded-full border shadow-sm font-bold"
                              :class="{'bg-green-50 text-green-700': monthlyHiredCount > 0}"
                        >{{ monthlyHiredCount }}</span>
                    </p>
                </div>
            </div>
            <div class="flex m-auto">
                <Link v-if="filterData.hasNextMonth" :href="`/personal-testing/hiring/show/${filterData.nextMonth}`"
                      as="button" class="p-1 border rounded-full hover:bg-gray-100 transition duration-300">
                    <svg class="w-12 h-12 -rotate-90" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24">
                        <path fill="lightgray"
                              d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                    </svg>
                </Link>
            </div>
        </div>
    </aside>

    <section class="flex px-8 gap-4">
        <div class=" py-4 w-full">
            <div class="overflow-x-auto">
                <div class="inline-block w-full rounded-lg overflow-hidden">
                    <table class="leading-normal border w-11/12 m-auto">
                        <thead class="sticky top-0">
                        <tr>
                            <th
                                class="px-8 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                            >
                                Данные анкетируемого
                            </th>
                            <th
                                class="px-3 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                            >
                                Вакансия
                            </th>
                            <th
                                class="px-3 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                            >
                                Дата анкетирования
                            </th>
                            <th
                                class="px-3 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                            >
                                Рекомендатор
                            </th>
                            <th
                                class="px-3 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                            >
                                Трудоустроен
                            </th>
                            <th
                                class="px-3 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                            >
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="person in hiringData" :key="person.id"
                            class="border-b bg-white"
                            :class="{ 'bg-green-50': person.hired_at}">
                            <td class="px-5 py-3 border-gray-200">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-gray-800 whitespace-no-wrap">
                                            {{ person.about.last_name }}
                                            {{ person.about.first_name }}
                                            {{ person.about?.middle_name }}
                                        </p>
                                        <p class="text-gray-500 whitespace-no-wrap text-xs">{{
                                                person.about.phone
                                            }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 border-gray-200 text-sm">
                                <p class="text-gray-900">{{ person.about.vacancy }}</p>
                            </td>
                            <td class="px-3 py-3 border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap text-sm">{{
                                        normalizeDate(person.created_at)
                                    }}</p>
                                <p class="text-gray-500 whitespace-no-wrap text-xs">
                                    {{ daysFromCreate(person.created_at) }}</p>
                            </td>
                            <td class="px-3 py-3 border-gray-200 text-sm">
                                <form v-if="!person.recommender_id"
                                      @submit.prevent="submit">
                                    <div class="flex gap-2 items-center">
                                        <input-label class="hidden" for="master_id">Рекомендатор</input-label>
                                        <TextInput
                                            id="master_id"
                                            class="h-6 block w-1/2 text-sm"
                                            v-model="hireForm.recommender_id"
                                            :value="hireForm.id === person.id ? hireForm.recommender_id : ''"
                                            @focus="hireForm.reset(); setData(person); handleFocus = 'recommender_id'"
                                            autocomplete="off"
                                            list="workers"
                                        />
                                        <div class="w-20 flex gap-2 items-center">
                                            <button
                                                v-if="hireForm.id === person.id && handleFocus === 'recommender_id' && hireForm.recommender_id !== ''"
                                                type="submit"
                                                class="border py-1 px-2 bg-green-100 border-green-600 text-green-600 rounded-full">
                                                V
                                            </button>
                                            <button v-if="hireForm.id === person.id && handleFocus === 'recommender_id'"
                                                    type="button"
                                                    @click="hireForm.reset(); hireForm.clearErrors()"
                                                    class="border py-1 px-2 bg-red-100 border-red-600 text-red-600 rounded-full">
                                                X
                                            </button>
                                        </div>
                                        <InputError :message="hireForm.errors.recommender_id"
                                                    v-if="hireForm.id === person.id"/>
                                    </div>
                                </form>
                                <span v-else>
                                    {{ person.recommender_id }}
                                </span>
                            </td>
                            <td class="px-3 py-3 border-gray-200 text-sm">
                                <form v-if="!person.hired_at"
                                      @submit.prevent="submit">
                                    <div class="flex gap-2 items-center">
                                        <input-label class="hidden" for="hired_at">Трудоустроен</input-label>
                                        <DateInput
                                            id="hired_at"
                                            class="h-7 block w-40 py-0.5 text-sm rounded-sm border-gray-300"
                                            v-model="hireForm.hired_at"
                                            :value="hireForm.id === person.id ? hireForm.hired_at : ''"
                                            @focus="hireForm.reset(); setData(person); handleFocus = 'hired_at'"
                                        />
                                        <InputError class="mt-2" :message="hireForm.errors.hired_at"/>
                                        <div class="w-20 flex gap-2 items-center">
                                            <button
                                                v-if="hireForm.id === person.id && handleFocus === 'hired_at' && hireForm.hired_at !== ''"
                                                type="submit"
                                                class="border py-1 px-2 bg-green-100 border-green-600 text-green-600 rounded-full">
                                                V
                                            </button>
                                            <button v-if="hireForm.id === person.id && handleFocus === 'hired_at'"
                                                    type="button"
                                                    @click="hireForm.reset(); hireForm.clearErrors()"
                                                    class="border py-1 px-2 bg-red-100 border-red-600 text-red-600 rounded-full">
                                                X
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <span v-else>
                                    {{ person.hired_at }}
                                </span>
                            </td>
                            <td class="px-3 py-3 border-gray-200 grid grid-cols-3 items-center justify-center text-sm">
                                <button v-if="$page.props.auth.user.login === 'admin'" type="button"
                                        class="col-start-1 w-min">
                                    <HirePersonIcon class="w-10 h-10"
                                                    @click="modalOpen = !modalOpen; setData(person)"/>
                                </button>
                                <Link :href="`/personal-testing/hiring/performance/${person.id}`"
                                      class="col-start-2 w-min ">
                                    <OpenBookIcon class="w-10 h-10"/>
                                </Link>
                                <button v-if="!isLoading || isLoading !== person.id"
                                        @click="downloadPDF(person)"
                                        class="relative" type="button"
                                        :disabled="isLoading">
                                    <PDFicon class="w-10 h-10" />
                                </button>
                                <Spinner v-if="isLoading === person.id" class="w-10 h-10" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <Modal :show="modalOpen" :max-width="'large'">
        <section class="p-4">
            <form @submit.prevent="submit()" class="flex flex-col gap-5">
                <div class="flex items-center">
                    <input-label class="w-1/2" for="master_id">Рекомендатор</input-label>
                    <TextInput
                        id="master_id"
                        class="h-6 block w-full  text-sm"
                        v-model="hireForm.recommender_id"
                        autocomplete="off"
                        list="workers"
                    />
                    <InputError :message="hireForm.errors.recommender_id"/>

                </div>

                <div class="flex items-center">
                    <input-label class="w-1/2" for="hired_at">Трудоустроен</input-label>
                    <input
                        type="date"
                        id="hired_at"
                        class="block w-full rounded-lg border-gray-300"
                        v-model="hireForm.hired_at"
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="hireForm.errors.hired_at"/>
                </div>

                <div class="flex gap-10 items-center justify-center">
                    <PrimaryButton>Сохранить</PrimaryButton>
                    <SecondaryButton @click="modalOpen = false; hireForm.reset()">Отменить</SecondaryButton>
                </div>
            </form>
        </section>
    </Modal>

    <datalist id="workers">
        <option v-for="worker in workers" :value="worker"></option>
    </datalist>
</template>

<style scoped>

</style>
