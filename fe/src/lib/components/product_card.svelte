<script lang="ts">
	export let data: ProductData;

	import type ProductData from '$lib/types/ProductData';
	import { sessionPage } from '$lib/utils/page';

	// import Carousel from '$lib/components/carousel.svelte';
	import noimage from '$lib/assets/noimage.svg';

	import { ripple } from 'svelte-ripple-action';
	import type { FileData } from '$lib/types/FileData';
	import { onMount } from 'svelte';
	import { api, store } from '$lib/utils/api';

	$: image = {} as FileData;
	let allLoaded = false;

	onMount(async () => {
		const res = await api.get(`/product/photo/${data.id}`);
		image = res.data[0];
		data.images?.push(image.filename);

		allLoaded = true;
	});
</script>

<div class={`w-[40vw] lg:w-[12vw] p-2 border border-grey rounded-lg m-2`}>
	{#if image}
		{#if image.filename}
			<img src={store + image.filename} alt="Product" class={`w-full h-48 rounded-lg`} />
		{:else}
			<svg
				class="animate-spin h-16 w-16 mx-auto"
				xmlns="http://www.w3.org/2000/svg"
				fill="none"
				viewBox="0 0 24 24"
			>
				<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
				></circle>
				<path
					class="opacity-75"
					fill="#125FF3"
					d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
				></path>
			</svg>
		{/if}
	{:else}
		<img src={noimage} alt="Product" class={`w-full h-48 rounded-lg`} />
	{/if}

	<h3 class={`text-md font-medium text-center my-2`}>
		{data.name.length > 10 ? data.name.slice(0, 10) + '...' : data.name}
	</h3>

	<p class={`text-sm font-semibold text-center my-2`}>
		{new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data.price)}
	</p>

	<!-- Use this one later -->
	<button
		use:ripple
		on:click={() => {
			setTimeout(() => {
				const product = data;
				if (image && image.filename) {
					product.images = [image.filename];
				}
				sessionStorage.setItem('product', JSON.stringify(product));

				sessionPage.set(`produk`);
			}, 250);
		}}
		type="button"
		class={`w-full text-white bg-french-violet rounded-lg text-sm px-4 py-2 transition duration-300 disabled:opacity-50`}
		disabled={!allLoaded}
	>
		Lihat Produk
	</button>
</div>
