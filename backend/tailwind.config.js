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
                maroon: {
                    DEFAULT: '#7B2D2D',
                    dark: '#5A2121',
                    darker: '#3e1616',
                },
                terracotta: {
                    DEFAULT: '#7AA36A',
                    light: '#f0f5ee',
                    dark: '#5A7D4D',
                },
                forest: {
                    DEFAULT: '#4A7C3F',
                    dark: '#295127',
                    darker: '#1a3619',
                },
                navy: {
                    DEFAULT: '#1B2A4A',
                    dark: '#0f1a30',
                    darker: '#070c18',
                    light: '#e8eaee',
                },
                primary: {
                    50: '#e8eaee',
                    100: '#c5cbd8',
                    200: '#9ea8bc',
                    300: '#7482a0',
                    400: '#556688',
                    500: '#364b6d',
                    600: '#2a3b59',
                    700: '#1b2a4a',
                    800: '#1B2A4A',
                    900: '#070c18',
                },
            },
        },
    },

    plugins: [forms],
};
