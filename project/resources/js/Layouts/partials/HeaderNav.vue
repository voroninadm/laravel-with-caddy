<script setup>
import {onBeforeUnmount, onMounted, ref} from 'vue';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import ResponsiveNavLink from '@/components/ResponsiveNavLink.vue';
import {Link} from '@inertiajs/vue3';
import NTLlogo from '@/components/icons/NTLlogo.vue';
import {showCurrentYearMonth} from "@/common/dateTimeHelper.js";

let showingNavigationDropdown = ref(false);
let showingSideMenu = ref(false);


function closeNavigationDropdown() {
    setTimeout(() => {
        showingNavigationDropdown.value = false
    }, 300)
}

const closeOnEscape = (e) => {
    if (showingSideMenu.value && e.key === 'Escape') {
        showingSideMenu.value = false;
    }
};

onMounted(() => {
    window.addEventListener('scroll', closeNavigationDropdown);
    document.addEventListener('keydown', closeOnEscape)
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', closeNavigationDropdown);
    document.removeEventListener('keydown', closeOnEscape)
});

</script>

<template>
    <nav :class="showingNavigationDropdown ? 'z-50' : '' "
         class="bg-white border-b border-gray-100 w-full nav shadow-lg">
        <!-- Primary Navigation Menu -->
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-12">
                <div class="flex">

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <button :class="{'hover:scale-110' :!showingSideMenu,
                         'animate__rubberBand animate__infinite animate__slow' : $page.props.ziggy.location === route('dashboard') && !showingSideMenu}"
                                class="animate__animated ml-52 transition duration-300"
                                @click="showingSideMenu = !showingSideMenu">
                            <svg
                                :class="{'text-red-700 scale-110 ' :showingSideMenu, 'hover:text-red-800/70': !showingSideMenu}"
                                class="h-7 fill-current text-gray-800 transition duration-300" viewBox="0 -0.5 21 21"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.6 20H21v-9h-9.4v9ZM0 20h9.4v-9H0v9ZM11.6 9H21V0h-9.4v9ZM0 9h9.4V0H0v9Z"/>
                            </svg>

                        </button>
                    </div>

                    <transition
                        appear
                        enter-active-class="animate__animated animate__fadeInLeft"
                        name="fade"
                    >
                        <aside
                            v-show="showingSideMenu"
                            class="py-2 left-0 top-14 bg-white  w-64 rounded-r-xl shadow-xl shadow-orange-800 transition duration-300 z-50 fixed">

                            <div class="mx-auto py-5">
                                <div class="flex items-center justify-center">
                                    <!--Logo-->
                                    <Link class="logo"
                                          :href="route('dashboard')"
                                          @click="showingSideMenu = false">
                                        <NTLlogo
                                            :height="50"
                                            :width="100"
                                            tabindex="0"
                                        />
                                    </Link>
                                </div>
                                <div class="container">
                                    <nav>
                                        <ul class="mcd-menu">
                                            <li>
                                                <p class="flex">Журнал сдачи смен
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24">
                                                        <path fill="lightgray"
                                                              d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                    </svg>
                                                </p>
                                                <ul>
                                                    <li>
                                                        <p>Монтаж флексоформ
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24">
                                                                <path fill="lightgray"
                                                                      d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                            </svg>
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="($page.props.auth.permissions.includes('medium_upff_permission')) || $page.props.auth.permissions.includes('full_upff_permission')"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('flexoform.create')"
                                                                    @click="showingSideMenu = false"> Новое задание
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('flexoform.allWorks')"
                                                                    @click="showingSideMenu = false"> Все
                                                                    работы
                                                                </DropdownLink>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <p>Экструзия
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24">
                                                                <path fill="lightgray"
                                                                      d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                            </svg>
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="($page.props.auth.permissions.includes('medium_extrusion_permission')) || $page.props.auth.permissions.includes('full_extrusion_permission')"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('extrusion.create')"
                                                                    @click="showingSideMenu = false"> Новое задание
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('extrusion.allWorks')"
                                                                    @click="showingSideMenu = false"> Все
                                                                    работы
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('extrusion.workout')"
                                                                    @click="showingSideMenu = false"> Выработка
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('extrusion.worksReport')"
                                                                    @click="showingSideMenu = false"> Показатели работ
                                                                </DropdownLink>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <p>Печать
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24">
                                                                <path fill="lightgray"
                                                                      d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                            </svg>
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="($page.props.auth.permissions.includes('medium_printing_permission')) || $page.props.auth.permissions.includes('full_printing_permission')"
                                                                    :href="route('printing.create')"
                                                                    :class="'hover:bg-inherit'"
                                                                    @click="showingSideMenu = false"> Новое задание
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink :href="route('printing.allWorks')"
                                                                              @click="showingSideMenu = false"
                                                                              :class="'hover:bg-inherit'"> Все
                                                                    работы
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :href="route('printing.workout')"
                                                                    :class="'hover:bg-inherit'"
                                                                    @click="showingSideMenu = false"> Выработка
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :href="route('printing.worksReport')"
                                                                    :class="'hover:bg-inherit'"
                                                                    @click="showingSideMenu = false"> Показатели работ
                                                                </DropdownLink>
                                                            </li>
                                                        </ul>
                                                    </li>

                                                    <li>
                                                        <p>Ламинация
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24">
                                                                <path fill="lightgray"
                                                                      d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                            </svg>
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="($page.props.auth.permissions.includes('medium_lamination_permission')) || $page.props.auth.permissions.includes('full_lamination_permission')"
                                                                    :href="route('lamination.create')"
                                                                    :class="'hover:bg-inherit'"
                                                                    @click="showingSideMenu = false"> Новое задание
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink :href="route('lamination.allWorks')"
                                                                              @click="showingSideMenu = false"
                                                                              :class="'hover:bg-inherit'"> Все
                                                                    работы
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :href="route('lamination.workout')"
                                                                    :class="'hover:bg-inherit'"
                                                                    @click="showingSideMenu = false"> Выработка
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :href="route('lamination.worksReport')"
                                                                    :class="'hover:bg-inherit'"
                                                                    @click="showingSideMenu = false"> Показатели работ
                                                                </DropdownLink>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <p>Сварка
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24">
                                                                <path fill="lightgray"
                                                                      d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                            </svg>
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="($page.props.auth.permissions.includes('medium_welding_permission')) || $page.props.auth.permissions.includes('full_welding_permission')"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('welding.create')"
                                                                    @click="showingSideMenu = false"> Новое задание
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('welding.allWorks')"
                                                                    @click="showingSideMenu = false"> Все
                                                                    работы
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('welding.workout')"
                                                                    @click="showingSideMenu = false"> Выработка
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('welding.worksReport')"
                                                                    @click="showingSideMenu = false"> Показатели работ
                                                                </DropdownLink>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <p>Резка
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24">
                                                                <path fill="lightgray"
                                                                      d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                            </svg>
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="($page.props.auth.permissions.includes('medium_cutting_permission')) || $page.props.auth.permissions.includes('full_cutting_permission')"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('cutting.create')"
                                                                    @click="showingSideMenu = false"> Новое задание
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('cutting.allWorks')"
                                                                    @click="showingSideMenu = false"> Все
                                                                    работы
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('cutting.workout')"
                                                                    @click="showingSideMenu = false"> Выработка
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('cutting.worksReport')"
                                                                    @click="showingSideMenu = false"> Показатели работ
                                                                </DropdownLink>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <p>Переработка
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24">
                                                                <path fill="lightgray"
                                                                      d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                            </svg>
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="($page.props.auth.permissions.includes('medium_recycling_permission')) || $page.props.auth.permissions.includes('full_recycling_permission')"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('recycling.create')"
                                                                    @click="showingSideMenu = false"> Новое задание
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('recycling.allWorks')"
                                                                    @click="showingSideMenu = false"> Все
                                                                    работы
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"

                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('recycling.workout')"
                                                                    @click="showingSideMenu = false"> Выработка
                                                                </DropdownLink>
                                                            </li>
                                                            <li>
                                                                <DropdownLink
                                                                    v-if="!($page.props.auth.role.includes('manager'))"
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('recycling.worksReport')"
                                                                    @click="showingSideMenu = false"> Показатели работ
                                                                </DropdownLink>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li v-if="(!$page.props.auth.role.includes('manager'))">
                                                        <p>Прочие отчеты
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                                                 fill="none"
                                                                 viewBox="0 0 24 24">
                                                                <path fill="lightgray"
                                                                      d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                            </svg>
                                                        </p>
                                                        <ul>
                                                            <li>
                                                                <DropdownLink
                                                                    :class="'hover:bg-inherit'"
                                                                    :href="route('machinesIdle.get')"
                                                                    @click="showingSideMenu = false"> Простои
                                                                    машин
                                                                </DropdownLink>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li v-if="(!$page.props.auth.role.includes('manager'))">
                                                <a :href="route('orders')"
                                                   @click="showingSideMenu = false"> Журнал распоряжений
                                                </a>
                                            </li>

                                            <li v-if="(!$page.props.auth.role.includes('manager'))">
                                                <a :href="'/info/General'"
                                                   @click="showingSideMenu = false"> Объявления
                                                    <span v-if="$page.props.auth.user.unreadedMessagesCount > 0"
                                                          class="ml-2 py-1 px-2.5 text-xs font-bold text-white bg-red-600 rounded-full shadow-lg">
                                                        {{ $page.props.auth.user.unreadedMessagesCount }}
                                                    </span>
                                                </a>
                                            </li>

                                            <li v-if="$page.props.auth.permissions.includes('open_users_profiles') ||
                                                       $page.props.auth.permissions.includes('open_workers_profiles')">
                                                <p>Сотрудники
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24">
                                                        <path fill="lightgray"
                                                              d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                    </svg>
                                                </p>
                                                <ul>
                                                    <li>
                                                        <DropdownLink
                                                            v-if="$page.props.auth.permissions.includes('open_users_profiles')"
                                                            :href="route('users.show')"
                                                            @click="showingSideMenu = false"> Пользователи программы
                                                        </DropdownLink>
                                                    </li>
                                                    <li>
                                                        <DropdownLink
                                                            v-if="$page.props.auth.permissions.includes('open_workers_profiles')"
                                                            :href="route('workers.show')"
                                                            @click="showingSideMenu = false"> Список работников
                                                        </DropdownLink>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li v-if="$page.props.auth.permissions.includes('open_tests')">
                                                <p>Тестирование
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24">
                                                        <path fill="lightgray"
                                                              d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                    </svg>
                                                </p>
                                                <ul>
                                                    <li>
                                                        <DropdownLink
                                                            v-if="$page.props.auth.permissions.includes('open_tests')"
                                                            :href="route('hiring.show', showCurrentYearMonth())"
                                                            @click="showingSideMenu = false"> Трудоустройство
                                                        </DropdownLink>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li v-if="($page.props.auth.permissions.includes('open_apps'))">
                                                <p>Приложения
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24">
                                                        <path fill="lightgray"
                                                              d="M5.7 9.7a1 1 0 0 0 0 1.4l4.9 5c.8.7 2 .7 2.8 0l5-5a1 1 0 1 0-1.5-1.4L12.7 14a1 1 0 0 1-1.4 0L7.1 9.7a1 1 0 0 0-1.4 0Z"/>
                                                    </svg>
                                                </p>
                                                <ul>
                                                    <li>
                                                        <DropdownLink :href="route('promo')"
                                                                      @click="showingSideMenu = false"> Генератор
                                                            промокодов
                                                        </DropdownLink>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </aside>
                    </transition>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6 mr-20">

                    <!--User Settings Dropdown -->
                    <div class="ml-3 relative">

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-hidden transition ease-in-out duration-150"
                                                type="button">
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        clip-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        fill-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                            </template>

                            <template #content>
                                <DropdownLink :active="route().current('profile.edit')"
                                              :href="route('profile.edit')"> Мой профиль
                                </DropdownLink>

                                <DropdownLink :href="route('logout')" as="button" method="post">
                                    Выйти
                                </DropdownLink>
                                <a href="http://10.1.5.5:8080"
                                   class="mt-8 ml-4 text-sm text-gray-500 italic hover:text-gray-800">
                                    К старому журналу
                                </a>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                d="M4 6h16M4 12h16M4 18h16"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                            <path
                                :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                d="M6 18L18 6M6 6l12 12"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
            class="sm:hidden absolute top-12 w-full bg-white text-center"
        >
            <!-- Responsive Settings Options -->
            <div class="pt-3 pb-3 border border-gray-200">
                <div class="px-4 text-center">
                    <div class="font-medium text-base text-gray-800">
                        {{ $page.props.auth.user.name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :active="route().current('profile.edit')"
                                       :href="route('profile.edit')"
                                       @click="closeNavigationDropdown"> Мой профиль
                    </ResponsiveNavLink>

                    <ResponsiveNavLink :href="route('logout')" as="button" method="post">
                        Выйти
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>

<style lang="scss" scoped>

.adding_icon {
    width: 25px;
    height: 25px;
    margin: 0 20px;
}

/* since nested groups are not supported we have to use
     regular css for the nested dropdowns
  */
li > ul {
    transform: translatex(100%) scale(0)
}

li:hover > ul {
    transform: translatex(101%) scale(1)
}

li > button svg {
    transform: rotate(-90deg)
}

.group:hover .group-hover\:scale-100 {
    transform: scale(1)
}

.group:hover .group-hover\:-rotate-180 {
    transform: rotate(180deg)
}

.scale-0 {
    transform: scale(0)
}

.min-w-32 {
    min-width: 8rem
}

.fade-enter-active, .fade-leave-active {
    transition: opacity .7s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}


.container {
    position: relative;
    margin: 0 auto;
    padding: 50px 0;
    clear: both;
}


.mcd-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: #FFF;
    border-radius: 2px;

}

.mcd-menu li {
    position: relative;
}

.mcd-menu li a,
.mcd-menu li p {
    display: flex;
    gap: 5px;
    justify-items: center;
    align-items: center;
    text-decoration: none;
    padding: 15px 20px;
    color: #777;
    /* == */
    text-align: left;
    height: 36px;
    position: relative;
    border-bottom: 1px solid #EEE;
    /* == */
}

.mcd-menu li:hover > a,
.mcd-menu li:focus > a,
.mcd-menu li:hover > p,
.mcd-menu li:focus > p {
    cursor: pointer;
    color: #e67e22;
}

.mcd-menu li a.active:before {
    content: "";
    position: absolute;

    /* == */
    top: 42%;
    left: 0;
    border-left: 5px solid #e67e22;
    border-top: 5px solid transparent;
    border-bottom: 5px solid transparent;
    /* == */
}

/* == */
.mcd-menu li a.active:after {
    content: "";
    position: absolute;
    top: 42%;
    right: 0;
    border-right: 5px solid #e67e22;
    border-top: 5px solid transparent;
    border-bottom: 5px solid transparent;
}

.mcd-menu li ul,
.mcd-menu li ul li ul {
    position: absolute;
    font-size: 14px;
    height: auto;
    min-width: 200px;
    padding: 0;
    margin: 0;
    background: rgb(246, 246, 246);
    opacity: 0;
    visibility: hidden;
    transition: all 300ms linear;
    -o-transition: all 300ms linear;
    -ms-transition: all 300ms linear;
    -moz-transition: all 300ms linear;
    -webkit-transition: all 300ms linear;
    z-index: 900;
    box-shadow: 2px 2px 5px rgba(10, 14, 23, 0.55);
    border-radius: 10px;
    /* == */
    left: 290px;
    top: 0;
    border-left: 4px solid #e67e22;
    /* == */
}

.mcd-menu li ul li ul {
    background: rgb(230, 230, 230);

}

.mcd-menu li ul:before {
    content: "";
    position: absolute;
    /* == */
    top: 15px;
    left: -9px;
    border-right: 5px solid #e67e22;
    border-bottom: 5px solid transparent;
    border-top: 5px solid transparent;
    /* == */
}

.mcd-menu li:hover > ul,
.mcd-menu li ul li:hover > ul {
    display: block;
    opacity: 1;
    visibility: visible;
    top: 0;
    /* == */
    left: 50px;
}

.mcd-menu li ul li a,
.mcd-menu li ul li p {
    display: flex;
    padding: 10px;
    text-align: left;
    border: 0;
    /* == */
    height: auto;
    /* == */
}

.mcd-menu li ul li ul {
    left: 230px;
    top: 0;
    border: 0;
    border-left: 4px solid rgb(173, 93, 4);
}

.mcd-menu li ul li ul:before {
    content: "";
    position: absolute;
    top: 15px;
    /* == */
    left: -9px;
    /* == */
    border-right: 5px solid rgb(173, 93, 4);
    border-bottom: 5px solid transparent;
    border-top: 5px solid transparent;
}

.mcd-menu li ul li:hover > ul {
    left: -10px;
}

.mcd-menu > li > p:hover > svg,
.mcd-menu > li:hover > p > svg,
.mcd-menu > li > ul > li > p:hover > svg,
.mcd-menu > li > ul > li:hover > p > svg {
    transform: rotate(-90deg);
    transition: transform 0.3s ease;
}
</style>
