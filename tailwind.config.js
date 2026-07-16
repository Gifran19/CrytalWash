/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./app/Views/**/*.php",
    "./public/**/*.php",
    "./app/Controllers/**/*.php",
    "./*.php"
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
      // Ukuran font diperbesar untuk keterbacaan usia 25-60 tahun
      fontSize: {
        'xs':   ['0.875rem', { lineHeight: '1.5' }],   // 14px (was 12px)
        'sm':   ['1rem',     { lineHeight: '1.6' }],   // 16px (was 14px)
        'base': ['1.125rem', { lineHeight: '1.75' }],  // 18px (was 16px)
        'lg':   ['1.25rem',  { lineHeight: '1.75' }],  // 20px (was 18px)
        'xl':   ['1.375rem', { lineHeight: '1.75' }],  // 22px (was 20px)
        '2xl':  ['1.625rem', { lineHeight: '1.4' }],   // 26px (was 24px)
        '3xl':  ['2rem',     { lineHeight: '1.3' }],   // 32px (was 30px)
        '4xl':  ['2.5rem',   { lineHeight: '1.2' }],   // 40px (was 36px)
        '5xl':  ['3.25rem',  { lineHeight: '1.1' }],   // 52px (was 48px)
        '6xl':  ['4rem',     { lineHeight: '1' }],     // 64px (was 60px)
        '7xl':  ['4.75rem',  { lineHeight: '1' }],     // 76px (was 72px)
        '8xl':  ['6.5rem',   { lineHeight: '1' }],     // 104px (was 96px)
        '9xl':  ['8.5rem',   { lineHeight: '1' }],     // 136px (was 128px)
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
