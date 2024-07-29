<script lang="ts">
	export let data: ProductData;

	import type ProductData from '$lib/types/ProductData';
	import { sessionPage } from '$lib/utils/page';

	import Carousel from '$lib/components/carousel.svelte';
	import noimage from '$lib/assets/noimage.svg';

	import { ripple } from 'svelte-ripple-action';
	import type { FileData } from '$lib/types/FileData';
	import { onMount } from 'svelte';
	import { api, store } from '$lib/utils/api';

	let image: FileData = {} as FileData;

	onMount(async () => {
		const res = await api.get(`/product/photo/${data.id}`);
		image = res.data[0];
	});
</script>

<div class={`w-[40vw] lg:w-[12vw] p-2 border border-grey rounded-lg m-2`}>
	{#if image}
		<img src={store + image.filename} alt="Product" class={`w-full h-48 rounded-lg`} />
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
		on:click={() => { sessionPage.set(`produk`) }}
		type="button"
		class={`w-full text-white bg-french-violet rounded-lg text-sm px-4 py-2 transition duration-300`}
	>
		Lihat Produk
	</button>
</div>
