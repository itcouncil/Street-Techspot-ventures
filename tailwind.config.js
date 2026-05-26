module.exports = {
	content: [
		'./*.php',
		'./inc/**/*.php',
		'./woocommerce/**/*.php',
		'./assets/js/**/*.js',
	],
	theme: {
		extend: {
			colors: {
				'stv-black': '#050505',
				'stv-carbon': '#0B0F14',
				'stv-graphite': '#111827',
				'stv-teal': '#00FFD1',
				'stv-orange': '#FF6B00',
				'stv-blue': '#4DA3FF',
				'stv-green': '#00FF99',
				'stv-white': '#F9FAFB',
				'stv-muted': '#9CA3AF',
				'stv-silver': '#D1D5DB',
			},
			fontFamily: {
				sans: [ 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif' ],
			},
		},
	},
	plugins: [],
};
