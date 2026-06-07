import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/react.jsx',
                'resources/js/vue.js',
                'resources/ts/main.tsx',
            ],
            refresh: true,
        }),
        react(),
        vue(),
    ],
    build: {
        sourcemap: false,
        minify: 'oxc',
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    if (id.includes('node_modules/react') || id.includes('node_modules/vue')) return 'vendor';
                },
            },
        },
    },
    server: {
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },
});
