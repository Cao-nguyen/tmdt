/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Màu pastel cho website bán hoa
        'rose-pastel': '#FFE4E6',
        'rose-light': '#FDA4AF',
        'rose-main': '#FB7185',
        'rose-dark': '#E11D48',

        'green-pastel': '#DCFCE7',
        'green-light': '#86EFAC',
        'green-main': '#4ADE80',
        'green-dark': '#16A34A',

        'pink-pastel': '#FCE7F3',
        'pink-light': '#F9A8D4',
        'pink-main': '#F472B6',
        'pink-dark': '#DB2777',

        'white-pure': '#FFFFFF',
        'cream': '#FFFBEB',
        'cream-light': '#FEF3C7',

        'gray-light': '#F3F4F6',
        'gray-medium': '#9CA3AF',
        'gray-dark': '#4B5563',
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
      },
    },
  },
  plugins: [],
}