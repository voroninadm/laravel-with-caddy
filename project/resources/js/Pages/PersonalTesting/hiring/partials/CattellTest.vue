<script setup>
import {ref} from "vue";


const props = defineProps({
    questions: Array
});

const emit = defineEmits('finishTest');

const currentQuestion = ref(0);

const raw_results = ({
    "MD": 0,
    "A": 0,
    "B": 0,
    "C": 0,
    "E": 0,
    "F": 0,
    "G": 0,
    "H": 0,
    "I": 0,
    "L": 0,
    "M": 0,
    "N": 0,
    "O": 0,
    "Q1": 0,
    "Q2": 0,
    "Q3": 0,
    "Q4": 0,
});

const getNextQuestion = (factor, raw_point) => {
    calcAnswer(factor, raw_point);
    currentQuestion.value++;
    if (currentQuestion.value === (props.questions.length)) {
        emit('finishTest', raw_results);
    }
}

const calcAnswer = (factor, raw_point) => {
    raw_results[factor] += raw_point;
}
</script>

<template>
    <section class="w-screen h-screen flex flex-col items-center bg-stone-200/50">

        <div class="p-4 w-8/12 text-center mx-auto my-auto shadow-xl drop-shadow-sm bg-white border rounded-xl shadow-gray-300">
                <span class="text-sm text-gray-400">Вопрос {{ questions[currentQuestion].id }} из {{
                        questions.length
                    }}</span>
            <p class="py-8 text-xl text-stone-800 no-select font-semibold"> {{ questions[currentQuestion].ask }}</p>
            <ol class="flex flex-col gap-4">
                <li v-for="answer in questions[currentQuestion].answers"
                    :key="questions[currentQuestion].answers.id"
                    class="flex justify-center">
                    <button @click="getNextQuestion(questions[currentQuestion].factor, answer.raw_point)"
                            type="button"
                            class="border py-1 px-2 w-8/12 rounded-xl text-gray-500 hover:drop-shadow-sm hover:shadow-sm hover:bg-gray-50 hover:text-gray-600 transition duration-200">
                        {{ answer.say }}
                    </button>
                </li>
            </ol>
        </div>
    </section>
</template>

<style scoped>
.no-select {
    -webkit-user-select: none; /* Chrome/Safari */
    -moz-user-select: none; /* Firefox */
    -ms-user-select: none; /* IE/Edge */
    user-select: none; /* Стандартный синтаксис */
}
</style>
