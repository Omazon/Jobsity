/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './core/blocks/**/*.{php,vue}',
    'single-movie-api.php',
    './core/includes/assets/src/*.vue'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}