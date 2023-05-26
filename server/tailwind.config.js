/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Raleway","ui-sans-serif", "system-ui","-apple-system","Segoe UI","Roboto","Helvetica Neue","Noto Sans","Liberation Sans","Arial"],
      }
    },
  },
  plugins: [],
}

