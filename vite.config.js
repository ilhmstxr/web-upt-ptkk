import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: [
                "resources/views/**",
                "app/View/Components/**",
                "app/Livewire/**", // Tambahkan jika Anda menggunakan Livewire
                "app/Filament/**", // Tambahkan jika Anda menggunakan Filament
                "routes/**",
            ],
        }),
        tailwindcss(),
    ],
    css: {
        postcss: {
            
            plugins: {
                "tailwindcss/nesting": "postcss-nesting",
                tailwindcss: {},
                autoprefixer: {},
            },
        },
    },
    server: {
        cors: true,
    },
});
