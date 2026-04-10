import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig, loadEnv } from 'vite';



export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
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
            host: '0.0.0.0', // Accept connection from any device on the network
            hmr: {
                host: `${env.VITE_HOST}` // Hor reload for Websockets
            },
            cors: {
                origin: [
                    `http://${env.VITE_HOST}:8000`, // Web server
                    `http://${env.VITE_HOST}:5173`, // Vite server
                ],
                // origin: true,
                methods: [ 'GET', 'POST', 'PUT', 'DELETE' ],
                allowedHeaders: [ 'Content-Type', 'Authorization' ]
            }
        }
    }
});
