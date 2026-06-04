/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./public/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        olive: {
          50: '#f9fbf2',
          100: '#f0f4e1',
          200: '#e1ebd0',
          300: '#ccdcae',
          400: '#a3b18a', // Light green from original CSS
          500: '#839665',
          600: '#64764b',
          700: '#4b5320', // Primary Olive from original CSS
          800: '#3e461f',
          900: '#353c1d',
        },
        dark: {
          DEFAULT: '#1a1a1a',
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
      },
    },
  },
  plugins: [],
}
