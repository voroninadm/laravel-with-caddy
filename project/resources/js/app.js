import '../css/app.css';
import 'animate.css';

import './bootstrap.js';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from '/vendor/tightenco/ziggy/dist/index.js';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

createInertiaApp({
    title: (title) => `${title}` ? `${title} | NTL` : `NTL`,
    resolve: async (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: false });
        let page = await pages[`./Pages/${name}.vue`](); // Вызываем функцию для загрузки модуля
        page.default.layout = page.default.layout || DefaultLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VueDatePicker)
            .mount(el);
    },
    progress: {
        delay: 250,
        color: '#b81414',
        includeCSS: true,
        showSpinner: true,
    },
});
