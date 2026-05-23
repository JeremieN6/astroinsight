import type { Config } from 'tailwindcss'

export default {
  darkMode: 'class',
  content: [
    './components/**/*.{vue,js,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './app.vue',
    './plugins/**/*.{js,ts}',
    './composables/**/*.{js,ts}',
  ],
  theme: {
    extend: {
      colors: {
        cosmos: {
          900: '#0a0a1a',
          800: '#0d0d2b',
        },
        gold: '#f59e0b',
        orchid: '#a855f7',
      },
      fontFamily: {
        display: ['Cinzel', 'serif'],
        body: ['Inter', 'sans-serif'],
      },
      animation: {
        drift: 'drift 9s ease-in-out infinite',
        shimmer: 'shimmer 8s linear infinite',
        'hero-orbit': 'heroOrbit 24s linear infinite',
      },
      keyframes: {
        drift: {
          '0%, 100%': { transform: 'translateZ(0)' },
          '50%': { transform: 'translate3d(0,-12px,0)' },
        },
        shimmer: {
          '0%': { backgroundPosition: '200%' },
          '100%': { backgroundPosition: '-200%' },
        },
        heroOrbit: {
          '0%': { transform: 'rotate(0deg) translate(104px) rotate(0deg)' },
          '100%': { transform: 'rotate(360deg) translate(104px) rotate(-360deg)' },
        },
      },
      backgroundImage: {
        'hero-orbit':
          'radial-gradient(circle at top, rgba(168,85,247,0.24), transparent 42%), radial-gradient(circle at 20% 20%, rgba(245,158,11,0.16), transparent 30%), linear-gradient(135deg, rgba(124,58,237,0.08), rgba(10,10,26,0.02))',
      },
      boxShadow: {
        glass: '0 24px 80px rgba(10,10,26,0.45)',
      },
    },
  },
  plugins: [],
} satisfies Config
