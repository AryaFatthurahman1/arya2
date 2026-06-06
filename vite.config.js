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
            ],
            refresh: true,
        }),
        react(),
        vue(),
    ],
    build: {
        sourcemap: false,
        minify: 'esbuild',
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['react', 'react-dom', 'vue'],
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
