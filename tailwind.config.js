import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        fontWeight: {
            light: '300',
            normal: '400',
            medium: '500'
        },
        fontFamily: {
            sans: ['Rubik', ...defaultTheme.fontFamily.sans],
            display: ['Inter']
        },
        extend: {
            backgroundImage: {
                'fore-back': 'linear-gradient(to right, var(--color-foreground-primary) var(--fore-width), var(--color-background-primary) var(--fore-width))',
                'split': 'linear-gradient(-75deg, var(--color-foreground-primary) 50%, var(--color-background-primary) 50%)'
            },
            colors: {
                foreground: {
                    DEFAULT: "var(--color-foreground-primary)",
                    '300': "var(--color-foreground-300)",
                    '500': "var(--color-foreground-500)",
                    '700': "var(--color-foreground-700)"
                },
                background: {
                    DEFAULT: "var(--color-background-primary)",
                    '300': "var(--color-background-300)",
                },
            },
        },
    },
    plugins: [],
};
