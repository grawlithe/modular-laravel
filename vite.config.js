import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import collectModuleAssetsPaths from './vite-module-loader.js';
const allPaths = await collectModuleAssetsPaths(['resources/js/app.js'], 'Modules');
const modulePath = path.resolve(__dirname, 'Modules');

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'Modules/Contacts/resources/assets/js/app.js',
                allPaths,
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            // 💡 Add an alias for the Contacts/Contact module's resources folder
            // This can sometimes help Vite map the request path to the file system path.
            '@module-contact': path.resolve(modulePath, 'Contacts/resources/assets/js'),
        },
    },
});
