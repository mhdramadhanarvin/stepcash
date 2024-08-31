import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import path from "path";
import { VitePWA } from "vite-plugin-pwa";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.tsx",
            refresh: true,
        }),
        react(),
        VitePWA({
            registerType: "prompt",
            includeAssets: [
                "favicon.svg",
                "favicon.ico",
                "robots.txt",
                "apple-touch-icon.png",
            ],
            manifest: {
                name: "My Laravel PWA",
                start_url: "/",
                short_name: "LaravelPWA",
                description: "My Laravel Inertia.js React PWA application",
                theme_color: "#ffffff",
                icons: [
                    {
                        src: "icon-192x192.png",
                        sizes: "192x192",
                        type: "image/png",
                    },
                    {
                        src: "icon-512x512.png",
                        sizes: "512x512",
                        type: "image/png",
                    },
                ],
            },
            workbox: {
                globPatterns: ["*/*.*", "*.*"],
                navigateFallback: null,
            },
        }),
    ],
    resolve: {
        alias: {
            "~fonts": path.resolve(__dirname, "resources/fonts"),
        },
    },
});
