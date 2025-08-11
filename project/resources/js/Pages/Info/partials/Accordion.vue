<script setup>
import {ref} from "vue";
import {normalizeDatetime} from '@/common/dateTimeHelper.js'
import NewIcon from "@/components/icons/EnvelopeIcon.vue";
import OkIcon from "@/components/icons/OkIcon.vue";

const props = defineProps({
    auth: Object,
    message: {
        required: true
    }
});

let isOpen = ref(false);
</script>

<template>
    <div class="space-y-2">
        <button
            @click="isOpen = !isOpen"
            class="relative flex w-full items-center justify-between rounded-xl px-4 py-1.5 transition-all z-20"
            :class="{
                'bg-white border border-gray-200 shadow hover:shadow-md': !isOpen,
                'bg-gray-100 border border-gray-200 shadow-inner': isOpen,
                'border border-yellow-300': !props.message.is_read,
            }"
        >
            <div class="flex w-full items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <!-- New message indicator -->
                    <span v-if="!props.message.is_read" class="absolute text-red-500 -left-3 -top-1 -rotate-45">
                        <NewIcon class="w-6 h-6" />
                    </span>

                    <!-- Author name -->
                    <span class="text-sm italic text-gray-500"
                          :class="{'underline underline-offset-3': props.message.author_name == $page.props.auth.user.name}">
                        {{ props.message.author_name }}
                    </span>

                    <!-- Message title -->
                    <span class="text-md font-bold text-gray-700">
                        {{ props.message.title }}
                    </span>

                    <!-- Completed indicator -->
                    <span
                        v-if="props.message.is_done"
                        class="text-green-500"
                    >
                        <OkIcon class="w-5 h-5" />
                    </span>
                </div>

                <!-- Date -->
                <span class="text-xs text-gray-500 whitespace-nowrap italic pr-5">
                    {{ normalizeDatetime(message.created_at) }}
                </span>
            </div>

            <!-- Chevron icon -->
            <svg
                :class="{ 'rotate-180': isOpen }"
                class="ml-2 h-5 w-5 text-gray-400 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                ></path>
            </svg>
        </button>

        <!-- Expanded content -->
        <div
            v-if="isOpen"
            class="relative w-11/12 rounded-lg border border-gray-200 bg-white p-4 -top-4 m-auto shadow-sm"
        >
            <slot></slot>
        </div>
    </div>
</template>
