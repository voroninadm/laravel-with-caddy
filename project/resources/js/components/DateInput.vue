<script setup>
import { onMounted, ref } from 'vue';


defineProps({
    modelValue: {
        type: Number || null,
        required: false,
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
        type="date"
        class=" h-7 py-0.5 text-sm border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>

<style scoped>

</style>
