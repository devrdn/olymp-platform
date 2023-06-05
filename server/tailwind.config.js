/** @type {Plugin} */

import plugin from "tailwindcss/plugin";

module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        "main": "#1D1D1DD9",
        "secondary": "rgba(29,29,29,0.90)",
        "table": "rgba(63,63,63,0.8)"
      },
      fontFamily: {
        sans: ["Raleway","ui-sans-serif", "system-ui","-apple-system","Segoe UI","Roboto","Helvetica Neue","Noto Sans","Liberation Sans","Arial"],
        mono: ["JetBrains Mono", "ui-monospace", "SFMono-Regular", "monospace"],
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
            "@apply bg-blue-600": {}
          },
          '.btn-primary:hover': {
            "@apply bg-blue-700": {}
          },
          '.btn-link': {
            "@apply m-0 block px-7 py-2.5 bg-blue-600": {}
          },
          '.btn-link:hover': {
            "@apply bg-blue-700": {}
          }
        })
      })
  ],
}

