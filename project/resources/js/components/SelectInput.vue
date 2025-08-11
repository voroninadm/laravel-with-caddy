<script setup>
import {computed, onMounted, ref} from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        required: true,
    },
    options: {
        required: true,
    },
    placeholderOption: {
        required: true,
        default: 'выберите значение',
    }
});

defineEmits(['update:modelValue']);

const selectElement = ref(null);

onMounted(() => {
    if (selectElement.value.hasAttribute('autofocus')) {
        selectElement.value.focus();
    }
});

defineExpose({ focus: () => selectElement.value.focus() });
</script>

<template>
    <select
        class="border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs"
        :class="props.modelValue ? '' : 'text-gray-600'"
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        ref="selectElement"
    >
        <option value="" disabled selected >{{ props.placeholderOption }}</option>
        <option v-for="(key, value) in options" :key="key" :value="key" class="text-sm">
            {{ key }}
        </option>
    </select>
</template>
