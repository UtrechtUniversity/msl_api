import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

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
            'resources/ts/tracker.ts',
            'resources/ts/dataPublication/mapController.ts',

            'resources/ts/dataPublication/tab-handle.ts',
            'resources/css/generalStyles/popupWithDirection.css',
            'resources/css/dataPublicationMap/datapublications-list.css',
            'resources/css/dataPublicationMap/in-map-styles.css',
            'resources/css/dataPublicationMap/top-menu.css',
            'resources/css/dataPublicationMap/pagination.css',
            'resources/css/dataPublicationMap/metadata.css',

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
