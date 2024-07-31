<script lang="ts">
	import ProductCard from '$lib/components/product_card.svelte';
	import type ProductData from '$lib/types/ProductData';
	import { productData } from '$lib/types/Sample';
	import type { WishlistData } from '$lib/types/WishlistData';
	import { api } from '$lib/utils/api';
	import { onMount } from 'svelte';
	// import koleksi from '$lib/assets/koleksi.svg';

	// import { userData } from '$lib/types/Sample';

	$: message = "Memuat...";

	let products: ProductData[] | [] = [];
	onMount(async () => {
		const res = await api.get(`/product/wishlist/${JSON.parse(localStorage.getItem('userData') || '{}').id}`);
		const wishlists = res.data as WishlistData[];

		if (wishlists.length < 1) {
			message = "Tidak ada produk favorit";
		}

		const promises = wishlists.map(async (product) => {
				const res = await api.get(`/product/${product.product_id}`);
				return res.data;
		});

		products = await Promise.all(promises);
	})
</script>

<div class={`overflow-x-hidden flex flex-col w-screen h-fit p-4`}>
	<!-- Desktop design -->
	<div class="grid grid-cols-12 lg:w-[85%]">
		<!-- Aside -->
		<!-- <aside class={`hidden lg:flex flex-col items-center col-span-3 min-h-full p-4 bg-white border border-grey rounded-lg me-4`}>
      {#if productDatas[0]?.images?.[0]}
        <img src={productDatas[0].images[0]} alt="Product" class="size-48">
      {:else}
        <img src={koleksi} alt="Product" class="size-48 p-8">
      {/if}
      <br>
      <p class="text-xl font-bold">Koleksi</p>
      <p class="text-sm opacity-50">{userData.username} | {productDatas.length} Produk</p>
    </aside> -->

		<div class="flex flex-col col-span-12 //lg:col-span-9 min-h-full">
			<!-- Search bar -->
			<!-- TODO: write search endpoint on laravel backend -->
			<!-- <label for="search" class={`mb-2 text-sm font-medium text-gray-900 sr-only`}>Search</label>
			<div class={`relative p-1`}>
				<div class={`absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none`}>
					<svg
						class="w-4 h-4 text-gray-500 dark:text-gray-400"
						aria-hidden="true"
						xmlns="http://www.w3.org/2000/svg"
						fill="none"
						viewBox="0 0 20 20"
					>
						<path
							stroke="currentColor"
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
						/>
					</svg>
				</div>
				<input
					type="text"
					id="search"
					name="search"
					class={`block w-full p-4 ps-10 text-sm text-black rounded-lg bg-white border border-grey focus:ring-1 focus:ring-french-violet focus:outline-none transition duration-300`}
					placeholder="Cari merek atau produk"
					autocomplete="off"
					required
				/>
			</div> -->

			<br />

			<!-- Products -->
			<div class={`flex justify-center items-center flex-wrap max-h-full overflow-y-auto`}>
				{#if products.length > 0}
					{#each products as productData}
						<ProductCard data={productData} />
					{/each}
				{:else}
					<p class="text-lg font-bold text-center">{message}</p>
				{/if}
			</div>
		</div>
	</div>
</div>
