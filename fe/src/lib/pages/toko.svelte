<script lang="ts">
	import ProductCard from "$lib/components/product_card.svelte";
	import type ProductData from "$lib/types/ProductData";
	import { api } from "$lib/utils/api";
	import { sessionPage } from "$lib/utils/page";
	import { AxiosError } from "axios";
	import { onMount } from "svelte";

	// Get products
	let products: ProductData[] | [] = [];
	async function getProducts() {
		try {
			const productsRes = await api.get(`/product/owner/${JSON.parse(localStorage.getItem('userData') || '{}').id}`);
			products = [...products, ...productsRes.data];
		} catch (err) {
			console.log(err);
			switch (true) {
				case err instanceof AxiosError:
					alert(err.response?.data.message);
					break;
				case err instanceof Error:
					alert(err.message);
					break;
				default:
					alert('Terjadi kesalahan');
			}
		}
	}
	onMount(async () => {
		await getProducts();
	});
</script>

<header class="overflow-x-hidden w-screen h-fit p-4 lg:p-8 bg-white border border-grey flex justify-between items-center fixed top-0 z-50">
  <div class="flex justify-center items-center">
    <button on:click={() => sessionPage.set('profile')} type="button" class="me-2">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
      </svg>
    </button>
    <h1 class="text-md font-bold">Toko Anda</h1>
  </div>
</header>

<div class={`overflow-x-hidden flex lg:items-center flex-col w-screen p-4`}>
	<div class="grid grid-cols-12 lg:w-[90%] pt-16 lg:p-24">
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
			<div class={`flex justify-center items-center flex-wrap max-h-full overflow-y-auto`}>
				{#each products as data}
					<ProductCard data={data} />
				{/each}
			</div>
		</div>
	</div>
</div>
