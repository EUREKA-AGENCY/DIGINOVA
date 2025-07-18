import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                'diginova': ['Poppins', 'Montserrat', ...defaultTheme.fontFamily.sans],
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'diginova': {
                    'blue': '#0C1122',
                    'red': '#E12428',
                    DEFAULT: '#0C1122', // Couleur par défaut (bleu)
                },
            },
            spacing: {
                'logo-square': '1em', // Taille du carré rouge du logo
            },
            borderColor: {
                'diginova': {
                    DEFAULT: '#E12428', // Rouge par défaut pour les bordures
                }
            },
        },
    },

    plugins: [
        forms,
        function({ addComponents }) {
            addComponents({
                '.logo': {
                    display: 'flex',
                    'flex-direction': 'column',
                    'align-items': 'center',
                    '&__monogram': {
                        display: 'flex',
                        'align-items': 'center',
                        'font-weight': 'bold',
                        'font-size': '2.25rem',
                        'color': '#0C1122',
                    },
                    '&__square': {
                        'background-color': '#E12428',
                        'width': '1em',
                        'height': '1em',
                        'display': 'inline-block',
                        'margin-left': '0.2em',
                    },
                    '&__text': {
                        'position': 'relative',
                        'margin-top': '0.5rem',
                        'font-weight': 'bold',
                        'font-size': '1.5rem',
                        'color': '#0C1122',
                        '&::after': {
                            'content': '""',
                            'position': 'absolute',
                            'bottom': '-0.25rem',
                            'left': '0',
                            'width': '100%',
                            'height': '0.25rem',
                            'background-color': '#E12428',
                        }
                    }
                },
                '.diginova-uppercase': {
                    'font-family': "'Poppins', sans-serif",
                    'text-transform': 'uppercase',
                    'letter-spacing': '0.05em',
                }
            })
        }
    ],
};