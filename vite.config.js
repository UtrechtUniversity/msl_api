import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import react from '@vitejs/plugin-react';
// import vue from '@vitejs/plugin-vue';

import tailwindcss from 'tailwindcss'
import commonjs from 'vite-plugin-commonjs';

export default defineConfig({    
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/css/treejs.css',
            'resources/css/treejs-custom.css',
            'resources/css/leaflet.css',
            'resources/js/app.js',
            'resources/js/leaflet-src.js',            
            'resources/js/tooltip.js',
            'resources/js/jstree.js',
            'resources/js/filters-menu.js',
            'resources/js/filters-menu-labs.js',
            'resources/js/keyword-form.js'
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
    css: {
        postcss: {
          plugins: [tailwindcss()],
        }
    }    
});