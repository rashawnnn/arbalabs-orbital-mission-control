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
        space: '#050505',     // Latar belakang hitam pekat ala ArbaLabs
        panel: '#0a0f18',     // Warna kotak panel biru sangat gelap transparan
        neon: '#4db8ff',      // Biru terang ala cahaya server di gambar ke-2
        alert: '#ff2a2a',     // Merah untuk trigger peluncuran
      },
      fontFamily: {
        mono: ['"ui-monospace"', '"SFMono-Regular"', '"Menlo"', '"Monaco"', '"Consolas"', 'monospace'], 
      }
    },
  },
  plugins: [],
}