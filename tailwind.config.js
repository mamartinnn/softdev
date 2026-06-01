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
                display: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    primary: '#1e40af',    // Biru Safir
                    secondary: '#000000',  // Hitam Murni
                    accent: '#eab308',     // Kuning Aksen
                    gold: '#f59e0b',       // Emas
                    dark: '#04112c',       // Biru Malam Gelap
                    darker: '#020c24',     // Biru Malam Lebih Gelap
                },
            },
            backgroundImage: {
                'gradient-brand': 'linear-gradient(135deg, #1e40af, #000000)',
                'gradient-dark': 'linear-gradient(135deg, #000000, #04112c)',
                'gradient-gold': 'linear-gradient(135deg, #000000, #eab308)',
                'gradient-sidebar': 'linear-gradient(180deg, #000000 0%, #020c24 40%, #000000 100%)',
                'gradient-card': 'linear-gradient(135deg, rgba(30,64,175,.25), rgba(0,0,0,.95))',
                'gradient-button': 'linear-gradient(135deg, #1e40af 0%, #000000 100%)',
            },
            boxShadow: {
                'brand': '0 20px 40px rgba(30,64,175,.2), 0 0 20px rgba(234,179,8,.12)',
                'brand-lg': '0 25px 50px rgba(30,64,175,.25), 0 0 30px rgba(234,179,8,.15)',
            },
        },
    },

    plugins: [forms],
};
