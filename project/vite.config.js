import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite'
import { fileURLToPath, URL } from "url";

export default defineConfig({
    server: {
        hmr: {
            host: 'localhost',
        },
        host: true,
        port: '40000',
        hot: true
    },
    plugins: [
        tailwindcss(),
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            "@/*": fileURLToPath(new URL("resources/js/", import.meta.url)),
            "@assets": fileURLToPath(new URL("resources/assets/", import.meta.url)),
        },
    },
    build: {
        chunkSizeWarningLimit: 1000,
        minify: true,
    },
    // ssr: {
    //     noExternal: ['file-saver', 'vue-toast-notification', 'moment', 'vue'],
    // },
});
