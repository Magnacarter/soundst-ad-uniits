/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  purge: {
    enabled: true,
    content: ["./src/**/*.{html,js,php}"],
  },
  theme: {
    extend: {},
  },
  plugins: [],
}
