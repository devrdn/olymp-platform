/** @type {Plugin} */

import plugin from "tailwindcss/plugin";

module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Raleway","ui-sans-serif", "system-ui","-apple-system","Segoe UI","Roboto","Helvetica Neue","Noto Sans","Liberation Sans","Arial"],
      },
      backgroundImage: {
       'body-image': "url('/public/images/background.jpg')"
      }
    },
  },
  plugins: [
      plugin(function ({ addUtilities, addComponents, e, config }) {
        addComponents({
          '.btn-primary': {
            "@apply py-2.5 px-7 bg-blue-600": {}
          }
        })
      })
  ],
}

