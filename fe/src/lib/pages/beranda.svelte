<script lang="ts">
	// Images
	import bannerex from '$lib/assets/bannerex.svg';
	import ProductCard from '$lib/components/product_card.svelte';
	import type ProductData from '$lib/types/ProductData';
	import { productData } from '$lib/types/Sample';
	import { api } from '$lib/utils/api';
	import { onMount } from 'svelte';
	import InfiniteScroll from 'svelte-infinite-scroll';
	// import noimage from "$lib/assets/noimage.svg";

	// Dependencies
	import { ripple } from 'svelte-ripple-action';

	// Get products
	let products: ProductData[] | [] = [];
	$: productsLastPage = 1;
	$: productsPage = 1;
	async function getProducts() {
		if (productsPage > productsLastPage) return;
		try {
			const productsRes = await api.get(`/product/paginate?page=${productsPage}`);
			products = [...products, ...productsRes.data.data];
			productsPage++;
			productsLastPage = productsRes.data.last_page;
		} catch (err) {
			console.log(err);
			alert(err instanceof Error ? err.message : 'Terjadi kesalahan saat memuat produk.');
		}
	}
	onMount(async () => {
		await getProducts();
	});

	// Assert categories
	const categories: { value: string; label: string }[] = [
		{ value: 'all', label: 'Semua' },
		{ value: 'food', label: 'Makanan' },
		{ value: 'drink', label: 'Minuman' },
		{ value: 'male_fashion', label: 'Fashion Pria' },
		{ value: 'female_fashion', label: 'Fashion Wanita' },
		{ value: 'child_fashion', label: 'Fashion Anak' },
		{ value: 'furniture', label: 'Perabotan' }
	];

	// Reactive values
	$: currentCategory = 'all';
	$: filteredProducts = products.filter(
		(productData) => currentCategory === 'all' || currentCategory === productData.category
	);
	$: allLoaded = productsPage > productsLastPage;
</script>

<div class={`lg:container overflow-x-hidden flex flex-col w-screen h-fit p-4`}>
	<!-- Featured product -->
	<!-- <h2 class={`text-xl ms-2 font-bold`}>
      Produk Unggulan
  </h2>

  <br>

  <div class={`relative w-[90vw] h-[25vh] flex justify-center items-center`}>
    <img src={bannerex} class={`absolute inset-0 w-[90vw] h-[25vh] object-cover rounded-lg`} alt=""/>
    <div class={`relative text-center`}>
        <h1 class={`text-lg text-white mb-4`}>
          "Koleksi Baju Modern"
        </h1>
        <button use:ripple type="button" class={`text-black bg-white rounded-full text-sm px-4 py-2 transition duration-300`}>
            Lihat Selengkapnya →
        </button>
    </div>
  </div>

  <br> -->

	<!-- Desktop design -->
	<div class="hidden lg:grid grid-cols-12 lg:w-[85vw] h-[70vh]">
		<!-- Categories -->
		<div
			class={`hidden lg:flex flex-col items-center col-span-3 min-h-full p-4 bg-white border border-grey rounded-lg me-4`}
		>
			<p class="text-xl font-bold">Kategori</p>
			<br />
			{#each categories as category}
				<button
					use:ripple
					on:click={() => (currentCategory = category.value)}
					type="button"
					class={`my-1 ${currentCategory === category.value ? `text-white bg-french-violet` : `text-black bg-white border border-grey`} rounded-full text-sm px-4 py-2 w-full transition duration-300 text-nowrap min-w-fit `}
				>
					{category.label}
				</button>
			{/each}
		</div>

		<div class="flex flex-col col-span-12 lg:col-span-9 min-h-full">
			<!-- Categories (mobile) -->
			<div class={`flex items-center overflow-x-auto lg:hidden`}>
				{#each categories as category}
					<button
						use:ripple
						on:click={() => (currentCategory = category.value)}
						type="button"
						class={`me-2 ${currentCategory === category.value ? `text-white bg-french-violet` : `text-black bg-white`} rounded-full text-sm px-4 py-2 transition duration-300 text-nowrap min-w-fit`}
					>
						{category.label}
					</button>
				{/each}
			</div>

			<br class="lg:hidden" />

			<!-- Search bar -->
			<!-- TODO: write search endpoint on laravel backend -->
			<label for="search" class={`mb-2 text-sm font-medium text-gray-900 sr-only`}>Search</label>
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
			</div>

			<br />

			<!-- Products -->
			<ul class={`flex items-center flex-wrap max-h-full overflow-y-auto`}>
				{#each filteredProducts as productData}
					{#if currentCategory === 'all' || currentCategory === productData.category}
						<ProductCard data={productData} />
					{/if}
				{/each}
				<InfiniteScroll threshold={10} on:loadMore={getProducts} />
				{#if !allLoaded}
					<svg
						class="animate-spin h-16 w-16 ms-4"
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
				{:else}
					<p class="text-lg font-bold text-center">Semua produk sudah ditampilkan</p>
				{/if}
			</ul>
		</div>
	</div>

	<!-- Mobile design -->
	<div class="lg:hidden">
		<!-- Categories -->
		<div class={`flex items-center overflow-x-auto`}>
			{#each categories as category}
				<button
					use:ripple
					on:click={() => (currentCategory = category.value)}
					type="button"
					class={`me-2 ${currentCategory === category.value ? `text-white bg-french-violet` : `text-black bg-white`} rounded-full text-sm px-4 py-2 transition duration-300 text-nowrap min-w-fit`}
				>
					{category.label}
				</button>
			{/each}
		</div>

		<br />

		<!-- Search bar -->
		<!-- TODO: write search endpoint on laravel backend -->
		<label for="search" class={`mb-2 text-sm font-medium text-gray-900 sr-only`}>Search</label>
		<div class={`relative`}>
			<div class={`absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none`}>
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
		</div>

		<br />

		<!-- Products -->
		<ul class={`flex items-center flex-wrap max-h-full overflow-y-auto`}>
			{#each filteredProducts as productData}
				{#if currentCategory === 'all' || currentCategory === productData.category}
					<ProductCard data={productData} />
				{/if}
			{/each}
			<InfiniteScroll threshold={10} on:loadMore={getProducts} />
		</ul>
	</div>
</div>
