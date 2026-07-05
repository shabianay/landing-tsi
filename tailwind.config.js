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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Clash Display', 'Inter', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: '#eaf5fb',
                    100: '#d0eaf7',
                    200: '#a1d5ef',
                    300: '#72c0e7',
                    400: '#43abdf',
                    500: '#3498DB',
                    600: '#2a7aaf',
                    700: '#1f5b83',
                    800: '#153d57',
                    900: '#0a1e2c',
                },
                dark: {
                    50: '#f0f2f4',
                    100: '#415060',
                    200: '#1a1a1a',
                    300: '#000000',
                }
            },
        },
    },

    plugins: [forms],
};
