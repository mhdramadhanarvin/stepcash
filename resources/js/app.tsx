import "./bootstrap";
import "../css/app.css";

import { createRoot } from "react-dom/client";
import { createInertiaApp } from "@inertiajs/react";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { QueryClient, QueryClientProvider } from "react-query";
import reportWebVitals from "./reportWebVitals";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.tsx`,
            import.meta.glob(["./Pages/**/*.tsx", "../icons/**"]),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);
        const queryClient = new QueryClient({
            defaultOptions: {
                queries: {
                    keepPreviousData: true,
                },
            },
        });
        root.render(
            <QueryClientProvider client={queryClient}>
                <App {...props} />
            </QueryClientProvider>,
        );
    },
    //progress: {
    //    color: "#4B5563",
    //},
});

reportWebVitals();

//if ("serviceWorker" in navigator) {
//    window.addEventListener("load", () => {
navigator.serviceWorker
    .register("build/sw.js")
    .then((registration) => {
        console.log(
            "ServiceWorker registered with scope: ",
            registration.scope,
        );
    })
    .catch((error) => {
        console.error("ServiceWorker registration failed: ", error);
    });
//    });
//}
