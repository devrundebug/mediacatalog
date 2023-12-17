import { defineConfig } from 'vite'
import { dirname, resolve } from 'path';

import symfonyPlugin from 'vite-plugin-symfony';
import vuePlugin from "@vitejs/plugin-vue";
import { fileURLToPath } from 'url';
import vue from "@vitejs/plugin-vue";

const basicPlaygroundDir = dirname(fileURLToPath(import.meta.url));

export default defineConfig(({command, mode, ssrBuild}) => {
    console.log(command, mode, ssrBuild)

    const commonConfig = {
        base: '/build/',
        plugins: [
            vuePlugin(),
            symfonyPlugin({
                debug: false
            }),
        ],
        publicDir: 'public/build',
        resolve: {
            alias: {
                '@': resolve(basicPlaygroundDir, 'assets/src'),
            }
        },
        build: {
            // outDir: resolve(basicPlaygroundDir, 'public/build'),
            assetsInlineLimit: 512,
            manifest: true,
            rollupOptions: {
                input: {
                    app: "./assets/app.js"
                },
                output: {
                    dir: "public/build",
                    manualChunks: {
                        vue: ['vue']
                    },
                }
            }
        },
    }

    if(mode === 'production') {
        return {
            ...commonConfig
        };
    }

    return commonConfig;

});