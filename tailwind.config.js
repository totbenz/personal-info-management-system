import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
export default {

    presets: [
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",

        "./vendor/wireui/wireui/resources/**/*.blade.php",
        "./vendor/wireui/wireui/ts/**/*.ts",
        "./vendor/wireui/wireui/src/View/**/*.php",

        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',

        "./vendor/vildanbina/livewire-wizard/resources/views/*.blade.php"
      ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // colors: {
            //     '': '#052569',
            //     'main_hover': '#1C3469',
                // 'secondary': '#FCC008',
                // 'tertiary': '#FA0302',
                // 'success': '#22C55E',
                // 'danger': '#FA0302',
                // 'gray-darkest': '#212529',
                // 'gray-dark': '#353535',
                // 'gray-light': '#e9ecef',
                // 'gray-lightest': '#F8F9FA'
            //   },
            colors: {
                primary: colors.indigo,
                secondary: colors.gray,
                positive: colors.emerald,
                negative: colors.red,
                warning: colors.amber,
                info: colors.blue,
                main: '#052569',
                main_hover: '#1C3469',
                dandelion: '#FCC008',
                success: '#22C55E',
                danger: '#FA0302'
            },
        },
    },
    plugins: [forms, typography],
}

