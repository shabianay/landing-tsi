import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/**
 * "Grid Blok" — the visual system is derived from the logo: a stepped stack of
 * square blocks, slate with one blue block. Corners stay nearly square, colour
 * is slate + ink + white, and brand blue (#3498DB) is used for one block per view.
 *
 * @type {import('tailwindcss').Config}
 */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Switzer', ...defaultTheme.fontFamily.sans],
                display: ['"Cabinet Grotesk"', 'Switzer', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Brand blue. 500 is the logo blue (fills, large type, ink text on top);
                // 600+ are the text-safe shades (≥4.5:1 on white).
                primary: {
                    50: '#EBF5FC',
                    100: '#D3E9F8',
                    200: '#A8D2F0',
                    300: '#79B9E6',
                    400: '#52A7E0',
                    500: '#3498DB',
                    600: '#1F78B4',
                    700: '#175E8F',
                    800: '#12476B',
                    900: '#0C2F47',
                },
                // Logo slate + ink. Kept under the existing "dark" name so every view shares it.
                dark: {
                    50: '#F1F3F5',
                    100: '#415060',
                    200: '#1C2630',
                    300: '#0F1720',
                },
                line: '#DDE2E7',
            },
            borderRadius: {
                // Near-square corners everywhere, echoing the logo blocks.
                sm: '1px',
                DEFAULT: '2px',
                md: '3px',
                lg: '4px',
                xl: '4px',
                '2xl': '6px',
                '3xl': '8px',
            },
            boxShadow: {
                sm: '0 1px 2px rgba(15, 23, 32, 0.06)',
                DEFAULT: '0 1px 3px rgba(15, 23, 32, 0.08)',
                md: '0 4px 10px -2px rgba(15, 23, 32, 0.10)',
                lg: '0 10px 24px -8px rgba(15, 23, 32, 0.14)',
                xl: '0 18px 36px -12px rgba(15, 23, 32, 0.18)',
                '2xl': '0 28px 56px -16px rgba(15, 23, 32, 0.24)',
            },
            maxWidth: {
                '8xl': '88rem',
            },
            transitionTimingFunction: {
                'out-expo': 'cubic-bezier(0.16, 1, 0.3, 1)',
            },
        },
    },

    plugins: [forms],
};
