/** @type {import('tailwindcss').Config} */
export default {
  content: ["./src/**/*.{vue,js,ts}"],
  theme: {
    extend: {},
  },
  plugins: [
    require("daisyui"),
  ],
  daisyui: {
        themes: [
            {
                test: {
                    // "default": "#F9A000",
                    "primary": "#FEE5B4",
                    "secondary": "#267126",
                    "accent": "#0A320A",
                    "neutral": "#B8BCC8",
                    //"base-100": "#ff00ff",
                    "info": "#F9A000",
                    "success": "green",
                    "warning": "yellow",
                    "error": "red",
                },
            },
        ],
    },
}

