import {fileURLToPath, URL} from 'node:url'

import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import symfonyPlugin from 'vite-plugin-symfony'

// https://vitejs.dev/config/
// check https://github.com/lhapaipai/symfony-vite-dev/blob/main/playground/with-cdn/vite.config.js
export default defineConfig(({command, mode, ssrBuild}) => {
    const commonConfig = {
        base: 'static/build',
        publicDir: false,
        plugins: [
            vue(),
            symfonyPlugin(),
        ],
        resolve: {
            alias: {
                '@': fileURLToPath(new URL('./src', import.meta.url)),
            }
        },
        build: {
            plugins: [vue(), symfonyPlugin()],
            rollupOptions: {
                input: {
                    app: "./src/main.js"
                },
            }
        },

        server: {
            proxy: {
                '/api': {
                    target: 'http://localhost:801',
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/api/, '/api'),
                },
                '/static': {
                    target: 'http://localhost:801',
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/static/, '/static'),
                }
            }
        }
    }

    if(mode === 'production') {
        return {
            ...commonConfig,
            build: {
                plugins: [vue(), symfonyPlugin()],
                outDir: '../../public/static/build/',
                cssCodeSplit: false,
                rollupOptions: {
                    input: {
                        app: "./src/main.js"
                    },
                }
            },
        }
    }
    return commonConfig;
});
