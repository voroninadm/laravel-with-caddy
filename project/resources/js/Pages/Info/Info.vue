<script setup>
import {ref} from "vue";
import {useForm, router} from "@inertiajs/vue3";
import Modal from '@/components/Modal.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import {Link} from "@inertiajs/vue3";
import '@vuepic/vue-datepicker/dist/main.css';
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import TextareaInput from '@/components/TextareaInput.vue';
import InputError from '@/components/InputError.vue';
import Accordion from "@/Pages/Info/partials/Accordion.vue";
import {showNotification} from "@/common/notifications.js";
import Pagination from "@/components/modules/Pagination.vue";
import PlusIcon from "@/components/icons/PlusIcon.vue";


const props = defineProps({
    auth: Object,
    info_data: {
        required: true
    },
    sector_data: {
        required: true
    },
    current_sector: {
        required: true
    }
})

const isModalOpen = ref(false);
const infoIdUpdate = ref(null);

const date = ref();

const filterForm = useForm({
    start_date: null,
    finish_date: null,
});
const InfoForm = useForm({
    title: '',
    text: '',
    sector_id: null
});

const submitInfoForm = () => {
    if (infoIdUpdate.value !== null) {
        InfoForm.patch(`/info/update/${infoIdUpdate.value}`);
        showNotification('success', 'Объявление успешно изменено!');
        isModalOpen.value = false;
        InfoForm.reset();
        infoIdUpdate.value = null;
    } else {
        InfoForm.post(route('info.store'));
        showNotification('success', 'Объявление успешно создано!');
        isModalOpen.value = false;
        InfoForm.reset();
    }
}

const infoPrepareToUpdate = (messageId) => {
    isModalOpen.value = true;
    infoIdUpdate.value = messageId;
    let info = props.sector_data.data.find((message) => message.id === messageId);
    console.log(info);
    InfoForm.title = info.title;
    InfoForm.text = info.text;
    InfoForm.sector_id = (info.sector_id);
}

const deleteInfo = (id) => {
    router.delete(`/info/destroy/${id}`);
    showNotification('default', 'Объявление удалено!');

}

const cancelInfo = () => {
    isModalOpen.value = false;
    infoIdUpdate.value = null;
    InfoForm.reset();
}

function markAsRead(id, isRead) {
    if (!isRead) {
        router.patch(`/info/read/${id}`)
        router.reload({only: ['sector_data']})
    }
}
</script>

<template>
    <section class="flex gap-14 px-10 mt-5">
        <div class="flex flex-col w-72 bg-white rounded-xl shadow-lg sticky p-5 mt-3 h-min border border-gray-100">

            <div class="space-y-3">
                <div
                    v-for="sector in props.info_data"
                    :key="sector.id"
                    class="flex items-center justify-between group"
                >
                    <div class="flex items-center space-x-1">
                        <button
                            v-if="props.current_sector.id == sector.id"
                            type="button"
                            @click="isModalOpen = true; InfoForm.sector_id = sector.id"
                            class="p-1 rounded-full transition-all opacity-50 hover:opacity-100 hover:bg-red-50 hover:ring-1 hover:ring-red-200"
                        >
                            <PlusIcon class="w-4 h-4 text-gray-500 hover:text-red-600"/>
                        </button>

                        <Link
                            :href="`/info/${sector.name}`"
                            class="text-sm px-2 py-1 rounded-md transition-colors"
                            :class="{
                        'text-gray-600 hover:text-red-700 hover:drop-shadow hover:bg-gray-50': props.current_sector.id !== sector.id,
                        'bg-red-600 text-white shadow font-medium ': props.current_sector.id === sector.id
                    }"
                        >
                            {{ sector.title }}
                        </Link>
                    </div>

                    <p class="flex items-center space-x-1 text-sm font-bold">
                <span v-if="sector.unread_infos_count" class="text-red-600">
                    {{ sector.unread_infos_count }}
                </span>
                        <span v-if="sector.unread_infos_count && sector.infos_count" class="text-gray-400">
                    /
                </span>
                        <span v-if="sector.infos_count" class="text-gray-500">
                    {{ sector.infos_count }}
                </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col mt-4 w-full justify-between">
            <div class="flex flex-col w-full justify-between gap-2 pb-5">
                <Accordion v-for="message in props.sector_data.data"
                           :message="message"
                           class="mb-2"
                           @click="markAsRead(message.id, message.is_read)">
                    <pre class="whitespace-pre-wrap">{{ message.text }}</pre>
                    <div v-if="message.user_id === props.auth.user.id || props.auth.user.login === 'admin'">
                        <hr class="mt-4">
                        <p class="mt-1 flex gap-6 justify-end">
                            <Link v-if="!message.is_done" as="button" :href="`/info/finish/${message.id}`"
                                  method="patch"
                                  class="text-sm italic text-gray-600 hover:underline hover:text-gray-800">отметить как
                                выполненное
                            </Link>
                            <button
                                @click="infoPrepareToUpdate(message.id);"
                                class="text-sm italic text-gray-600 hover:underline hover:text-gray-800">изменить
                            </button>
                            <button @click="deleteInfo(message.id)"
                                    class="text-sm italic text-gray-600 hover:underline hover:text-gray-800">удалить
                            </button>
                        </p>
                    </div>
                </Accordion>
            </div>

            <Pagination v-if="props.sector_data.links.length > 3"
                        :from="props.sector_data.from"
                        :to="props.sector_data.to"
                        :total="props.sector_data.total"
                        :prev_page_url="props.sector_data.prev_page_url"
                        :next_page_url="props.sector_data.next_page_url"
                        :links="props.sector_data.links"
            />
        </div>
    </section>


    <Modal :max-width="'xxl'" :show="isModalOpen" @close="isModalOpen = !isModalOpen; InfoForm.sector_id = null">
        <form @submit.prevent="submitInfoForm">
            <div class="p-6 flex flex-col">
                <section class="text-center text-gray-800">
                    <h3 class="pb-4 font-bold flex flex-col">Объявление: <span class="text-red-900">#{{
                            props.current_sector.title
                        }}</span></h3>
                    <div class="flex flex-col items-center mb-4">
                        <input-label class="mb-1" for="title">Заголовок объявления</input-label>
                        <TextInput
                            id="title"
                            class="h-6 block w-full"
                            v-model="InfoForm.title"
                            autocomplete="off"
                            required
                        />
                        <InputError class="mt-2" :message="InfoForm.errors.title"/>
                    </div>
                    <div class="flex flex-col items-center mb-4">
                        <input-label class="mb-1" for="text">Текст объявления</input-label>
                        <TextareaInput
                            id="text"
                            class="h-36 block w-full"
                            v-model="InfoForm.text"
                            autocomplete="off"
                            required
                        />
                        <InputError class="mt-2" :message="InfoForm.errors.text"/>
                    </div>
                </section>

                <div class="flex gap-8 justify-center">
                    <PrimaryButton class="flex justify-center">Сохранить</PrimaryButton>
                    <SecondaryButton @click="cancelInfo" class="flex justify-center">Отменить</SecondaryButton>
                </div>
            </div>
        </form>
    </Modal>
</template>

<style scoped>
.input-radio.green:checked {
    box-shadow: 0 0 0 2px rgb(49, 136, 37);
    background-color: rgb(49, 136, 37);
}

.input-radio.blue:checked {
    box-shadow: 0 0 0 2px rgb(37, 39, 136);
    background-color: rgb(37, 39, 136);
}
</style>
