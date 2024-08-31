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
                name: "StepCash",
                start_url: "/",
                short_name: "StepCash",
                description: "Convert your step to any reward.",
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
                screenshots: [
                    {
                        src: "/screenshots/screenshot-mobile-1.png",
                        sizes: "640x1136",
                        type: "image/png",
                    },
                    {
                        src: "/screenshots/screenshot-desktop-1.png",
                        sizes: "1280x800",
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
