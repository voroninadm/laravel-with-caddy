<script setup>
import {Link} from "@inertiajs/vue3";
import {computed} from "vue";

const props = defineProps({
    routeName: String,
    imageName: String,
    title: String
})

const getImageSrc = computed(() => {
    return new URL(`/resources/assets/img/personal-testing/${props.imageName}`, import.meta.url)
});
</script>

<template>
    <Link :href="route(props.routeName)" class="hover:scale-105 hover:-rotate-1 transition duration-500">
        <div class=" w-full rounded-lg shadow-lg shadow-gray-400 bg-white">
            <img :src="getImageSrc" class="rounded-t-lg" width="auto" height="auto"
                 :alt="props.title"
                 loading="lazy">
            <p class="text text-center p-2 text-gray-500 uppercase tracking-tight">{{ props.title }}</p>
        </div>
    </Link>
</template>
