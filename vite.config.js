import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/sass/register.scss',
                'resources/js/register.js',
            ],
            refresh: true,
        }),
    ],
    server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: {
        host: '192.168.1.4',
    },

    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler'
            }
        }
    }
},

});
