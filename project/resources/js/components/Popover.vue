<script setup>
import {ref} from "vue";

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    message: {
        type: String,
        required: true
    }
})

const isPopoverVisible = ref(false);
</script>

<template>
    <div class="relative group inline-block">
        <!-- Icon that triggers the popover -->
        <span @mouseover="isPopoverVisible = true" @mouseout="isPopoverVisible = false" class="cursor-pointer">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path fill="#939393" fill-rule="evenodd" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10ZM12 7.75c-.621 0-1.125.504-1.125 1.125a.75.75 0 0 1-1.5 0 2.625 2.625 0 1 1 4.508 1.829c-.092.095-.18.183-.264.267-.216.215-.405.404-.571.617-.22.282-.298.489-.298.662V13a.75.75 0 0 1-1.5 0v-.75c0-.655.305-1.186.614-1.583.229-.294.516-.58.75-.814.07-.07.136-.135.193-.194A1.125 1.125 0 0 0 12 7.75ZM12 17a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
            </svg>
        </span>

        <!-- Popover content -->
        <div v-show="isPopoverVisible" class="absolute left-10 -top-24 mt-2 w-64 text-black text-sm border border-gray-300 rounded-lg bg-gray-100 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <p class="px-2 py-1 text-center text-sm">
                {{ props. title}}
            </p>
            <hr>
            <p class="px-2 py-1 text-center text-xs italic">
                {{ props. message}}
            </p>
        </div>
    </div>
</template>

<style scoped>

</style>
