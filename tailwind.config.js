import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Define tus variables CSS como colores personalizados
                'texto-titulo': 'var(--title_text_color)',
                'texto-light': 'var(--light_text_color)',
                'texto-dark': 'var(--dark_text_color)',
                'primario-btn': 'var(--primary_color_btn)',
                'secondary-btn': 'var(--secondary_color_btn)',
                'fondo-dark': 'var(--dark_application_background)',
                'fondo-light': 'var(--light_application_background)',
                'navbar-dark': 'var( --background_navbar_dark)',
                'navbar-light': 'var( --background_navbar_light)',
            },
        },
    },

    plugins: [forms],
};