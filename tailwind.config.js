import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "wisal-beige": "#D1CBB4", // Beige Heritage - warm secondary / traditional accents
                "wisal-aqua": "#467389", // Aqua Blue - primary identity color (buttons, interactive nodes)
                "wisal-ivory": "#FFFBF5", // Ivory - main light background
                "wisal-charcoal": "#323232", // Charcoal - text colors and dark UI elements
            },
            fontFamily: {
                sans: ["Outfit", "Inter", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
