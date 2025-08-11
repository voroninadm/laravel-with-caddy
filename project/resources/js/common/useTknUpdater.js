import { ref, computed } from 'vue';

export function useTknUpdater(form, tknFieldName = 'tkn') {
    const timeoutId = ref(null);

    const tknValue = computed({
        get: () => form[tknFieldName],
        set: (newValue) => {
            form[tknFieldName] = newValue;
        }
    });

    const updateAndSatisfyTkn = () => {
        clearTimeout(timeoutId.value);
        timeoutId.value = setTimeout(() => {
            if (tknValue.value.length >= 5 && !tknValue.value.endsWith('a')) {
                tknValue.value += 'a';
            }
        }, 500);
    };

    return { updateAndSatisfyTkn, tknValue };
}
