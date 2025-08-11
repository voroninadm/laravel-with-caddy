<script setup>
import MachinesReportsFilter from '@/Pages/Reports/partials/MachinesReportsFilter.vue';
import PrintTable from "@/Pages/Reports/partials/PrintingTable.vue";
import LaminationTable from "@/Pages/Reports/partials/LaminationTable.vue";
import WeldingTable from "@/Pages/Reports/partials/WeldingTable.vue";
import CuttingTable from "@/Pages/Reports/partials/CuttingTable.vue";
import {onBeforeUnmount, onMounted, ref} from "vue";
import ExtrusionTable from "@/Pages/Reports/partials/ExtrusionTable.vue";
import RecyclingTable from "@/Pages/Reports/partials/RecyclingTable.vue";

const props = defineProps({
    idlesData: {
        required: false
    }
})

// Состояние кнопки
const showButton = ref(false);

// Обработчик события прокрутки
const handleScroll = () => {
    // Отображаем кнопку, если прокрутка больше 100px
    showButton.value = window.scrollY > 100;
};

// Функция прокрутки вверх
const scrollToTop = () => {
    window.scrollTo({top: 0, behavior: 'smooth'});
};

// Добавляем обработчик события при монтировании компонента
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

// Удаляем обработчик события при размонтировании компонента
onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <machines-reports-filter :title="'Простой машин'"
                             :route="'/idles/machines'"
                             :is-excel-button-disabled="idlesData.length === 0"
                             :excel-report-name="'Отчет по простоям.xlsx'"
    />

    <div class="overflow-y-auto">
        <PrintTable v-if="props.idlesData.printing"
                    :tasks="props.idlesData.printing"/>

        <LaminationTable v-if="props.idlesData.lamination"
                         :tasks="props.idlesData.lamination"/>

        <WeldingTable v-if="props.idlesData.welding"
                      :tasks="props.idlesData.welding"/>

        <CuttingTable v-if="props.idlesData.cutting"
                      :tasks="props.idlesData.cutting"/>

        <ExtrusionTable v-if="props.idlesData.extrusion"
                        :tasks="props.idlesData.extrusion"/>

        <RecyclingTable v-if="props.idlesData.recycling"
                        :tasks="props.idlesData.recycling"/>

        <button type="button" v-if="showButton" @click="scrollToTop"
                class="upButton">
            <svg class="w-12 h-12" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 512 512">
  <circle cx="256" cy="256" r="256" fill="#31bafd"/>
                <path fill="#2b9ed8"
                      d="M511.6 270.2 377.2 136l-146 141.4-99 96.2 138.1 138.1a256 256 0 0 0 241.3-241.4z"/>
                <circle cx="256" cy="256" r="170.7" fill="#fff"/>
                <path fill="#e6f3ff" d="M256 85.3a170.7 170.7 0 0 1 0 341.4V85.3z"/>
                <path fill="#324a5e" d="m331.6 310.1-75.7-75.6-75.5 75.6-28.1-28 103.6-103.7 103.7 103.7z"/>
                <path fill="#2b3b4e" d="m255.9 178.4 103.7 103.7-28 28-75.7-75.6z"/>
</svg>

        </button>
    </div>
</template>

<style scoped>
.upButton {
    position: fixed;
    right: 50px;
    bottom: 80px;
    opacity: 0.7;
}
</style>
