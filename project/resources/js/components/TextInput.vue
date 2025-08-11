<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: String || null,
        required: true,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        type="text"
        class="border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
