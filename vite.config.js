import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    // ── Development server (local + ngrok tunnelling) ─────────────────────────
    server: {
        host: '0.0.0.0',
        port: 5173,
        // For ngrok HMR, update VITE_NGROK_DOMAIN in .env instead of hardcoding
        hmr: process.env.VITE_NGROK_DOMAIN
            ? { host: process.env.VITE_NGROK_DOMAIN, protocol: 'wss' }
            : true,
        cors: true,
    },

    // ── Production build optimisations ───────────────────────────────────────
    build: {
        // Rolldown (Vite 8 default) handles minification — no separate tool needed
        // Increase chunk size warning threshold (Tailwind bundles are large)
        chunkSizeWarningLimit: 1024,
        rollupOptions: {
            output: {
                // Split vendor JS into separate cacheable chunk (function form for Rolldown/Vite 8)
                manualChunks: (id) => {
                    if (id.includes('alpinejs')) return 'vendor';
                },
                // Content-hash filenames for aggressive browser caching
                entryFileNames:   'assets/[name]-[hash].js',
                chunkFileNames:   'assets/[name]-[hash].js',
                assetFileNames:   'assets/[name]-[hash][extname]',
            },
        },
        // Source maps only in development
        sourcemap: false,
        // Enable CSS code splitting
        cssCodeSplit: true,
    },
});
