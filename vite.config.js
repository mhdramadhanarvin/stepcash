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
            strategies: "generateSW",
            //registerType: "autoUpdate",
            includeAssets: [
                "favicon.svg",
                "favicon.ico",
                "robots.txt",
                "apple-touch-icon.png",
            ],
            manifest: {
                name: "My Laravel PWA",
                short_name: "LaravelPWA",
                description: "My Laravel Inertia.js React PWA application",
                theme_color: "#ffffff",
                icons: [
                    {
                        src: "/icons/icon-192x192.png",
                        sizes: "192x192",
                        type: "image/png",
                    },
                    {
                        src: "/icons/icon-512x512.png",
                        sizes: "512x512",
                        type: "image/png",
                    },
                ],
            },
            workbox: {
                globPatterns: ["**/*.{js,css,html,png,svg,ico}"], // Ensure index.html and other assets are included
            },
        }),
    ],
    resolve: {
        alias: {
            "~fonts": path.resolve(__dirname, "resources/fonts"),
        },
    },
});
