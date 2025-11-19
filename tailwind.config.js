/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/**/*.php",
    "./routes/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        'tomorrow-blue': {
          'bg': '#0a2540',
          'fg': '#ffffff',
          'selection': '#0d8a6f',
          'cursor': '#ffffff',
          'comment': '#7285b7',
          'red': '#ff9da4',
          'orange': '#ffc58f',
          'yellow': '#ffeead',
          'green': '#7cffcb',
          'aqua': '#a0ffe3',
          'blue': '#bbdaff',
          'purple': '#ebbbff'
        },
        'green-blue': {
          'darkest': '#041628',
          'darker': '#051b30',
          'dark': '#0a2540',
          'medium': '#0e3356',
          'light': '#124070',
          'teal': '#0d8a6f',
          'teal-dark': '#0a7259',
          'cyan': '#7cffcb',
          'cyan-light': '#a0ffe3'
        }
      }
    },
  },
  plugins: [],
}