<template>
    <label class="flex items-center space-x-2">
        <input
            type="radio"
            :name="name"
            :value="value"
            v-model="selectedValue"
            class="form-radio h-5 w-5 text-orange-600"
        />
        <span class="text-gray-700 text-sm">{{ label }}</span>
    </label>
</template>

<script>
export default {
    props: {
        name: String,     // имя группы радиокнопок
        value: String,    // значение радиокнопки
        label: String,    // подпись
        selected: String, // значение выбранной радиокнопки
    },
    computed: {
        selectedValue: {
            get() {
                return this.selected;
            },
            set(value) {
                this.$emit('update:selected', value);
            },
        },
    },
};
</script>
