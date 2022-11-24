import {defineConfig} from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'
export default defineConfig({
    plugins: [
        vue({
            template: { transformAssetUrls }
        }),
        laravel({
            input: ['resources/js/app.ts'],
            refresh: true,
        }),
        quasar(),

    ],
    build: {
        sourcemap: true,
    },
});
