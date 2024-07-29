<script lang="ts">
	import { sessionPage } from '$lib/utils/page';

	import Beranda from '$lib/pages/beranda.svelte';
	import Koleksi from '$lib/pages/koleksi.svelte';
	import Riwayat from '$lib/pages/riwayat.svelte';
	import Profile from '$lib/pages/profile.svelte';
	import Landing from '$lib/pages/landing.svelte';

	import { onMount } from 'svelte';
	import { authStatus } from '$lib/utils/guard';
	import Toko from '$lib/pages/toko.svelte';
	import Produk from '$lib/pages/produk.svelte';
	import Troli from '$lib/pages/troli.svelte';

	$: $authStatus;

	// Scroll to top functionality
	let isScrolled = false;
	onMount(() => {
		const handleScroll = () => {
			isScrolled = window.scrollY > 0;
		};
		window.addEventListener('scroll', handleScroll);
		return () => {
			window.removeEventListener('scroll', handleScroll);
		};
	});

	// Define which pages should be layouted
	const layoutedPages = ['beranda', 'koleksi', 'riwayat', 'profile'];

	// Reactive state
	$: $sessionPage;
</script>

{#if $authStatus}
	{#if $sessionPage === 'landing'}
		<Landing />
	{:else}
		<div class={`${layoutedPages.includes($sessionPage) && `pt-24 lg:pt-32 pb-32 lg:pb-0 lg:ps-32`}`}>
			<!-- Padding because fixed navbar and header would block the contents -->
			{#if $sessionPage === 'beranda'}
				<Beranda />
			{/if}

			{#if $sessionPage === 'koleksi'}
				<Koleksi />
			{/if}

			{#if $sessionPage === 'riwayat'}
				<Riwayat />
			{/if}

			{#if $sessionPage === 'profile'}
				<Profile />
			{/if}

			{#if $sessionPage === 'produk'}
				<Produk />
			{/if}

			{#if $sessionPage === 'troli'}
				<Troli />
			{/if}

			{#if $sessionPage === 'toko'}
				<Toko />
			{/if}
		</div>
	{/if}
{:else}
	<Landing />
{/if}

<!-- Scroll to top -->
<button
	type="button"
	on:click={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
	class={`fixed bottom-40 lg:bottom-16 end-12 rounded-full shadow-lg ${!isScrolled && `opacity-0`} transition duration-300 z-50`}
>
	<svg
		xmlns="http://www.w3.org/2000/svg"
		width="48"
		height="48"
		fill="#125FF3"
		class="bi bi-arrow-up-circle-fill"
		viewBox="0 0 16 16"
	>
		<path
			d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"
		/>
	</svg>
</button>
