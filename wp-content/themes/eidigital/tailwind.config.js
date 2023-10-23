/** @type {import('tailwindcss').Config} */

const fontSizes = {};
const lineHeights = {};
const heights = {};
for (let i = 10; i <= 200; i++) {
  fontSizes[i] = `${i}px`;
  lineHeights[i] = `${i}px`;
}

for (let i = 10; i <= 2000; i++) {
  heights[i+'px'] = `${i}px`;
}

module.exports = {
  content: [
    "./assets/local/js/**/*.{js,ts,jsx,tsx}",
    "./assets/local/sass/**/*.scss",
    "./template-parts/**/*.php",
    "./includes/**/*.php",
    "header.php",
    "footer.php",
		"single-*.php",
    "page-*.php",
  ],
  important: false,
  theme: {
    container: {
      padding: '2%',
    },
    extend: {
      gridTemplateColumns: {
        'header-navigation-mobile': '200px 1fr',
        'header-navigation-desktop': '200px 1fr 200px',
      },
			fontSize: fontSizes,
      lineHeight: lineHeights
    },
    screens: {
      xs: "350px",
      sm: "480px",
      md: "768px",
      lg: "1024px",
			slider: "1100px",
      desktop: "1280px",
      xl: "1440px",
      xxl: "1600px",
    },
    fontFamily: {
      serif: ["poppins"],
      sans: ["poppins"],
      title: ['poppins', 'Helvetica Neue', 'Helvetica', 'sans-serif'],
      subtitle: ['poppins', 'Helvetica Neue', 'Helvetica', 'sans-serif'],
      body: ['poppins', 'Helvetica Neue', 'Helvetica', 'sans-serif'],
      cta: ['poppins', 'Helvetica Neue', 'Helvetica', 'sans-serif']
    },
    colors: {
			transparent: 'transparent',
      red: { 
        DEFAULT: "#e5002b",
        100: "#ef4444",
        300: "#ef4444",
        500: "#dc2626",
      },
      black: {
        DEFAULT: "#000",
        400: "#171719",
        300: "#111111",
        200: "#475569",
        100: "#cbd5e1",
      },
      white: "#fff",
      gray: {
        300: "#e8e8e1",
        400: "#B7B7B7",
        DEFAULT: "#eee",
        500: "#b6b6b6",
        600: "#505050",
        700: "#979797",
				800: '#ABABAB',
      },
    },
  },
  variants: {
    textColor: ["hover", "focus"],
    borderColor: ["focus", "hover"],
    backgroundColor: ["hover"],
  },
  safelist: [
    {
      pattern: /(bg|text|border|outline|placeholder|from|via|to|ring|divide|fill|stroke)-(white|black)/
      
    },
    'transparent',
    'aspect-video',
    'gap-4',
    'translate-1/2'
  ],
};
