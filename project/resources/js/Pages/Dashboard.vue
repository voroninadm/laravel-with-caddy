<script setup>
import {normalizeDashboardDates, showLongCurrentDate} from "@/common/dateTimeHelper.js"
import NTLlogo from "@/components/icons/NTLlogo.vue";
import {onMounted, onUnmounted, ref} from "vue";
import Tooltip from "@/components/Tooltip.vue";

const props = defineProps({
    dailyInfo: Object
})
const season = 'spring';

const applyParallaxToSeason = (seasonId, mouseXPercent, mouseYPercent) => {
    const seasonElement = document.getElementById(seasonId);
    if (!seasonElement) return;

    const background = seasonElement.querySelector(".background");
    const middleground = seasonElement.querySelector(".middleground");
    const foreground = seasonElement.querySelector(".foreground");

    moveLayer(background, mouseXPercent, mouseYPercent, 0.03);
    moveLayer(middleground, mouseXPercent, mouseYPercent, 0.07);
    moveLayer(foreground, mouseXPercent, mouseYPercent, 0.17);
};

const moveLayer = (layer, mouseXPercent, mouseYPercent, intensity) => {
    if (!layer) return;

    const xOffset = (mouseXPercent - 1) * intensity * 100;
    const yOffset = (mouseYPercent - 1) * intensity * 100;

    layer.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
};

const handleMouseMove = (event) => {
    const {clientX, clientY} = event;
    const screenWidth = window.innerWidth;
    const screenHeight = window.innerHeight;

    const mouseXPercentage = clientX / screenWidth;
    const mouseYPercentage = clientY / screenHeight;

    applyParallaxToSeason(season, mouseXPercentage, mouseYPercentage);
};

// dates


const dateStart = normalizeDashboardDates(props.dailyInfo ? props.dailyInfo['date_start'] : '');
const dateFinish = normalizeDashboardDates(props.dailyInfo ? props.dailyInfo['date_finish'] : '');


onMounted(() => {
    document.addEventListener("mousemove", handleMouseMove);
});

onUnmounted(() => {
    document.removeEventListener("mousemove", handleMouseMove);
});
</script>

<template>
    <div class="absolute left-1/4 top-24 flex flex-col gap-2 text-bold">
        <NTLlogo />
        <p class="italic text-gray-400">Сегодня {{ showLongCurrentDate() }} </p>
    </div>
    <div class="w-full h-full">

        <section id="spring" class="season" v-if="season === 'spring'">
            <img class="background" src="@assets/img/spring/spring-background.svg" alt="Spring background"/>
            <img class="middleground" src="@assets/img/spring/spring-middleground.svg" alt="Spring middleground"/>
            <img class="foreground" src="@assets/img/spring/spring-foreground.svg" alt="Spring foreground"/>
        </section>

        <section id="autumn" class="season" v-if="season === 'autumn'">
            <img class="background" src="@assets/img/autumn/autumn-background.svg" alt="Autumn background"/>
            <img class="middleground" src="@assets/img/autumn/autumn-middleground.svg" alt="Autumn middleground"/>
            <img class="foreground" src="@assets/img/autumn/autumn-foreground.svg" alt="Autumn foreground"/>
        </section>

        <section id="winter" class="season" v-if="season === 'winter'">
            <img class="background" src="@assets/img/winter/winter-background.svg" alt="Winter background"/>
            <img class="middleground" src="@assets/img/winter/winter-middleground.svg" alt="Winter middleground"/>
            <img class="foreground" src="@assets/img/winter/winter-foreground.svg" alt="Winter foreground"/>
        </section>

        <section id="summer" class="season" v-if="season === 'summer'">
            <img class="background" src="@assets/img/summer/summer-background.svg" alt="Summer background"/>
            <img class="middleground" src="@assets/img/summer/summer-middleground.svg" alt="Summer middleground"/>
            <img class="foreground" src="@assets/img/summer/summer-foreground.svg" alt="Summer foreground"/>
        </section>
    </div>

    <div v-if="season === 'autumn'" class="falling_wrap">
        <p class="bg01"><img src="@assets/img/leaf.png" alt="autumn_leaf"></p>
        <p class="bg02"><img src="@assets/img/brown_leaf.png" alt="autumn_leaf"></p>
        <p class="bg03"><img src="@assets/img/leaf.png" alt="autumn_leaf"></p>
        <p class="bg04"><img src="@assets/img/brown_leaf.png" alt="autumn_leaf"></p>
        <p class="bg05"><img src="@assets/img/brown_leaf.png" alt="autumn_leaf"></p>
        <p class="bg06"><img src="@assets/img/leaf.png" alt="autumn_leaf"></p>
        <p class="bg07"><img src="@assets/img/brown_leaf.png" alt="autumn_leaf"></p>
    </div>

    <div v-if="season === 'winter'" class="falling_wrap">
        <p class="bg01"><img src="@assets/img/snowflake1.webp" alt="snowflake"></p>
        <p class="bg02"><img src="@assets/img/snowflake2.webp" alt="snowflake"></p>
        <p class="bg03"><img src="@assets/img/snowflake3.webp" alt="snowflake"></p>
        <p class="bg04"><img src="@assets/img/snowflake2.webp" alt="snowflake"></p>
        <p class="bg05"><img src="@assets/img/snowflake1.webp" alt="snowflake"></p>
        <p class="bg06"><img src="@assets/img/snowflake3.webp" alt="snowflake"></p>
        <p class="bg07"><img src="@assets/img/snowflake1.webp" alt="snowflake"></p>
    </div>

    <section v-if="props.dailyInfo"
             class="flex flex-col p-6 mb-10 animate__animated animate__fadeIn animate__delay-2s z-10">
        <p class="m-auto flex items-center text-gray-400 p-2 text-xs gap-2">
            с {{ dateStart }} по {{ dateFinish }} фактически произведено
            <tooltip class="text-center">Обновление ежедневно в 09:00</tooltip>
        </p>
        <div class="flex gap-5 justify-center text-sm">

            <div class="flex flex-col items-center p-2 border border-gray-200 rounded-lg text-gray-400">
                <p class="font-bold uppercase flex items-center gap-2">Экструзия</p>
                <p class="flex items-center gap-2">
                    <span>{{ props.dailyInfo['extrusion_mass'] }} килограмм</span>
                    <span
                        v-if="props.dailyInfo['extrusion_mass_status'] && props.dailyInfo['extrusion_mass_status'] !== 'no change'"
                        class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent"
                        :class="{'border-t-[7px] border-t-red-300': props.dailyInfo['extrusion_mass_status'] === 'down',
                      'border-b-[7px] border-b-green-300' :  props.dailyInfo['extrusion_mass_status'] === 'up'}"
                    />
                </p>
                <span>{{ props.dailyInfo['extrusion_length'] }} погонных метров</span>
            </div>

            <div class="flex flex-col items-center p-2 border border-gray-200 rounded-lg text-gray-400">
                <p class="font-bold uppercase flex items-center gap-2">Печать</p>
                <p class="flex items-center gap-2">
                    <span>{{ props.dailyInfo['printing_mass'].toFixed(2) }} килограмм</span>
                    <span
                        v-if="props.dailyInfo['printing_mass_status'] && props.dailyInfo['printing_mass_status'] !== 'no change'"
                        class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent"
                        :class="{'border-t-[7px] border-t-red-300': props.dailyInfo['printing_mass_status'] === 'down',
                      'border-b-[7px] border-b-green-300' :  props.dailyInfo['printing_mass_status'] === 'up'}"
                    />
                </p>
                <span>{{ props.dailyInfo['printing_length'].toFixed(2) }} погонных метров</span>
            </div>

            <div class="flex flex-col items-center p-2 border border-gray-200 rounded-lg text-gray-400">
                <p class="font-bold uppercase flex items-center gap-2">Ламинация</p>
                <p class="flex items-center gap-2">
                    <span>{{ props.dailyInfo['lamination_mass'] }} килограмм</span>
                    <span
                        v-if="props.dailyInfo['lamination_mass_status'] && props.dailyInfo['lamination_mass_status'] !== 'no change'"
                        class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent"
                        :class="{'border-t-[7px] border-t-red-300': props.dailyInfo['lamination_mass_status'] === 'down',
                      'border-b-[7px] border-b-green-300' :  props.dailyInfo['lamination_mass_status'] === 'up'}"
                    />
                </p>
                <span>{{ props.dailyInfo['lamination_length'] }} погонных метров</span>
            </div>

            <div class="flex flex-col items-center p-2 border border-gray-200 rounded-lg text-gray-400">
                <p class="font-bold uppercase flex items-center gap-2">Сварка</p>
                <p class="flex items-center gap-2">
                    <span>{{ props.dailyInfo['welding'] }} тысяч штук</span>
                    <span v-if="props.dailyInfo['welding_status'] && props.dailyInfo['welding_status'] !== 'no change'"
                          class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent"
                          :class="{'border-t-[7px] border-t-red-300': props.dailyInfo['welding_status'] === 'down',
                      'border-b-[7px] border-b-green-300' :  props.dailyInfo['welding_status'] === 'up'}"
                    />
                </p>
            </div>
            <div class="flex flex-col items-center p-2 border border-gray-200 rounded-lg text-gray-400">
                <p class="font-bold uppercase flex items-center gap-2">Резка</p>
                <p class="flex items-center gap-2">
                    <span>{{ props.dailyInfo['cutting_mass'] }} килограмм</span>
                    <span v-if="props.dailyInfo['cutting_mass_status'] && props.dailyInfo['cutting_mass_status'] !== 'no change'"
                          class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent"
                          :class="{'border-t-[7px] border-t-red-300': props.dailyInfo['cutting_mass_status'] === 'down',
                      'border-b-[7px] border-b-green-300' :  props.dailyInfo['cutting_mass_status'] === 'up'}"
                    />
                </p>
                <span>{{ props.dailyInfo['cutting_length'] }} сырьевых метров</span>
            </div>
            <div class="flex flex-col items-center p-2 border border-gray-200 rounded-lg text-gray-400">
                <p class="font-bold uppercase flex items-center gap-2">Переработка</p>
                <p class="flex items-center gap-2">
                    <span>{{ props.dailyInfo['recycling'] }} килограмм</span>
                    <span
                        v-if="props.dailyInfo['recycling_status'] && props.dailyInfo['recycling_status'] !== 'no change'"
                        class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent"
                        :class="{'border-t-[7px] border-t-red-300': props.dailyInfo['recycling_status'] === 'down',
                      'border-b-[7px] border-b-green-300' :  props.dailyInfo['recycling_status'] === 'up'}"
                    />
                </p>

            </div>
        </div>
    </section>
    <section v-else class="m-auto p-10">
        <p class="italic text-gray-500 text-sm">Аааа!!! Суточный отчет поврежден или не сформирован. Дождитесь формирования нового отчета или обратитесь к разработчику</p>
    </section>
</template>


<style scoped>


.background,
.middleground,
.foreground {
    position: absolute;
}

.season {
    position: absolute;
    top: 25%;
    left: 50%;
    transform: translateX(-50%);
    width: 800px;
    height: 400px;
    border-radius: 30px;
    z-index: -2;
}

/* -- Spring -- */

#spring {
    background: linear-gradient(#4ad4ff, #fff);
    opacity: 1;
}

#spring > .background {
    top: 150px;
    left: 130px;
    animation: fadeInMy forwards;
    opacity: 0;
    animation-delay: 0.2s;
    animation-duration: 2s;
}

#spring > .middleground {
    top: 60px;
    left: -60px;
    opacity: 0;
    animation: fadeInMy forwards;
    animation-duration: 2s;
    animation-delay: 0.8s;
}

#spring > .foreground {
    top: -10px;
    left: 350px;
    animation: fadeInMy forwards;
    opacity: 0;
    animation-delay: 1.6s;
    animation-duration: 2s;
}

/* -- Summer -- */

#summer {
    background: linear-gradient(#0694fb, #e7f2fb);
}

#summer > .background {
    top: 250px;
    left: 80px;
    animation: fadeInMy forwards;
    opacity: 0;
    animation-delay: 0.2s;
    animation-duration: 2s;
}

#summer > .middleground {
    top: 150px;
    left: 305px;
    opacity: 0;
    animation: fadeInMy forwards;
    animation-duration: 2s;
    animation-delay: 0.8s;
}

#summer > .foreground {
    top: -50px;
    left: -160px;
    animation: fadeInMy forwards;
    opacity: 0;
    animation-delay: 1.6s;
    animation-duration: 2s;
}

/* -- Autumn -- */

#autumn {
    background: linear-gradient(#f3ba89, #f9eca9);
}

#autumn > .background {
    top: 65px;
    left: 75px;
    animation: fadeInMy forwards;
    opacity: 0;
    animation-delay: 0.2s;
    animation-duration: 2s;
}

#autumn > .middleground {
    top: 65px;
    left: -50px;
    opacity: 0;
    animation: fadeInMy forwards;
    animation-duration: 2s;
    animation-delay: 0.8s;
}

#autumn > .foreground {
    top: -90px;
    left: 360px;
    animation: fadeInMy forwards;
    opacity: 0;
    animation-delay: 1.6s;
    animation-duration: 2s;
}

/* -- Winter -- */

#winter {
    background: linear-gradient(#b5c8d0, #fadb8d);
}

#winter > .background {
    top: 150px;
    left: -35px;
    animation: fadeInMy forwards;
    opacity: 0;
    animation-delay: 0.2s;
    animation-duration: 2s;
}

#winter > .middleground {
    top: 105px;
    left: 320px;
    opacity: 0;
    animation: fadeInMy forwards;
    animation-duration: 2s;
    animation-delay: 0.8s;
}

#winter > .foreground {
    top: 50px;
    left: -188px;
    animation: fadeInMy forwards;
    opacity: 0;
    animation-delay: 1.6s;
    animation-duration: 2s;
}

@keyframes fadeInMy {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}


.falling_wrap {
    position: absolute;
    padding: 0;
    margin: 0;
    width: 100%;
    height: 100%;

    z-index: -1;
}

.falling_wrap p {
    position: absolute;
    list-style: none;
    top: -50px;
    animation: fall 4s linear infinite;
}


@keyframes fall {
    to {
        top: 97%;
    }
}

@keyframes sway1 {
    from {
        transform: translateX(0px) rotate(0deg);
    }

    to {
        transform: translateX(200px) rotate(-45deg);
    }
}

@keyframes sway2 {
    from {
        transform: translateX(200px) rotate(-45deg);
    }

    to {
        transform: translateX(0px) rotate(0deg);
    }
}

.falling_wrap p img {
    width: 100%;
}

.falling_wrap .bg01 {
    left: 0%;
    width: 24px;
    height: 15px;
    animation: fall 10s linear infinite,
    sway1 3s ease-in-out infinite alternate;
    animation-delay: 2s;
}

.falling_wrap .bg02 {
    left: 5%;
    width: 13px;
    height: 9px;
    animation: fall 15s linear infinite,
    sway1 2s ease-in-out infinite alternate;
    animation-delay: 8s;
}

.falling_wrap .bg03 {
    left: 15%;
    width: 16px;
    height: 10px;
    animation: fall 9s linear infinite,
    sway1 3.5s ease-in-out infinite alternate;
    animation-delay: 13s;
}

.falling_wrap .bg04 {
    left: 25%;
    width: 26px;
    height: 15px;
    animation: fall 10s linear infinite,
    sway2 2s ease-in-out infinite alternate;
    animation-delay: 6s;
}

.falling_wrap .bg05 {
    left: 65%;
    width: 16px;
    height: 10px;
    animation: fall 7s linear infinite,
    sway2 3.5s ease-in-out infinite alternate;
    animation-delay: 7s;
}

.falling_wrap .bg06 {
    left: 50%;
    width: 13px;
    height: 9px;
    animation: fall 7s linear infinite,
    sway1 3s ease-in-out infinite alternate;
    animation-delay: 3s;
}

.falling_wrap .bg07 {
    left: 75%;
    width: 16px;
    height: 10px;
    animation: fall 10s linear infinite,
    sway2 4s ease-in-out infinite alternate;
    animation-delay: 4s;
}

.falling_wrap .bg07 {
    left: 85%;
    width: 26px;
    height: 15px;
    animation: fall 10s linear infinite,
    sway2 4s ease-in-out infinite alternate;
    animation-delay: 4s;
}
</style>


