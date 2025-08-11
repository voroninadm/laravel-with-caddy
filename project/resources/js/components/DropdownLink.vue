<script setup>
import { Link } from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        required: false,
    },
    method: {
        type: String,
        required: false,
    }
});

const classes = computed(() =>
    props.active
        ? 'block text-sm w-full pl-3 pr-4 py-2 border-l-4 border-orange-400 text-left text-base font-medium text-orange-700 focus:outline-hidden focus:text-orange-800 focus:bg-orange-100 focus:border-orange-700 transition duration-150 ease-in-out'
        : 'block text-sm w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-hidden focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link
        :href="href"
        :method="method"
        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 transition duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
