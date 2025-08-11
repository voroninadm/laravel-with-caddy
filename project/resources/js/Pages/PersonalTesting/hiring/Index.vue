<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: GuestLayout
}
</script>

<script setup>
import {ref} from "vue";
import HRAgreementModal from "@/Pages/PersonalTesting/hiring/partials/HRAgreementModal.vue";
import Questionnaire from "@/Pages/PersonalTesting/hiring/partials/Questionnaire.vue";
import CattellTest from "@/Pages/PersonalTesting/hiring/partials/CattellTest.vue";
import FinalHiringPage from "@/Pages/PersonalTesting/hiring/partials/FinalHiringPage.vue";
import axios from "axios";
import {showNotification} from "@/common/notifications.js";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    questions: Array
})

const showModal = ref(true)
const currentPage = ref(Questionnaire);

const proceedToTest = (about_form) => {
    currentPage.value = CattellTest;
    HRdata.about = about_form;
}

const proceedToFinal = (raw_results) => {
    currentPage.value = FinalHiringPage;
    HRdata.raw_test_results = raw_results;

    axios.post(route('hiring.store'), HRdata)
        .then(() => {
            currentPage.value = FinalHiringPage;
            setTimeout(() => {
                router.post(route('testing.logout'));
            }, 10000);
        })
        .catch((error) => {
            showNotification('error', `Произошла ошибка при отправке данных: ${error.message}`);
        });
}

let HRdata = {
    about: null,
    raw_test_results: null
};
</script>

<template>
    <component :is="currentPage"
    :questions="questions"
    @finishAbout="proceedToTest"
    @finishTest="proceedToFinal" />

    <HRAgreementModal :show="showModal" @acceptAgreement="showModal = false"></HRAgreementModal>
</template>

<style scoped>

</style>
