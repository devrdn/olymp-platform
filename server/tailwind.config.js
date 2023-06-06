/** @type {import('tailwindcss').Config} */

import plugin from "tailwindcss/plugin";

module.exports = {
  content: [
      "./src/**/*.php",
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        "main": "#1D1D1D",
        "back": "#121212",
        "secondary": "rgba(29,29,29,1)",
        "table": "rgba(63,63,63,0.8)",
        "controls": "rgb(17, 24, 39)"
      },
      fontFamily: {
        sans: ["Raleway","ui-sans-serif", "system-ui","-apple-system","Segoe UI","Roboto","Helvetica Neue","Noto Sans","Liberation Sans","Arial"],
        mono: ["JetBrains Mono", "ui-monospace", "SFMono-Regular", "monospace"],
      },
      backgroundImage: {
        'body-image': "url('/public/images/background.jpg')",
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
        },
        '.text-input': {
          "@apply w-full outline-none p-2 bg-transparent border-b-2 border-white focus:border-blue-500 ease-in-out duration-300": {}
        }
      })
    })
  ],
}