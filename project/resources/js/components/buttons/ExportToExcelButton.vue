<script setup>
import {computed} from "vue";

const props = defineProps({
    width: {
        type: String,
        default: '6'
    },
    padding: {
        type: String,
        default: '2'
    },
    isDisabled: Boolean
})

const widthHeightClass = computed(() => {
    return {
        '3': 'w-3',
        '4': 'w-4',
        '5': 'w-5',
        '6': 'w-6',
        '7': 'w-7',
    }[props.width.toString()];
});

const paddingClass = computed(() => {
    return {
        '1': 'p-1',
        '2': 'p-2',
    }[props.padding.toString()];
});
</script>

<template>
    <button type="button" class="border rounded-lg transition duration-200"
    :class="{'bg-slate-100' : props.isDisabled, 'hover:scale-105' : !props.isDisabled}, paddingClass" :disabled="props.isDisabled">
        <svg :class="widthHeightClass" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 26 26">
  <path
      d="M25.2 3H16v3h3v2h-3v2h3v2h-3v2h3v2h-3v2h3v2h-3v3h9.2c.4 0 .8-.4.8-.9V4c0-.5-.4-.9-.8-.9zM24 20h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V6h4v2zM0 2.9V23l15 3V0L0 2.9zM9.5 18l-1.8-3.3a3 3 0 0 1-.2-.7l-.2.7L5.5 18H2.8L6 13 3 8h3l1.4 3 .3.8.4-.8 1.6-3H12l-3 5 3 5H9.6z"/>
</svg>

    </button>
</template>
