/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/js/**/*.{js,ts,jsx,tsx}"],
    theme: {
        extend: {
            boxShadow: {
                cum: "0 35px 60px -15px rgba(0, 0, 0, 0.3)",
            },
        },
    },
    plugins: [],
};
