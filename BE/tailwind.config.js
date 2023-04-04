const defaultTheme = require('tailwindcss/defaultTheme');
// require('tailwindcss/colors');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                montserrat: ['Montserrat'],
                sans: ['"Montserrat"']
            },
            fontSize: {
                xs: [
                    '12px',
                    {
                        lineHeight: '17px',
                        letterSpacing: '0.004em'
                    }
                ],
                sm: [
                    '14px',
                    {
                        lineHeight: '21px',
                        letterSpacing: '0.004em'
                    }
                ],
                tiny: '.875rem',
                base: '.875rem',
                lg: '1.125rem',
                xl: '1.25rem',
                '2xl': '1.5rem',
                '3xl': '1.875rem',
                '4xl': '2.25rem',
                '5xl': '3rem',
                '6xl': '4rem',
                '7xl': '5rem',
                '8xl': '5rem'
            },
            colors: {
                pink: {
                    350: '#ff7fab'
                },
                gray: {
                    150: '#eceff1',
                    250: '#FAFAFA',
                    350: '#4F5B84',
                    450: '#141A3C'
                },
                blue: {
                    350: '#99ABCE'
                },
            }
        },
    },
    variants: {
        extend: {
            // opacity: ['disabled'],
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
