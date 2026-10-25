import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/plugins.css',
                'resources/js/app.js',
                'resources/js/photography.js',
                'resources/js/outreach.js',
                'resources/js/publications.js',
            ],
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'slash-div'],
            },
        },
    },
});
