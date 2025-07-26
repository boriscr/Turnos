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
                'texto-titulo': 'var(--color_texto_titulo)',
                'texto-light': 'var(--color_texto_light)',
                'texto-dark': 'var(--color_texto_dark)',
                'primario-btn': 'var(--color_primario_btn)',
                'secondary-btn': 'var(--color_secundario_btn)',
                'fondo-dark': 'var(--fondo_aplicacion_dark)',
                'fondo-light': 'var(--fondo_aplicacion_light)',
                'navbar-dark': 'var( --fondo_navbar_dark)',
                'navbar-light': 'var( --fondo_navbar_light)',
            },
        },
    },

    plugins: [forms],
};