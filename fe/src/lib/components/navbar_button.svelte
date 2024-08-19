<script lang="ts">
	import { sessionPage } from '$lib/utils/page';
	import beranda from '$lib/assets/beranda.svg';
	import berandaactive from '$lib/assets/berandaactive.svg';
	import koleksi from '$lib/assets/koleksi.svg';
	import koleksiactive from '$lib/assets/koleksiactive.svg';
	import riwayat from '$lib/assets/riwayat.svg';
	import riwayatactive from '$lib/assets/riwayatactive.svg';
	import profile from '$lib/assets/profile.svg';
	import profileactive from '$lib/assets/profileactive.svg';

	export let option: string;
	const options: string[] = ['beranda', 'koleksi', 'riwayat', 'profile'];
	const images: { name: string; image: string; active: string }[] = [
		{
			name: 'beranda',
			image: beranda,
			active: berandaactive
		},
		{
			name: 'koleksi',
			image: koleksi,
			active: koleksiactive
		},
		{
			name: 'riwayat',
			image: riwayat,
			active: riwayatactive
		},
		{
			name: 'profile',
			image: profile,
			active: profileactive
		}
	];

	$: isActive = $sessionPage === option;

	import { ripple } from 'svelte-ripple-action';

	// Find the image object that matches the option
	const imageObject = images.find(img => img.name === option);

	// Set the image source based on the option and isActive status
	$: imageSrc = imageObject ? (isActive ? imageObject.active : imageObject.image) : '';
</script>

<button
	use:ripple
	class={`rounded-full size-16 p-4 m-2 ${isActive ? `bg-french-violet text-white` : ``} flex justify-center items-center flex-col transition duration-300`}
	on:click={() => {
		sessionPage.set(option);
		window.scrollTo(0, 0);
	}}
>
	<img
		src={imageSrc}
		alt={option}
		class="size-6 mb-1"
	/>
	<p class="text-sm">{option.charAt(0).toUpperCase() + option.slice(1)}</p>
</button>
