<script setup>
import {Link, router, useForm} from "@inertiajs/vue3";
import TextInput from "@/components/TextInput.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import Pagination from "@/components/modules/Pagination.vue";

const props = defineProps({
    users: {
        required: true
    },
})

const form = useForm({
    user: '',
});

const findUser = () => {
    let foundUser = props.users.find(user =>
        user.name.toLowerCase() === (form.user.toLowerCase())
    )
    console.log(foundUser);

    if (foundUser) {
        router.visit(`/users/find/${foundUser.id}`, {
            method: 'get'
        })
    }
}
</script>

<template>
    <div class="relative">
        <header class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900">Все пользователи программы</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Выбрав пользователя Вы, как администратор, можете внести изменения в его профиль
                </p>
            </div>
            <form @submit.prevent="findUser" action="#" class="flex items-center gap-4">
                <TextInput v-model="form.user" placeholder="Поиск..." class="h-8"
                           list="users"/>
                <PrimaryButton>Найти и изменить</PrimaryButton>
            </form>
        </header>

        <div class="overflow-x-auto">
            <div class="w-full">
                <div class="bg-white shadow-md rounded-sm my-6">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-center">Имя</th>
                            <th class="py-3 px-6 text-center hidden md:table-cell">Логин</th>
                            <th class="py-3 px-6 text-center hidden sm:table-cell">Должность</th>
                            <th class="py-3 px-6 text-center">Действия</th>
                        </tr>
                        </thead>

                        <tbody class="text-gray-600 text-sm font-light">

                        <tr v-for="user in props.users" :key="user.id"
                            class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left">
                                <p>{{ user.name }}</p>
                            </td>

                            <td class="hidden md:table-cell py-3 px-6 text-center">
                                <p>{{ user.login }}</p>
                            </td>
                            <td class="hidden md:table-cell py-3 px-6 text-center">
                                <p>{{ user.job }}</p>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <div class="w-5 mx-3 transform hover:text-orange-500 hover:scale-110">
                                        <Link :href="route('users.edit', {id: user.id})">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </Link>
                                    </div>
                                    <div class="w-5 mx-3 transform hover:text-orange-500 hover:scale-110">
                                        <Link type="button" as="button" :href="route('users.destroy', {id: user.id})"
                                              :preserveScroll="true"
                                              method="post" class="w-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
            </div>
        </div>
        <Link :href="route('users.create')" class="absolute -top-0 -left-25 border-2 rounded-lg p-3 hover:bg-gray-100 transition duration-300">
            <svg class="w-8" xmlns="http://www.w3.org/2000/svg" viewBox="-2.5 0 32 32">
                <path
                    d="m18.7 21.8-5.5-2c-.2 0-.2 0-.2-.6 0-.5.2-1 .4-1.4.2-.4.5-1.2.6-1.9.2-.3.6-.9.8-2 .2-1 0-1.3 0-1.7-.1-.3 0-1.5.1-2.4.1-.7 0-2-1-3.1A4.6 4.6 0 0 0 10.5 5h-1c-2 0-3 1-3.5 1.7-.9 1-1 2.4-1 3l.3 2.4v.1c-.2.4-.3.7-.1 1.7.2 1.1.6 1.7.8 2 .1.7.4 1.5.6 2l.2 1.3-.1.7a64 64 0 0 0-5.6 2c-.8.3-1 1-1 1.5V26c0 .4.2 1 1 1H19c.8 0 1-.6 1-1v-2.6c0-.6-.4-1.3-1.2-1.6zM19 26H1v-2.6c0-.3.1-.5.4-.6l5.5-2c.9-.2.9-1 .9-1.6 0-.8-.1-1.4-.4-1.8-.1-.4-.4-1-.4-1.6v-.3l-.3-.3c-.1-.1-.4-.5-.6-1.5v-1.3c.2-.6 0-2-.1-2.8 0-.4 0-1.4.7-2.3.4-.5 1.2-1.2 2.7-1.3h1c1.2.1 2.2.5 2.8 1.3.7.9.7 2 .7 2.3-.2.8-.3 2.2-.2 2.7V12.7c.1.2.2.3 0 1.1-.1 1-.4 1.4-.5 1.5l-.2.3v.3l-.6 1.6c-.2.5-.5 1.1-.5 1.8 0 .6 0 1.4 1 1.7 1.6.5 4.3 1.4 5.4 1.9.4.1.6.4.6.6V26zM23 14.4v-4.1h-1v4.1h-4.2v1H22v4.3h1v-4.2h4.3v-1z"/>
            </svg>
        </Link>
    </div>


    <datalist id="users">
        <option v-for="user in users" :value="user.name"></option>
    </datalist>
</template>


<style scoped>

</style>
