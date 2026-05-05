/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#570013",
        primary_container: "#800020",
        surface: "#fbf9f5",
        surface_low: "#f5f3ef",
        surface_lowest: "#ffffff",
        outline_variant: "#e0bfbf",
        on_surface: "#1b1c1a",
      },
      fontFamily: {
        serif: ['"Noto Serif"', 'serif'],
        sans: ['Manrope', 'sans-serif'],
      }
    },
  },
  plugins: [],
}