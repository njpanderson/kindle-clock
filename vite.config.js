import { defineConfig } from 'vite';
import legacy from '@vitejs/plugin-legacy'
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        legacy({
            targets: ['chrome >= 64']
        }),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@lib': '/resources/js/lib',
            '@components': '/resources/js/components',
            '@': '/resources/js',
        }
    },
    css: {
        devSourcemap: true
    }
});
