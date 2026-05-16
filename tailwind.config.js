import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            // ── GoToEat Design System — Gastronomic Efficiency ──────────
            colors: {
                'primary':                    '#9d4300',
                'primary-container':          '#f97316',
                'on-primary':                 '#ffffff',
                'on-primary-container':       '#582200',
                'on-primary-fixed':           '#341100',
                'on-primary-fixed-variant':   '#783200',
                'primary-fixed':              '#ffdbca',
                'primary-fixed-dim':          '#ffb690',
                'inverse-primary':            '#ffb690',
                'secondary':                  '#006c49',
                'secondary-container':        '#6cf8bb',
                'on-secondary':               '#ffffff',
                'on-secondary-container':     '#00714d',
                'on-secondary-fixed':         '#002113',
                'on-secondary-fixed-variant': '#005236',
                'secondary-fixed':            '#6ffbbe',
                'secondary-fixed-dim':        '#4edea3',
                'tertiary':                   '#006398',
                'tertiary-container':         '#00a2f4',
                'on-tertiary':                '#ffffff',
                'on-tertiary-container':      '#003554',
                'on-tertiary-fixed':          '#001d32',
                'on-tertiary-fixed-variant':  '#004b74',
                'tertiary-fixed':             '#cde5ff',
                'tertiary-fixed-dim':         '#93ccff',
                'background':                 '#f8f9ff',
                'on-background':              '#0b1c30',
                'surface':                    '#f8f9ff',
                'surface-dim':                '#cbdbf5',
                'surface-bright':             '#f8f9ff',
                'surface-variant':            '#d3e4fe',
                'surface-tint':               '#9d4300',
                'surface-container':          '#e5eeff',
                'surface-container-low':      '#eff4ff',
                'surface-container-high':     '#dce9ff',
                'surface-container-highest':  '#d3e4fe',
                'surface-container-lowest':   '#ffffff',
                'on-surface':                 '#0b1c30',
                'on-surface-variant':         '#584237',
                'inverse-surface':            '#213145',
                'inverse-on-surface':         '#eaf1ff',
                'outline':                    '#8c7164',
                'outline-variant':            '#e0c0b1',
                'error':                      '#ba1a1a',
                'error-container':            '#ffdad6',
                'on-error':                   '#ffffff',
                'on-error-container':         '#93000a',
            },

            fontFamily: {
                heading: ['Sora', 'sans-serif'],
                body:    ['Inter', ...defaultTheme.fontFamily.sans],
                sans:    ['Inter', ...defaultTheme.fontFamily.sans],
            },

            fontSize: {
                'h1':         ['48px', { lineHeight: '1.2', letterSpacing: '-0.02em', fontWeight: '700' }],
                'h2':         ['36px', { lineHeight: '1.3', letterSpacing: '-0.01em', fontWeight: '600' }],
                'h3':         ['24px', { lineHeight: '1.4', fontWeight: '600' }],
                'body-lg':    ['18px', { lineHeight: '1.6', fontWeight: '400' }],
                'body-md':    ['16px', { lineHeight: '1.5', fontWeight: '400' }],
                'body-sm':    ['14px', { lineHeight: '1.5', fontWeight: '400' }],
                'label-caps': ['12px', { lineHeight: '1',   letterSpacing: '0.05em', fontWeight: '600' }],
            },

            borderRadius: {
                DEFAULT: '0.25rem',
                lg:  '0.5rem',
                xl:  '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
                full: '9999px',
            },

            spacing: {
                xs: '8px', sm: '16px', md: '24px', lg: '40px', xl: '64px',
                gutter: '24px', container_max: '1280px', base: '4px',
            },
        },
    },

    plugins: [forms],
};
