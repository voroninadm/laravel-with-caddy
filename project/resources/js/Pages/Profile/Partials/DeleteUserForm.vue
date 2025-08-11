<script setup>
import DangerButton from '@/components/DangerButton.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import Modal from '@/components/Modal.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Удаление аккаунта</h2>

            <p class="mt-1 text-sm text-gray-600">
                Удаляя аккаунт будьте в курсе, что все ваши ресурсы, права доступа и данные будут безвозвратно утеряны!
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Я устал, удаляем</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 py-2">
                    Вы же случайно нажали на кнопку удаления?..
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Удаляя аккаунт будьте в курсе, что все ваши ресурсы, права доступа и данные будут безвозвратно утеряны!
                </p>

                <p class="mt-3 text-sm text-gray-600">
                    Подтвердите удаление аккаунта вводом своего пароля
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        placeholder="Ваш пароль"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-center">
                    <SecondaryButton @click="closeModal"> Я передумал </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Удаляем, решено
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
