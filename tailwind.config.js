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
        extend: {
            fontFamily: {
                next: ["Atkinson Hyperlegible Next", "sans-serif"],
                //   josefin: ["Josefin Sans", "sans-serif"],
                // poppins: ["Poppins", "sans-serif"],
            },

            screens: {
                xs: '200px',
                sm: '480px',
                md: '768px',
                lg: '976px',
                xl: '1440px',
            },
        },
    },
    plugins: [],
};
