import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        })
    ],
    build: {
        minify: 'esbuild',
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks: undefined,
                compact: true
            }
        }
    }
});
/*
# 1. Limpia cache y builds viejos
rm -rf public/build vendor storage/framework/views/*

# 2. Reinstala dependencias (por si acaso)
npm install

# 3. Build de CSS y JS
npm run build

# 4. O para desarrollo con watch
npm run dev
*/