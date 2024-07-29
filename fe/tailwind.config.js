import daisyui from 'daisyui';

/** @type {import('tailwindcss').Config} */
export default {
	content: ['./src/**/*.{html,js,svelte,ts}'],
	theme: {
		extend: {
			fontSize: {
				// Heading
				h1: '56px',
				h2: '52px',
				h3: '48px',
				h4: '44px',
				h5: '40px',
				h6: '36px',
				// Body
				xl: '28px',
				lg: '24px',
				md: '20px',
				sm: '12px',
				xs: '8px'
			},
			colors: {
				grey: '#D1D5DB',
				red: '#FF0000',
				blue: '#125FF3',
				'clear-grey': '#8F8F8F',
				background: '#F7F7F6',
				white: '#FEFCFF',
				black: '#212529',
				'french-violet': '#7F2CCB',
				violet: '#FF84E8',
				'lavender-pink': '#FFA9E7'
			}
		}
	},
	plugins: [daisyui],
	daisyui: {
		themes: ['light'],
		prefix: 'daisy-'
	}
};
