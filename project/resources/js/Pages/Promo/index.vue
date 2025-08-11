<script setup>
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import InputError from '@/components/InputError.vue';
import {useForm, router} from "@inertiajs/vue3";
import SaveButton from "@/components/buttons/SaveButton.vue";
import {Head} from '@inertiajs/vue3';
import {currentDate, showLongCurrentDate} from "@/common/dateTimeHelper.js";

import TextareaInput from "@/components/TextareaInput.vue";
import axios from "axios";
import {ref, watch} from "vue";
import NumberInput from "@/components/NumberInput.vue";

const form = useForm({
    title: '',
    prefix: 'NTL*',
    postfix: '',
    usedSymbols: '1234567890abcdefghijklmnopqrstyvwxyzABCDEFGHIJKLMNOPQRSTYVWXYZ',
    blocks_count: 2,
    elements_in_block: 3,
    first_block: 2,
    codes_to_count: 1000,
    divide_element: ' '
});

const codeExample = () => {
    const usedSymbols = form.usedSymbols.split('');

    let promoCode = '';

    // Добавляем первый блок
    let firstBlock = '';
    for (let j = 0; j < form.first_block; j++) {
        const randomIndex = Math.floor(Math.random() * usedSymbols.length);
        firstBlock += usedSymbols[randomIndex];
    }
    promoCode += form.prefix + ' ' + firstBlock + ' ';

    // Добавляем остальные блоки
    for (let i = 0; i < form.blocks_count; i++) {
        let block = '';
        for (let j = 0; j < form.elements_in_block; j++) {
            const randomIndex = Math.floor(Math.random() * usedSymbols.length);
            block += usedSymbols[randomIndex];
        }
        promoCode += block + ' ';    }

    promoCode += form.postfix;

    return promoCode;
}

const isFormSubmitting = ref(false);
const formMessage = ref('');

const submit = () => {
    isFormSubmitting.value = true;
    formMessage.value = 'Подождите, выполняется генерация промокодов';

    form.post('/get-promo', {
        onError: () => {
            isFormSubmitting.value = false;
            formMessage.value = 'Произошла ошибка при выполнении запроса';
        },
        onFinish: () => {
            axios.get('/promoCodes.txt', { responseType: 'blob' })
                .then(response => {
                    if(response.status === 400) {
                        isFormSubmitting.value = false;
                        formMessage.value = 'Произошла ошибка при генерации промокодов';
                    }
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', `Промокоды ${form.title}.txt`);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    isFormSubmitting.value = false;
                    formMessage.value = '';
                    form.defaults();
                })
        }
    });
};

</script>

<template>
    <Head title="Генератор промо-кодов НТЛ"/>

    <section class="mt-5 w-11/12 mx-auto border-2 shadow-lg rounded-lg flex flex-col justify-center items-center">
        <h1 class="m-5 uppercase font-bold text-xl text-gray-600">Генератор промокодов НТЛ</h1>
        <p class="mt-5 flex items-center justify-center italic">Пример кода:
            <span class="px-5 text-lg font-bold not-italic text-red-700">{{ codeExample() }}</span>
        </p>
        <div class=" mt-5 p-4 flex flex-col items-center justify-center">
            <form class="flex flex-col gap-3 justify-center" @submit.prevent="submit">
                <div class="p-3 flex flex-col gap-2">

                    <div class="w-1/2 m-auto">
                        <input-label for="title">Наименование продукции для промокодов</input-label>
                        <TextInput
                            id="title"
                            class="mt-1 block w-full text-sm text-gray-600"
                            v-model="form.title"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.usedSymbols"/>
                    </div>

                    <div class="flex justify-center gap-6">
                        <div>
                            <input-label for="prefix">Префикс</input-label>
                            <TextInput
                                id="prefix"
                                class="h-6 block w-full text-gray-600"
                                v-model="form.prefix"
                            />
                            <InputError class="mt-2" :message="form.errors.prefix"/>
                        </div>
                        <div>
                            <input-label for="postfix">Постфикс</input-label>
                            <TextInput
                                id="postfix"
                                class="h-6 block w-full text-gray-600"
                                v-model="form.postfix"
                            />
                            <InputError class="mt-2" :message="form.errors.postfix"/>
                        </div>
                    </div>
                    <div>
                        <input-label for="usedSymbols">Используемые символы</input-label>
                        <TextareaInput
                            id="usedSymbols"
                            class="mt-1 block w-full text-sm text-gray-600"
                            v-model="form.usedSymbols"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.usedSymbols"/>
                    </div>

                    <div class="flex gap-2">
                        <div cl>
                            <input-label for="first_block">Количество знаков в первом блоке</input-label>
                            <NumberInput
                                id="first_block"
                                min="1"
                                max="10"
                                class="h-6 block w-full text-gray-600"
                                v-model.number="form.first_block"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.first_block"/>
                        </div>
                        <div>
                            <input-label for="blocks_count">Количество остальных блоков</input-label>
                            <NumberInput
                                id="blocks_count"
                                min="0"
                                max="5"
                                class="h-6 block w-full text-gray-600"
                                v-model.number="form.blocks_count"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.blocks_count"/>
                        </div>
                        <div>
                            <input-label for="elements_in_block">Количество знаков в этих блоках</input-label>
                            <NumberInput
                                id="elements_in_block"
                                min="0"
                                max="10"
                                class="h-6 block w-full text-gray-600"
                                v-model.number="form.elements_in_block"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.elements_in_block"/>
                        </div>
                    </div>
                    <div>
                        <input-label for="codes_to_count">Количество необходимых промо-кодов</input-label>
                        <NumberInput
                            id="codes_to_count"
                            min="10"
                            max="1000000"
                            class="h-6 block w-full text-gray-600"
                            v-model.number="form.codes_to_count"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.codes_to_count"/>
                    </div>

                </div>
                <div class="flex flex-col justify-center items-center">
                    <save-button :disabled="isFormSubmitting"/>
                    <p class="mt-2 text-gray-500 italic animate-pulse">{{ formMessage }}</p>
                </div>

            </form>
        </div>
    </section>
</template>
