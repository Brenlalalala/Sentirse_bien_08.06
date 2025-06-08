import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/*/.blade.php',
        './resources/*/.blade.php',
        './resources/*/.js',
        './resources/*/.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                lavanda: '#A084DC',
                spa: '#E8DFF5',
                coral: '#F27C38',
                'gris-suave': '#F3F4F6',
                oscuro: '#3A3A3A',
            },
        },
    },

    plugins: [forms],
};