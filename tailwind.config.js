/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/views/*.blade.php",
    ],
    theme: {
      extend: {
        backgroundImage: {
          gradient: "linear-gradient(0deg, #000000 4.75%, #2a0f45 67.27%)",
        },
        colors: {
          primary: '#6F28E2',
        }
      },
    },
    plugins: [],
}