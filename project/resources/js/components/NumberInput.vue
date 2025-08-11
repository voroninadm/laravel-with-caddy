<script setup>
import { onMounted, ref } from 'vue';


defineProps({
    modelValue: {
        type:  [Number, null],
        required: false,
    },
    required: {
        type: Boolean,
        default: false
    }
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
        type="number"
        class="border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs"
        :required="required"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.valueAsNumber)"
        ref="input"
        autocomplete="off"
    />
</template>

