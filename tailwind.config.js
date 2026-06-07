/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.jsx",
        "./resources/**/*.ts",
        "./resources/**/*.tsx",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                    950: '#1e1b4b',
                },
            },
            backgroundImage: {
                'gradient-primary': 'linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7)',
                'gradient-success': 'linear-gradient(135deg, #10b981, #059669)',
                'gradient-warning': 'linear-gradient(135deg, #f59e0b, #ea580c)',
                'gradient-danger': 'linear-gradient(135deg, #ef4444, #ec4899)',
                'gradient-info': 'linear-gradient(135deg, #3b82f6, #06b6d4)',
                'gradient-dark': 'linear-gradient(135deg, #0f172a, #1e1b4b, #312e81)',
                'gradient-body': 'linear-gradient(135deg, #f8fafc, #e0e7ff, #ede9fe)',
            },
            boxShadow: {
                'glow-primary': '0 0 20px rgba(99, 102, 241, 0.3)',
                'glow-success': '0 0 20px rgba(16, 185, 129, 0.3)',
                'glow-warning': '0 0 20px rgba(245, 158, 11, 0.3)',
                'glow-danger': '0 0 20px rgba(239, 68, 68, 0.3)',
            },
            animation: {
                'fade-in-down': 'fadeInDown 0.5s ease-out',
                'fade-in-up': 'fadeInUp 0.5s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-slow': 'bounce 2s infinite',
                'float': 'float 8s ease-in-out infinite',
            },
            keyframes: {
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0) scale(1)' },
                    '50%': { transform: 'translateY(-20px) scale(1.05)' },
                },
            },
        },
    },
    plugins: [],
};
