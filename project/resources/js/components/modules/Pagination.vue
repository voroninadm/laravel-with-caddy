<script setup>

import {Link} from "@inertiajs/vue3";
import {computed} from "vue";
const props = defineProps([
    'from',
    'to',
    'total',
    'prev_page_url',
    'next_page_url',
    'links'
]);

const middleLinks = computed(() => {
    if (props.links.length <= 2) return []
    return props.links.slice(1, -1)
})
</script>

<template>
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <a :href="props.prev_page_url" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Предыдущая</a>
            <a :href="props.next_page_url" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Следующая</a>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <p class="text-sm text-gray-700">
                    Показано от
                    <span class="font-medium">{{ props.from }}</span>
                    до
                    <span class="font-medium">{{ props.to }}</span>
                    среди
                    <span class="font-medium">{{ props.total }}</span>
                    результатов
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">
                    <Link v-if="props.prev_page_url" :href="props.prev_page_url" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Предыдущая</span>
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                        </svg>
                    </Link>

                    <Link v-for="link in middleLinks"
                          :href="link.url" aria-current="page" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-red-200 focus:z-20 focus:outline-offset-0 md:inline-flex transition duration-300"
                        :class="{'z-10 bg-red-700/80 text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700/80': link.active }">
                        {{ link.label }}
                    </Link>
                    <Link v-if="props.next_page_url" :href="props.next_page_url" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Следующая</span>
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>

