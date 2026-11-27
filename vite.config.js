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
    // Serve on an IPv4 address: CSP host-sources cannot express IPv6 literals like [::1],
    // so the dev server's CSS would be blocked by the Content-Security-Policy.
    server: {
        host: '127.0.0.1',
    },
});
