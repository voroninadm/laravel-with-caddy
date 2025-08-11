<script setup>
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {showNotification} from "@/common/notifications.js";

const props = defineProps(['userId'])


const form = useForm({
    user_id: props.userId,
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.patch(route('users.update-password'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showNotification('success', 'Пароль пользователя успешно обновлен!')
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Изменение пароля</h2>

            <p class="mt-1 text-sm text-gray-600">
                Используйте только в случае, если пользователь забыл пароль. Не забудьте оповестить пользователя о смене
                пароля!
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div class="flex gap-10">
                <div class="w-1/2">
                    <InputLabel for="password" value="Новый пароль"/>
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 h-8 block w-full"
                        autocomplete="new-password"
                    />
                    <InputError :message="form.errors.password" class="mt-2"/>
                </div>

                <div class="w-1/2">
                    <InputLabel for="password_confirmation" value="Подтвердите новый пароль"/>
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 h-8 block w-full"
                        autocomplete="new-password"
                    />
                    <InputError :message="form.errors.password_confirmation" class="mt-2"/>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Сохранено.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
