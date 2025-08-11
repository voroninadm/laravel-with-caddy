<script setup>

import NTLlogo from "@/components/icons/NTLlogo.vue";
import {Link} from "@inertiajs/vue3";
import DropdownLink from "@/components/DropdownLink.vue";
import Dropdown from "@/components/Dropdown.vue";
</script>

<template>
    <div class="px-20 py-2 flex items-center justify-between border shadow-lg bg-white z-10">
        <Link as="button" type="button" :href="route('testing.index')" method="get">
            <NTLlogo />
        </Link>

        <Link as="button" type="button" href="/personal-testing/logout" method="post"
              class="px-3 py-2 border rounded-lg shadow-lg uppercase tracking-wild text-gray-500 text-sm hover:bg-gray-500 hover:text-white transition duration-300">
            Выйти
        </Link>
    </div>
</template>

<style scoped>

</style>
