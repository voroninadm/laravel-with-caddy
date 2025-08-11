<script setup>
import {ref} from 'vue';
import {useForm, Link, usePage} from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import NTLlogo from "@/components/icons/NTLlogo.vue";

const props = defineProps({
    errors: Object
})
const errors = props.errors || {}; // Получение ошибок из props

const loginInput = ref(null);

const form = useForm({
    login: '',
    password: '',
});

const submit = () => {
    form.post(route('testing.login'), {
        onSuccess: () => {
            form.reset();
        },
    });
}

</script>

<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';

export default {
    layout: GuestLayout
}
</script>
<template>
    <div class="w-full h-full flex flex-col items-center justify-center gap-10">
        <div class="m-4 bg-white rounded-lg p-2 md:w-4/12 shadow-2xl">
            <form class="flex flex-col" @submit.prevent="submit">
                <div>
                    <div class="py-3">
                        <NTLlogo class="block mx-auto"/>
                    </div>
                    <p class="text-center p-3 text-gray-500 uppercase">Анкетирование и тестирование</p>
                    <div>
                        <TextInput
                            id="login"
                            type="text"
                            class="mt-1 block w-full"
                            ref="loginInput"
                            v-model="form.login"
                            placeholder="Логин"
                            required
                            @change="form.errors.login = props.errors.permission = ''"
                        />

                    </div>

                    <div class="mt-3">

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            placeholder="Пароль"
                            required
                            @change="form.errors.password = form.errors.login = props.errors.permission = ''"
                        />

                        <div class="flex flex-col p-2 items-center h-8">
                            <InputError :message="form.errors.login"/>
                            <InputError :message="props.errors.permission"/>
                        </div>
                    </div>

                    <div class="flex justify-center mt-5 gap-10">

                        <Link :href="route('login')"
                              class="inline-flex items-center px-4 py-2 bg-white rounded-md font-semibold text-xs text-gray-500 uppercase tracking-widest  border border-gray-300 hover:bg-gray-200 active:bg-gray-200 transition ease-in-out duration-150 justify-center w-1/4">
                            На главную
                        </Link>
                        <PrimaryButton class="justify-center w-1/4"
                                       :class="{ 'opacity-25': form.processing }"
                                       :disabled="form.processing">
                            Войти
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
