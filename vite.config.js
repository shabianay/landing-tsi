import { defineConfig } from 'vite';
import { existsSync } from 'node:fs';
import laravel from 'laravel-vite-plugin';

const publicDirectory = existsSync('public/index.php') ? 'public' : 'public_html';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            publicDirectory,
            refresh: true,
        }),
    ],
});
