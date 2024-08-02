<script lang="ts">
	import ProductCard from '$lib/components/product_card.svelte';
	import type ProductData from '$lib/types/ProductData';
	import { api } from '$lib/utils/api';
	import { sessionPage } from '$lib/utils/page';
	import { AxiosError } from 'axios';
	import { onMount } from 'svelte';
	import { ripple } from 'svelte-ripple-action';

	// Get products
	let products: ProductData[] | [] = [];
	let allProducts: ProductData[] | [] = [];
	async function getProducts() {
		try {
			const productsRes = await api.get(
				`/product/owner/${JSON.parse(localStorage.getItem('userData') || '{}').id}`
			);
			products = [...products, ...productsRes.data];
			allProducts = products;
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

	// Functions

	// Format currency on create product form
	let price: string = '';
	let actualPrice: number = 0;
	function formatPrice(e: Event) {
		const target = e.target as HTMLInputElement;
		let formattedVal = target.value.replace(/[^0-9]/g, '');
		if (formattedVal.length > 6) formattedVal = formattedVal.slice(0, 6); // max 6 digits
		const parsedVal = Number(formattedVal);
		actualPrice = parsedVal <= 999999 ? parsedVal : 999999;
		actualPrice = Number(formattedVal);
		price = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(
			Number(formattedVal)
		);
	}

	function openCreateProductModal() {
		const element = document.getElementById('create_product_modal') as HTMLDialogElement;
		element.showModal();
	}

	function handleImageUpload(e: Event) {
		const target = e.target as HTMLInputElement;
		const file = target.files?.[0];
		if (!file) return;
		const reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = (e) => {
			const element = document.getElementById('preview') as HTMLImageElement;
			element.src = e.target?.result as string;
		};
	}

	function createProduct(e: SubmitEvent) {
		e.preventDefault();

		const formData = new FormData(e.target as HTMLFormElement);
		formData.append('owner_id', JSON.parse(localStorage.getItem('userData') || '{}').id);
		formData.set('price', actualPrice.toFixed(0));

		// formData.set('price', parseInt(price.replace(/[^0-9.-]+/g, "")).toFixed(2));

		api
			.post('/product', formData)
			.then((res) => {
				alert('Produk berhasil dibuat');

				// Upload photo too
				const fileFormData = new FormData();
				const file = formData.get('image') as File;
				fileFormData.append('file', file);
				api
					.post(`/product/photo/${res.data.id}`, fileFormData)
					.then((res) => {
						alert('Foto berhasil diupload');

						location.reload(); // Fin
					})
					.catch((err) => {
						console.log(err);
					});
			})
			.catch((err) => {
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
			});
	}

	function search(e: Event) {
		const target = e.target as HTMLInputElement;
		if (!target.value) {
			products = allProducts;
		} else {
			const all = allProducts;
			products = all.filter((product) => product.name.includes(target.value)); // Make it more complex in the future
		}
	}
</script>

<dialog id="create_product_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<h3 class="text-lg font-bold">Buat Produk Baru</h3>

		<form on:submit={createProduct}>
			<div class="mb-4">
				<label for="name" class="text-sm font-bold mb-2">Nama Produk</label>
				<input
					name="name"
					type="text"
					id="name"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="(Wajib)"
					required
				/>
			</div>
			<div class="mb-4">
				<label for="description" class="text-sm font-bold mb-2">Deskripsi</label>
				<textarea
					name="description"
					id="description"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="(Opsional)"
				/>
			</div>
			<div class="mb-4">
				<label for="price" class="text-sm font-bold mb-2">Harga</label>
				<input
					name="price"
					bind:value={price}
					on:change={formatPrice}
					type="text"
					id="price"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="(Wajib)"
				/>
			</div>
			<div class="mb-4">
				<label for="category" class="text-sm font-bold mb-2">Kategori</label>
				<select
					name="category"
					id="category"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					required
				>
					<option selected value="food">Makanan</option>
					<option value="drink">Minuman</option>
					<option value="female_fashion">Fashion Wanita</option>
					<option value="male_fashion">Fashion Pria</option>
					<option value="child_fashion">Fashion Anak</option>
					<option value="furniture">Perabotan</option>
				</select>
			</div>
			<div class="mb-4">
				<label for="image" class="text-sm font-bold mb-2">Gambar</label>
				<img
					id="preview"
					class="size-32 my-2"
					src="https://placehold.co/600x400?text=Belum\nAda\nGambar"
					alt="Preview"
				/>
				<input
					on:change={handleImageUpload}
					name="image"
					type="file"
					id="image"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="(Opsional)"
				/>
			</div>
			<button
				type="submit"
				use:ripple
				class="bg-french-violet text-white font-bold
				py-2 px-4 rounded focus:outline-none focus:shadow-outline">Unggah Produk</button
			>
		</form>

		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
		</form>
	</div>
</dialog>

<header
	class="overflow-x-hidden w-screen h-fit p-4 lg:p-8 bg-white border border-grey flex justify-between items-center fixed top-0 z-50"
>
	<div class="flex justify-center items-center">
		<button on:click={() => sessionPage.set('profile')} type="button" class="me-2">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				width="16"
				height="16"
				fill="currentColor"
				class="bi bi-arrow-left"
				viewBox="0 0 16 16"
			>
				<path
					fill-rule="evenodd"
					d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"
				/>
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
					on:change={search}
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

			<div class="flex w-full items-center justify-center space-x-4 px-2">
				<button
					on:click={openCreateProductModal}
					use:ripple
					class="bg-french-violet w-full text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
					>Buat Produk Baru</button
				>
			</div>

			<br />

			<!-- Products -->
			<div class={`flex justify-center items-center flex-wrap max-h-full overflow-y-auto`}>
				{#if products.length < 1}
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
					{#each products as data}
						<ProductCard {data} />
					{/each}
				{/if}
			</div>
		</div>
	</div>
</div>
