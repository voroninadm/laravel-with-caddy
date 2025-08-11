<script setup>

</script>

<template>
    <section class="bg-linear-to-r from-red-50 via-purple-100 to-gray-100 w-screen h-screen">
        <div
            class="w-6/12 p-8 flex flex-col justify-center items-center gap-5 shadow-lg shadow-violet-300 rounded-xl mx-auto my-20 bg-white">
            <div class="checkmark-circle">
                <div class="background"></div>
                <div class="checkmark draw"></div>
            </div>
            <h1 class="text-2xl text-gray-700">Вы завершили анкетированиие и тестирование!</h1>
            <p class="text-center text-gray-500">Результаты вашего тестирования сейчас обрабатываются и будут
                рассмотрены в ближайшее время!</p>
            <p class="text-gray-500 mt-10">
                <span class="text-red-700 font-bold">NTL</span> Упаковка благодарит Вас за уделенное время и интерес к нашей компании!
            </p>
        </div>
    </section>
</template>

<style scoped>
/* Checkmark */
.checkmark-circle {
    width: 150px;
    height: 150px;
    position: relative;
    display: inline-block;
}

.checkmark-circle .background {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: #00C09D;
    position: absolute;
}

.checkmark-circle .checkmark {
    border-radius: 5px;
}

.checkmark-circle .checkmark.draw:after {
    -webkit-animation-delay: 100ms;
    -moz-animation-delay: 100ms;
    animation-delay: 100ms;
    -webkit-animation-duration: 3s;
    -moz-animation-duration: 3s;
    animation-duration: 3s;
    -webkit-animation-timing-function: ease;
    -moz-animation-timing-function: ease;
    animation-timing-function: ease;
    -webkit-animation-name: checkmark;
    -moz-animation-name: checkmark;
    animation-name: checkmark;
    -moz-transform: scaleX(-1) rotate(135deg);
    -ms-transform: scaleX(-1) rotate(135deg);
    -o-transform: scaleX(-1) rotate(135deg);
    transform: scaleX(-1) rotate(135deg);
    -webkit-animation-fill-mode: forwards;
    -moz-animation-fill-mode: forwards;
    animation-fill-mode: forwards;
}

.checkmark-circle .checkmark:after {
    opacity: 1;
    height: 95px;
    width: 70px;
    -webkit-transform-origin: left top;
    transform-origin: left top;
    border-right: 15px solid white;
    border-top: 15px solid white;
    border-radius: 4px !important;
    content: '';
    left: 40px;
    top: 85px;
    position: absolute;
}

@-webkit-keyframes checkmark {
    0% {
        height: 0;
        width: 0;
        opacity: 1;
    }
    20% {
        height: 0;
        width: 37.5px;
        opacity: 1;
    }
    40% {
        height: 75px;
        width: 37.5px;
        opacity: 1;
    }
    100% {
        height: 75px;
        width: 37.5px;
        opacity: 1;
    }
}

@-moz-keyframes checkmark {
    0% {
        height: 0;
        width: 0;
        opacity: 1;
    }
    20% {
        height: 0;
        width: 37.5px;
        opacity: 1;
    }
    40% {
        height: 75px;
        width: 37.5px;
        opacity: 1;
    }
    100% {
        height: 75px;
        width: 37.5px;
        opacity: 1;
    }
}

@keyframes checkmark {
    0% {
        height: 0;
        width: 0;
        opacity: 1;
    }
    20% {
        height: 0;
        width: 37.5px;
        opacity: 1;
    }
    40% {
        height: 75px;
        width: 37.5px;
        opacity: 1;
    }
    100% {
        height: 75px;
        width: 37.5px;
        opacity: 1;
    }
}
</style>
