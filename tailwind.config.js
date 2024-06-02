/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "ProviderMain/**/*.{php,js}",
    "ProviderMain/*.php",
    "webApp/**/*.htm.php",
    "webApp/**/**/*.htm.php",
    "webApp/html/**/*.htm.php"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

