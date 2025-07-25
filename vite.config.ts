import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    esbuild: {
        jsx: 'automatic',
    },
    optimizeDeps: {
        include: ['apexcharts', 'react-apexcharts'],
    },
    resolve: {
        alias: {
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
    server: {
        host: '127.0.0.1',   // ✅ Force local host, no Herd
        port: 5173,          // ✅ Default Vite port
        https: false,        // ✅ Ensure no Herd SSL
        watch: {
            usePolling: true,
            interval: 300,    // ✅ Stable file watching (Windows-friendly)
        },
        hmr: {
            host: '127.0.0.1',
        },
    },
});
