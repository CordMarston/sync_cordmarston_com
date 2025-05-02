import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5174,
        strictPort: true,
        hmr: {
            protocol: 'wss',
            host: 'sync.cordmarston.com',
            clientPort: 443,
            path: '/@vite', // important!
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: false, // or true if you prefer, but websocket is more important
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
});