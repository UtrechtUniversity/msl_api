import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
// import react from '@vitejs/plugin-react';
// import vue from '@vitejs/plugin-vue';

// import tailwindcss from 'tailwindcss'
import commonjs from 'vite-plugin-commonjs';
export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel([
            'resources/css/app.css',
            'resources/css/treejs.css',
            'resources/css/treejs-custom.css',
            'resources/ts/app.ts',
            'resources/ts/tooltip.ts',
            'resources/ts/jstree.ts',
            'resources/ts/filters-menu.ts',
            'resources/ts/filters-menu-labs.ts',
            'resources/ts/keyword-form.ts',
            'resources/ts/dp/dp-map.ts'
        ]),
        commonjs()
    ],
    build: {
        modulePreload: false
    },
    server: {
        hmr: {
            host: 'localhost'
        },
        watch: {
            usePolling: true,
        },

    },

});