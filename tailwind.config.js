/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './core/blocks/**/*.{php,vue}',
    'single-movie-api.php',
    './core/includes/assets/src/*.vue',
    './core/includes/assets/components/*.vue'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}