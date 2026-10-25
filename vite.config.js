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
        // The vendored bxslider CSS still carries IE-only `*zoom` hacks; drop them instead of failing
        lightningcss: { errorRecovery: true },
        preprocessorOptions: {
            scss: {
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'slash-div'],
            },
        },
    },
});
