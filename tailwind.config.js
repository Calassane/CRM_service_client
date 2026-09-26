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
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#eff8ff', 100: '#dbefff', 200: '#bee3ff', 300: '#91d2ff',
                    400: '#5db8fd', 500: '#3798fa', 600: '#2178ef', 700: '#1a60dc',
                    800: '#1c4db2', 900: '#1d438c', 950: '#172a55',
                },
            },
            boxShadow: {
                panel: '0 1px 2px rgba(15, 23, 42, 0.03), 0 12px 32px rgba(15, 23, 42, 0.055)',
                lift: '0 18px 45px rgba(23, 42, 85, 0.16)',
            },
        },
    },

    plugins: [forms],
};
