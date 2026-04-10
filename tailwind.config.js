import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Livewire/**/*.php',
        './app/Http/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans:     ['DM Sans', ...defaultTheme.fontFamily.sans],
                headline: ['DM Sans', ...defaultTheme.fontFamily.sans],
                body:     ['DM Sans', ...defaultTheme.fontFamily.sans],
                label:    ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // ── Primary: Dark Terracotta (main brand / primary CTAs) ────────
                'primary':                    '#7c3d1f',
                'on-primary':                 '#ffffff',
                'primary-container':          '#fde8d5',
                'on-primary-container':       '#3d1500',
                'primary-fixed':              '#ffd5b4',
                'primary-fixed-dim':          '#e8b07a',
                'on-primary-fixed':           '#2d1000',
                'on-primary-fixed-variant':   '#5c2e10',

                // ── Secondary: Teal (secondary actions, variety) ─────────────────
                'secondary':                  '#0d6b62',
                'on-secondary':               '#ffffff',
                'secondary-container':        '#cc9933',   // gold — kept for CTAs
                'on-secondary-container':     '#3d2200',
                'secondary-fixed':            '#d0f4ef',
                'secondary-fixed-dim':        '#87d1c8',
                'on-secondary-fixed':         '#003731',
                'on-secondary-fixed-variant': '#004e47',

                // ── Tertiary: Indigo (info, links, variety) ──────────────────────
                'tertiary':                   '#3730a3',
                'on-tertiary':                '#ffffff',
                'tertiary-container':         '#e0e7ff',
                'on-tertiary-container':      '#1e1b4b',
                'tertiary-fixed':             '#c7d2fe',
                'tertiary-fixed-dim':         '#a5b4fc',
                'on-tertiary-fixed':          '#1e1b4b',
                'on-tertiary-fixed-variant':  '#312e81',

                // ── Surfaces: White-based ─────────────────────────────────────────
                'background':                 '#ffffff',
                'on-background':              '#1a0a00',
                'surface':                    '#ffffff',
                'on-surface':                 '#1a0a00',
                'on-surface-variant':         '#52433a',
                'surface-dim':                '#e4d8ce',
                'surface-bright':             '#ffffff',
                'surface-container-lowest':   '#ffffff',
                'surface-container-low':      '#faf7f5',
                'surface-container':          '#f5efea',
                'surface-container-high':     '#efe6de',
                'surface-container-highest':  '#e8dbd1',
                'surface-tint':               '#7c3d1f',
                'surface-variant':            '#ede0d6',

                // ── Outline ───────────────────────────────────────────────────────
                'outline':                    '#87736a',
                'outline-variant':            '#d8c8be',

                // ── Inverse ───────────────────────────────────────────────────────
                'inverse-surface':            '#362010',
                'inverse-on-surface':         '#ffeedd',
                'inverse-primary':            '#e8a87a',

                // ── Error ─────────────────────────────────────────────────────────
                'error':                      '#ba1a1a',
                'on-error':                   '#ffffff',
                'error-container':            '#ffdad6',
                'on-error-container':         '#93000a',

                // ── Accent (gold highlight) ───────────────────────────────────────
                accent: {
                    DEFAULT: '#cc9933',
                    dark:    '#a07a20',
                    light:   '#f0c85a',
                },

                // ── Legacy hali aliases ───────────────────────────────────────────
                hali: {
                    teal:             '#0d6b62',
                    'teal-dark':      '#094f48',
                    orange:           '#cc9933',
                    bg:               '#ffffff',
                    card:             '#ffffff',
                    'text-primary':   '#1a0a00',
                    'text-secondary': '#52433a',
                    border:           '#d8c8be',
                },
            },
            borderRadius: {
                DEFAULT: '0.125rem',
                sm:      '0.125rem',
                md:      '0.375rem',
                lg:      '0.5rem',
                xl:      '0.75rem',
                '2xl':   '1rem',
                '3xl':   '1.5rem',
                full:    '9999px',
            },
            boxShadow: {
                card:         '0 1px 3px 0 rgba(26,10,0,0.06)',
                'card-hover': '0 8px 32px 0 rgba(26,10,0,0.08)',
                ambient:      '0 8px 64px 0 rgba(26,10,0,0.06)',
                sidebar:      '4px 0 24px 0 rgba(26,10,0,0.04)',
            },
        },
    },

    plugins: [forms, typography],
};
