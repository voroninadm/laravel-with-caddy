<script setup>
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {showNotification} from '@/common/notifications.js';
import Checkbox from '@/components/Checkbox.vue';
import {ref, watch} from "vue";


const props = defineProps({
    sectors: {
        required: true
    }
});
const currentBookmark = ref('elog');

const form = useForm({
    name: '',
    login: '',
    email: '',
    job: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    permissions: {
        set_permissions: false,
        open_users_profiles: false,
        open_workers_profiles: false,
        open_apps: false,
        open_tests: false,
        printing_permission: 'basic',
        lamination_permission: 'basic',
        welding_permission: 'basic',
        cutting_permission: 'basic',
        extrusion_permission: 'basic',
        recycling_permission: 'basic',
        upff_permission: 'basic',
        printing_orders_permission: 'basic',
        lamination_orders_permission: 'basic',
        welding_orders_permission: 'basic',
        cutting_orders_permission: 'basic',
        extrusion_orders_permission: 'basic',
        recycling_orders_permission: 'basic',
        spoolcutting_orders_permission: 'basic',
    },
        infos_permissions: Object.fromEntries(
            props.sectors.map(sector => [
                sector.name, sector.name === 'General' ? true : false
            ])
    )
});

const changeUserRole = () => {
    form.permissions.set_permissions = false;
    form.permissions.open_users_profiles = false;
    form.permissions.open_workers_profiles = false;
    form.permissions.open_apps = false;
    form.permissions.open_tests = false;
    form.permissions.printing_permission = 'basic';
    form.permissions.lamination_permission = 'basic';
    form.permissions.welding_permission = 'basic';
    form.permissions.cutting_permission = 'basic';
    form.permissions.extrusion_permission = 'basic';
    form.permissions.recycling_permission = 'basic';
    form.permissions.upff_permission = 'basic';
    form.permissions.printing_orders_permission = 'basic';
    form.permissions.lamination_orders_permission = 'basic';
    form.permissions.welding_orders_permission = 'basic';
    form.permissions.cutting_orders_permission = 'basic';
    form.permissions.extrusion_orders_permission = 'basic';
    form.permissions.recycling_orders_permission = 'basic';
    form.permissions.spoolcutting_orders_permission = 'basic';
}

watch(() => form.role, (newRole) => {
    form.infos_permissions = Object.fromEntries(
        props.sectors.map(sector => [
            sector.name, newRole === 'user' && sector.name === 'General' ? true : false
        ])
    );
});


const submit = () => {
    form.post(route('users.register'), {
            onSuccess: () => {
                showNotification('success', 'Профиль пользователя успешно создан!')
            }
        }
    )
}

watch(() => form.role, (newRole) => {
    if (newRole === "user") {
        form.infos_permissions['General'] = true;
    }
});
</script>

<template>
    <div class="py-5">
<!--        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">-->

            <div class="max-w-7xl mx-auto p-4 sm:p-8 bg-white shadow-lg sm:rounded-lg">
    <section>
        <header>
            <h2 class="text-lg text-center font-medium py-2">{{ $page.props.auth.user.name }}, Вы решили создать нового пользователя</h2>

            <blockquote class="mt-1 text-sm text-gray-600 text-center text-orange-900">
                Выбрав роль "пользователь с настраиваемыми правами доступа" вы создаете учетную запись, использовать которую могут только
                сотрудники, непосредственно работающие на территории предприятия!
            </blockquote>
        </header>

        <hr class="mt-5">
        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div class="grid grid-cols-3 items-center w-full gap-10 justify-between">
                <div class="flex w-full gap-4">
                    <InputLabel class="w-1/3" for="name" value="Имя"/>
                    <TextInput
                        id="name"
                        type="text"
                        class="h-6 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.name"/>
                </div>
                <div class="flex items-center w-full gap-4">
                    <InputLabel class="w-1/3" for="email" value="Email"/>
                    <TextInput
                        id="email"
                        type="email"
                        class="h-6 block w-full"
                        v-model="form.email"
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.email"/>
                </div>
                <div class="flex items-center w-full gap-4">
                    <InputLabel class="w-1/4" for="job" value="Должность"/>
                    <TextInput
                        id="job"
                        type="text"
                        class="h-6 block w-full"
                        v-model="form.job"
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.job"/>
                </div>
            </div>

            <div class="grid grid-cols-3 items-center w-full gap-10 justify-between">
                <div class="flex w-full gap-4">
                    <InputLabel class="w-1/3" for="login" value="Логин"/>
                    <TextInput
                        id="login"
                        type="text"
                        class="h-6 block w-full"
                        v-model="form.login"
                        required
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.login"/>
                </div>
                <div class="flex items-center w-full gap-4">
                    <InputLabel class="w-1/3" for="password" value="Пароль"/>
                    <TextInput
                        id="password"
                        type="password"
                        class="h-6 block w-full"
                        v-model="form.password"
                        ref="passwordInput"
                        required
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.password"/>
                </div>
                <div class="flex items-center w-full gap-4">
                    <InputLabel class="w-1/3" for="password_confirmation" value="Подтверждение пароля"/>
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="h-6 block w-full"
                        v-model="form.password_confirmation"
                        ref="passwordConfirmation"
                        required
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation"/>
                </div>
            </div>

            <div class="flex flex-col items-center gap-4 items-center p-4">
                <div class="flex gap-2 items-center">
                    <input-label class="text-gray-600 italic">Вы можете выбрать роль пользователя:
                    </input-label>
                    <div class="flex items-center gap-6 ml-10">
                        <label class="radio-label text-sm">
                            <input @change=changeUserRole type="radio" class="input-radio gray" v-model="form.role"
                                   value="manager">
                            <span class="ml-2">Менеджер</span>
                        </label>
                        <label class="radio-label text-sm">
                            <input @change=changeUserRole type="radio" class="input-radio blue" v-model="form.role"
                                   value="user">
                            <span class="ml-2">Пользователь с настраиваемыми правами доступа</span>
                        </label>
                    </div>
                </div>
            </div>


            <div v-if="form.role === 'user'">
                <div>
                    <div class="flex items-center">
                        <button type="button" @click="currentBookmark = 'elog'"
                                class="p-2 border uppercase text-sm font-bold opacity-50 hover:opacity-100 transition duration-200"
                                :class="{'bg-gray-200 border-2 border-x-gray-300 border-t-gray-400 text-gray-600 opacity-100' : currentBookmark === 'elog'}">
                            Журнал смен
                        </button>
                        <button type="button" @click="currentBookmark = 'jsonOrders'"
                                class="p-2 border uppercase text-sm font-bold opacity-50 hover:opacity-100 transition duration-200"
                                :class="{'bg-gray-200 border-2 border-x-gray-300 border-t-gray-400 text-gray-600 opacity-100' : currentBookmark === 'jsonOrders'}">
                            Журнал распоряжений
                        </button>
                        <button type="button" @click="currentBookmark = 'infos'"
                                class="p-2 border uppercase text-sm font-bold opacity-50 hover:opacity-100 transition duration-200"
                                :class="{'bg-gray-200 border-2 border-x-gray-300 border-t-gray-400 text-gray-600 opacity-100' : currentBookmark === 'infos'}">
                            Объявления
                        </button>
                        <button type="button" @click="currentBookmark = 'other'"
                                class="p-2 border uppercase text-sm font-bold opacity-50 hover:opacity-100 transition duration-200"
                                :class="{'bg-gray-200 border-2 border-x-gray-300 border-t-gray-400 text-gray-600 opacity-100' : currentBookmark === 'other'}">
                            Прочее
                        </button>
                    </div>
                </div>

                <table class="min-w-max w-full text-sm table-auto border-spacing-2">
                    <thead>
                    <tr v-if="currentBookmark === 'elog'"
                        class="bg-gray-200 text-gray-600 uppercase text-sm border-spacing-y-0.5">
                        <th class="py-1 px-2 text-center"></th>
                        <th class="py-1 px-2 text-center">Печать</th>
                        <th class="py-1 px-2 text-center">Ламинация</th>
                        <th class="py-1 px-2 text-center">Сварка</th>
                        <th class="py-1 px-2 text-center">Резка</th>
                        <th class="py-1 px-2 text-center">Экструзия</th>
                        <th class="py-1 px-2 text-center">Переработка</th>
                        <th class="py-1 px-2 text-center">УПФФ</th>
                    </tr>
                    <tr v-if="currentBookmark === 'jsonOrders'"
                        class="bg-gray-200 text-gray-600 uppercase text-sm border-spacing-y-0.5">
                        <th class="py-1 px-2 text-center"></th>
                        <th class="py-1 px-2 text-center">Печать</th>
                        <th class="py-1 px-2 text-center">Ламинация</th>
                        <th class="py-1 px-2 text-center">Сварка</th>
                        <th class="py-1 px-2 text-center">Резка</th>
                        <th class="py-1 px-2 text-center">Экструзия</th>
                        <th class="py-1 px-2 text-center">Переработка</th>
                        <th class="py-1 px-2 text-center">Шпулерезка</th>
                    </tr>
                    <tr v-if="currentBookmark === 'infos'"
                        class="bg-gray-200 text-gray-600 uppercase text-sm border-spacing-y-0.5">
                        <th v-for="sector in props.sectors" class=" py-1 px-2 text-center ">{{ sector.title }}</th>
                    </tr>
                    </thead>

                    <tbody v-if="currentBookmark === 'elog'" class="text-gray-600 text-sm font-light">
                    <tr>
                        <td class="w-60 text-center">
                            <p class="font-bold">Пользователь</p>
                            <span class="text-xs">(только просмотр)</span>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.printing_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.lamination_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.welding_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.cutting_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.extrusion_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.recycling_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.upff_permission"
                                       value="basic">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-60 text-center">
                            <p class="font-bold">Мастер</p>
                            <span class="text-xs">(добавление, правка и определенное удаление)</span>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.printing_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.lamination_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.welding_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.cutting_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.extrusion_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.recycling_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.upff_permission"
                                       value="medium">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-60 text-center">
                            <p class="font-bold">Администратор</p>
                            <span class="text-xs">(полные права)</span>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.printing_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.lamination_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.welding_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.cutting_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.extrusion_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.recycling_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.upff_permission"
                                       value="full">
                            </div>
                        </td>
                    </tr>
                    </tbody>

                    <!-- ЖУРНАЛ РАСПОРЯЖЕНИЙ                   -->
                    <tbody v-if="currentBookmark === 'jsonOrders'" class="text-gray-600 text-sm font-light">
                    <tr>
                        <td class="w-60 text-center">
                            <p class="font-bold">Пользователь</p>
                            <span class="text-xs">(только просмотр)</span>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.printing_orders_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.lamination_orders_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.welding_orders_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.cutting_orders_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.extrusion_orders_permission"
                                       value="basic">

                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.recycling_orders_permission"
                                       value="basic">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio blue"
                                       v-model="form.permissions.spoolcutting_orders_permission"
                                       value="basic">
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="9">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-60 text-center">
                            <p class="font-bold">Мастер</p>
                            <span class="text-xs">(внесение некоторых изменений и смена статуса)</span>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green text-xs"
                                       v-model="form.permissions.printing_orders_permission" value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.lamination_orders_permission" value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.welding_orders_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.cutting_orders_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.extrusion_orders_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.recycling_orders_permission"
                                       value="medium">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio green"
                                       v-model="form.permissions.spoolcutting_orders_permission"
                                       value="medium">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-60 text-center">
                            <p class="font-bold">Администратор</p>
                            <span class="text-xs">(создание, редактирование, изменение статуса, изменение очередности, удаление)</span>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.printing_orders_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.lamination_orders_permission" value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.welding_orders_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.cutting_orders_permission"
                                       value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.extrusion_orders_permission" value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.recycling_orders_permission" value="full">
                            </div>
                        </td>
                        <td>
                            <div class="flex place-content-center justify-items-center justify-center">
                                <input type="radio" class="input-radio orange"
                                       v-model="form.permissions.spoolcutting_orders_permission"
                                       value="full">
                            </div>
                        </td>
                    </tr>
                    </tbody>

                    <tbody v-if="currentBookmark === 'infos'" class="text-gray-600 text-sm font-light">
                    <tr>
                        <td v-for="sector in props.sectors" class="text-center px-2 py-2">
                            <Checkbox class="w-5 h-5"
                                      v-model:checked="form.infos_permissions[sector.name]"
                                      :value="sector.id"
                                      :disabled="sector.name === 'General'"
                            />
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div v-if="currentBookmark === 'other'" class="flex flex-col">
                    <p class="italic text-gray-700 px-8 py-2">Дополнительные разрешения:</p>
                    <div class="flex flex-col gap-1">
                        <label v-if="$page.props.auth.user.login === 'admin'" class="flex items-center">
                            <Checkbox v-model:checked="form.permissions.set_permissions"/>
                            <span class="ml-2 text-sm text-gray-600">Выдача разрешений</span>
                        </label>
                        <label v-if="$page.props.auth.user.login === 'admin'">
                            <Checkbox v-model:checked="form.permissions.open_users_profiles"/>
                            <span class="ml-2 text-sm text-gray-600">Управление пользователями</span>
                        </label>
                        <label v-if="$page.props.auth.user.login === 'admin'">
                            <Checkbox v-model:checked="form.permissions.open_workers_profiles"/>
                            <span class="ml-2 text-sm text-gray-600">Управление работниками</span>
                        </label>
                        <label>
                            <Checkbox v-model:checked="form.permissions.open_apps"/>
                            <span class="ml-2 text-sm text-gray-600">Доступ к приложениям</span>
                        </label>
                        <label>
                            <Checkbox v-model:checked="form.permissions.open_tests"/>
                            <span class="ml-2 text-sm text-gray-600">Доступ к тестам</span>
                        </label>
                    </div>
                </div>
                <div class="flex flex-col mt-5 items-center">
                    <p v-for="error in form.errors " class="mt-4 text-xs text-red-800">{{ error }}</p>
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
            </div>
        </div>
</template>

<style scoped>
.input-radio.blue:checked {
    box-shadow: 0 0 0 2px rgb(148, 180, 245);
    background-color: rgb(148, 180, 245);
}

.input-radio.yellow:checked {
    box-shadow: 0 0 0 2px rgb(245, 189, 6);
    background-color: rgb(245, 189, 6);
}

.input-radio.orange:checked {
    box-shadow: 0 0 0 2px rgb(245, 141, 6);
    background-color: rgb(245, 141, 6);
}

.input-radio.gray:checked {
    box-shadow: 0 0 0 2px rgba(156, 163, 175, 0.62);
    background-color: rgba(156, 163, 175, 0.62);
}

.input-radio.green:checked {
    box-shadow: 0 0 0 2px rgb(79, 204, 13);
    background-color: rgb(79, 204, 13);
}
</style>
