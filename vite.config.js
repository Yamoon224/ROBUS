import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
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
            '@': '/resources/js',
            'vue': 'vue/dist/vue.esm-bundler.js'
        },
    },
    build: {
        // Désactiver le SSR
        ssr: false,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('vue')) {
                            return 'vendor-vue'
                        }
                        if (id.includes('sweetalert2')) {
                            return 'vendor-swal'
                        }
                        if (id.includes('axios')) {
                            return 'vendor-axios'
                        }
                        if (id.includes('pusher-js') || id.includes('laravel-echo')) {
                            return 'vendor-pusher'
                        }
                        return 'vendor'
                    }
                }
            }
        },
        commonjsOptions: {
            include: [/node_modules/],
            transformMixedEsModules: true
        }
    },
    optimizeDeps: {
        include: [
            'vue', 
            'axios', 
            'sweetalert2', 
            'pusher-js', 
            'laravel-echo'
        ]
    },
    // Configuration spécifique pour éviter les problèmes SSR
    ssr: {
        noExternal: ['vue', '@vue/server-renderer']
    }
})