<script setup>
import {Head, useForm, Link} from '@inertiajs/vue3';
import {showNotification} from "@/common/notifications.js";
import {ref} from "vue";
import Modal from '@/components/Modal.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import InputError from '@/components/InputError.vue';
import Checkbox from '@/components/Checkbox.vue';
import Pagination from "@/components/modules/Pagination.vue";


const props = defineProps({
    workers: {
        required: true
    },
})

const workerToUpdateName = ref(null);
const isModalOpen = ref(false);

const form = useForm({
    worker_id: '',
    name: '',
    job:  '',
    category: '',
    is_blocked: false
});

const createWorker = () => {
    workerToUpdateName.value = null;
    isModalOpen.value = !isModalOpen.value
}

const submit = () => {
    if (workerToUpdateName.value) {
        form.patch(route('workers.update'), {
                onSuccess: () => {
                    isModalOpen.value = false;
                    showNotification('success', 'Профиль работника успешно обновлен!');
                    form.reset();
                    workerToUpdateName.value = null
                }
            }
        )
    } else {
        form.post(route('workers.store'), {
            onSuccess: () => {
                isModalOpen.value = false;
                showNotification('success', 'Работник успешно создан!');
                form.reset();
                workerToUpdateName.value = ''
            }
        })
    }
}

const cancel = () => {
    form.reset();
    isModalOpen.value = false;
    workerToUpdateName.value = ''
}

const edit = (id) => {
    isModalOpen.value = !isModalOpen.value;
    let worker = props.workers.data.find(worker => worker.id === id);
    workerToUpdateName.value = worker.name;
    form.worker_id = worker.id;
    form.name = worker.name;
    form.job = worker.job ? worker.job : '';
    form.category = worker.category ? worker.category : '';
    form.is_blocked = Boolean(worker.is_blocked);
}

const isBlockedUserIcon = (isBlocked) => {
    return isBlocked ? '💀' : ""
}
</script>

<template>
    <!--    <Head :title="Работники"><title>Hf</title></Head>-->

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <header class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Все работники</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Выбрав работника Вы, как администратор, можете внести изменения в его профиль
                    </p>
                </div>
                <div>
                    <SecondaryButton @click="createWorker">Создать работника</SecondaryButton>
                </div>
            </header>

            <div>
                <div class="w-full">
                    <div class="bg-white shadow-md rounded-sm my-6">
                        <table class="min-w-max w-full table-auto">
                            <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-center">Имя</th>
                                <th class="py-3 px-6 text-center hidden sm:table-cell">Должность</th>
                                <th class="py-3 px-6 text-center hidden sm:table-cell">Разряд/категория</th>
                                <th class="py-3 px-6 text-center hidden sm:table-cell">Заблокирован</th>
                                <th class="py-3 px-6 text-center">Действия</th>
                            </tr>
                            </thead>

                            <tbody class="text-gray-600 text-sm font-light">

                            <tr v-for="worker in props.workers.data" :key="worker.id"
                                class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-1 px-6 text-left tracking-wide">
                                    <p>{{ worker.name }}</p>
                                </td>

                                <td class="hidden md:table-cell py-1 px-6 text-center">
                                    <p>{{ worker.job }}</p>
                                </td>
                                <td class="hidden md:table-cell py-1 px-6 text-center">
                                    <p>{{ worker.category }}</p>
                                </td>
                                <td class="hidden md:table-cell py-1 px-6 text-center">
                                    <p>{{ isBlockedUserIcon(worker.is_blocked) }}</p>
                                </td>
                                <td class="py-1 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <div class="w-5 mx-3 transform hover:text-orange-500 hover:scale-110">
                                            <button type="button" class="w-5 h-5" @click="edit(worker.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div v-if="$page.props.auth.user.login === 'admin'" class="w-5 mx-3 transform hover:text-orange-500 hover:scale-110">
                                            <Link :href="route('workers.destroy', {id: worker.id})" method="post">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </Link>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination
                                :from="props.workers.from"
                                :to="props.workers.to"
                                :total="props.workers.total"
                                :prev_page_url="props.workers.prev_page_url"
                                :next_page_url="props.workers.next_page_url"
                                :links="props.workers.links"
                    />

                </div>
            </div>

            <Modal :show="isModalOpen" @close="isModalOpen = !isModalOpen">
                <form @submit.prevent="submit">
                    <h2 class="uppercase text-center font-bold mt-4">{{ workerToUpdateName ? `Изменение профиля "${workerToUpdateName}"` : 'Создание работника' }}</h2>
                    <div class="flex flex-col gap-3 p-6 text-gray-800">
                        <div class="flex gap-4 mt-2 items-center">
                            <input-label class="w-1/2" for="name">Введите имя</input-label>
                            <TextInput
                                id="name"
                                type="text"
                                class="h-6 block w-full"
                                v-model="form.name"
                                autocomplete="off"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.name"/>
                        </div>

                        <div class="flex gap-4 items-center">
                            <input-label class="w-1/2" for="job">Должность</input-label>
                            <TextInput
                                id="job"
                                type="text"
                                class="h-6 block w-full"
                                v-model="form.job"
                                autocomplete="off"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.job"/>
                        </div>
                        <div class="flex gap-4 items-center">
                            <input-label class="w-1/2" for="category">Разряд/категория</input-label>
                            <TextInput
                                id="category"
                                type="text"
                                class="h-6 block w-full"
                                v-model="form.category"
                                autocomplete="off"
                            />
                            <InputError class="mt-2" :message="form.errors.category"/>
                        </div>

                        <div class="flex justify-center mt-2">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.is_blocked"/>
                                <span class="ml-2 text-sm text-gray-600">Пользователь заблокирован</span>
                            </label>
                        </div>

                        <div class="flex gap-8 justify-center mt-10">
                            <PrimaryButton @click="submit" class="flex justify-center">Сохранить</PrimaryButton>
                            <SecondaryButton @click="cancel" class="flex justify-center">Отменить
                            </SecondaryButton>
                        </div>
                    </div>
                </form>
            </Modal>
        </div>
    </div>
</template>

<style scoped>
</style>
