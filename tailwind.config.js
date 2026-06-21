/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "C:/xampp/htdocs/CrystalWash2/CrystalWash/app/Views/**/*.php",
    "C:/xampp/htdocs/CrystalWash2/CrystalWash/public/**/*.php",
    "C:/xampp/htdocs/CrystalWash2/CrystalWash/**/*.php"
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
      boxShadow: {
        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
        'glow': '0 0 15px rgba(163, 177, 138, 0.5)',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0', transform: 'translateY(10px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        }
      }
    },
  },
  plugins: [],
}
