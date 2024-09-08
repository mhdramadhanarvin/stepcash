import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.tsx",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
                serif: ["Poppins", ...defaultTheme.fontFamily.serif],
                mono: ["Poppins", ...defaultTheme.fontFamily.mono],
            },
            colors: {
                commons: "#00AA5B",
            },
        },
    },

    plugins: [forms],
};
