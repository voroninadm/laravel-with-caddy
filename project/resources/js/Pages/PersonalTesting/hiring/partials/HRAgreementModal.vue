<script setup>

import Modal from "@/components/Modal.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import Checkbox from "@/components/Checkbox.vue";
import {computed, ref} from "vue";

const isConfidencialAccepted = ref(false);
const isTestingAccepted = ref(false);
const isFeelengAccepted = ref(false);


const allIsOk = computed(() => {
    return isConfidencialAccepted.value === true
        && isTestingAccepted.value === true
        && isFeelengAccepted.value === true
})

defineEmits('acceptAgreement')
</script>

<template>
    <Modal :maxWidth="'ultra'">
        <div class="p-3 flex flex-col items-center text-sm border-4 border-red-800 rounded-lg">
            <form action="#" @submit.prevent>
                <div class="flex flex-col gap-5 py-2 text-lg  font-medium text-gray-900">

                    <label class="flex gap-3">
                        <Checkbox v-model:checked="isConfidencialAccepted" class="w-6 h-6"/>
                        <span>Подтверждаю, что приступая к выполнению анкетирования, я ознакомился с
                            <a :href="route('testing.confidential-agreement')" target="_blank"
                               class="text-red-700 underline">
                                Положением о конфиденциальности
                            </a>
                            и принимаю все его условия.
                        </span>
                    </label>

                    <label class="flex gap-3">
                        <Checkbox v-model:checked="isTestingAccepted" class="w-6 h-6"/>
                        <span> Даю добровольное согласие на прохождение психологического тестирования, направленного на оценку моих профессионально-личностных качеств с использованием
                            многофакторного личностного опросника;
                        </span>
                    </label>

                    <label class="flex gap-3">
                        <Checkbox v-model:checked="isFeelengAccepted" class="w-6 h-6"/>
                        <span> Я подтверждаю, что сейчас, непосредственно перед прохождением анкетирования и тестирования, чувствую себя здоровым и работоспособным.
                        </span>
                    </label>

                    <div class="m-auto p-2">
                        <PrimaryButton :disabled="!allIsOk"
                                       @click="allIsOk ? $emit('acceptAgreement'): ''"
                                       :class="{'opacity-50' : !allIsOk}">
                            Подтверждаю
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>

<style scoped>

</style>
