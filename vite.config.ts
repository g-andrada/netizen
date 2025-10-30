import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
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
    server: {
        host: '0.0.0.0', // Listen to all network interfaces
        hmr: {
            // host: '10.0.0.171'
            host: '10.0.0.170'
        },
        cors: {
            origin: [
                'http://localhost:8000',
                'http://10.0.0.171:8000',
                'http://10.0.0.171:5173',
                'http://10.0.0.170:8000',
                'http://10.0.0.170:5173'
            ],
            methods: [ 'GET', 'POST', 'PUT', 'DELETE' ],
            allowedHeaders: [ 'Content-Type', 'Authorization' ]
        }
    }
});
