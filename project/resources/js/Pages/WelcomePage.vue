<script setup>
import {ref} from 'vue';
import {useForm, Link} from '@inertiajs/vue3';
import Checkbox from '@/components/Checkbox.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import NTLlogo from '@/components/icons/NTLlogo.vue';
import Dropdown from "@/components/Dropdown.vue";
import DropdownLink from "@/components/DropdownLink.vue";

const loginInput = ref(null);

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('auth.login'), {
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
        <div class="m-4 bg-white rounded-lg p-2 md:w-4/12 drop-shadow-2xl shadow-lg shadow-gray-400">
            <form class="flex flex-col" @submit.prevent="submit">
                <div>
                    <div class="py-3">
                        <NTLlogo class="block mx-auto"/>
                    </div>
                    <div>
                        <InputLabel for="login" value="Логин"/>

                        <TextInput
                            id="login"
                            type="text"
                            class="mt-1 block w-full"
                            ref="loginInput"
                            v-model="form.login"
                            required
                            @change="form.errors.login = ''"
                        />

                        <InputError :message="form.errors.login"/>
                    </div>

                    <div class="mt-3">
                        <InputLabel for="password" value="Пароль"/>

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            @change="form.errors.password = ''; form.errors.login = ''"
                        />

                        <InputError class="mt-2" :message="form.errors.password"/>
                    </div>

                    <div class="flex justify-center mt-2">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember"/>
                            <span class="ml-2 text-sm text-gray-600">Запомнить меня</span>
                        </label>
                    </div>

                    <div class="flex justify-center mt-2">
                        <PrimaryButton class="justify-center w-1/4"
                                       :class="{ 'opacity-25': form.processing }"
                                       :disabled="form.processing">
                            Войти
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <Link href="/personal-testing/index"
                class="shadow-lg drop-shadow-sm px-10 py-2 text-sm font-medium uppercase rounded-md text-gray-600 bg-white hover:text-gray-700 hover:drop-shadow-xl focus:outline-hidden transition duration-150">
                <span class="font-bold text-red-700">NTL</span> Тестирование
            </Link>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.adding_icon {
    width: 25px;
    height: 25px;
    margin: 0 20px;
}
</style>
