<script lang="ts">
	// Depedencies
	import { sessionPage } from '$lib/utils/page';
	import cod from '$lib/assets/transaction.svg';
	import { ripple } from 'svelte-ripple-action';
	import { api, store } from '$lib/utils/api';
	import userPlaceholder from '$lib/assets/profile.svg';
	import noimage from '$lib/assets/noimage.svg';
	import type TrolleyItem from '$lib/types/TrolleyItem';
	import { MapEvents, MapLibre, DefaultMarker } from 'svelte-maplibre';
	import type { MapMouseEvent } from 'maplibre-gl';
	import { onMount } from 'svelte';
	import { AxiosError } from 'axios';

	// Images
	import transaction_success from '$lib/assets/transaction_success.svg';

	$: items = JSON.parse(sessionStorage.getItem('cart') || '[]') as TrolleyItem[];

	function removeItem(item: TrolleyItem) {
		const index = items.findIndex((i) => i === item);
		if (index > -1) {
			const newItems = items;
			newItems.splice(index, 1);
			items = newItems;
			sessionStorage.setItem('cart', JSON.stringify(newItems));
		} else {
			alert('Item tidak ditemukan');
		}
	}
	function changeQuantity(item: TrolleyItem, quantity: number) {
		const index = items.findIndex((i) => i === item);
		if (index > -1) {
			const newItems = items;
			newItems[index].quantity = quantity;
			items = newItems;
			sessionStorage.setItem('cart', JSON.stringify(newItems));
		} else {
			alert('Item tidak ditemukan');
		}
	}

	// Finalize delivery: COD Method
	function openCodModal() {
		const element = document.getElementById('cod_modal') as HTMLDialogElement;
		element.showModal();
	}
	$: currentLat = 0;
	$: currentLng = 0;
	onMount(() => {
		navigator.geolocation.getCurrentPosition((position) => {
			currentLat = position.coords.latitude;
			currentLng = position.coords.longitude;
		});
	});
	function setDeliveryLocation(e: CustomEvent<MapMouseEvent>) {
		currentLng = e.detail.lngLat.lng;
		currentLat = e.detail.lngLat.lat;
	}
	async function finalizeCodDelivery(e: SubmitEvent) {
		e.preventDefault();

		const formInput = new FormData(e.target as HTMLFormElement);
		const customer_name = formInput.get('customer_name') as string;
		const area = formInput.get('area') as string;
		const district = formInput.get('district') as string;
		const city = formInput.get('city') as string;
		const address_criteria = formInput.get('address_criteria') as string;

		const customer_id = JSON.parse(localStorage.getItem('userData') || '{}').id;
		const full_criteria = `Nama : ${customer_name} <br/> Lokasi: ${area} <br/> Kecamatan: ${district} <br/> Kabupaten  Kota: ${city} <br/> Informasi Tambahan: ${address_criteria}`;

		let itemsArr = [];
		for (const item of items) {
			itemsArr.push({
				product_id: item.product.id,
				quantity: item.quantity
			});
		}

		const data = {
			customer_id: customer_id,
			method: 'cod',
			longitude: currentLng,
			latitude: currentLat,
			address_criteria: full_criteria,
			items: itemsArr
		};

		try {
			await api.post(`/transaction`, data, {
				headers: {
					Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}`
				}
			});

			const element = document.getElementById('cod_modal') as HTMLDialogElement;
			element.close();

			sessionStorage.removeItem('cart');

			const successModal = document.getElementById('success_modal') as HTMLDialogElement;
			successModal.showModal();
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
</script>

<dialog id="cod_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<h3 class="text-lg font-bold">Cash on Delivery</h3>

		<br />

		<MapLibre
			center={[currentLng, currentLat]}
			zoom={7}
			class="map"
			standardControls
			style={{
				version: 8,
				sources: {
					osm: {
						type: 'raster',
						tiles: ['https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'],
						tileSize: 256,
						attribution:
							'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
					}
				},
				layers: [
					{
						id: 'osm',
						type: 'raster',
						source: 'osm'
					}
				]
			}}
		>
			<MapEvents on:click={setDeliveryLocation} />
			<DefaultMarker lngLat={[currentLng, currentLat]} />
		</MapLibre>
		<p>Koordinat (LatLng): {currentLat}, {currentLng}</p>

		<form on:submit={finalizeCodDelivery}>
			{#each [{ name: 'customer_name', label: 'Nama Lengkap Anda', placeholder: 'Nama Lengkap Anda' }, { name: 'area', label: 'Nama Perumahan / Jalan / Desa', placeholder: 'Nama Perumahan / Jalan / Desa' }, { name: 'district', label: 'Kecamatan', placeholder: 'Kecamatan' }, { name: 'city', label: 'Kabupaten / Kota', placeholder: 'Kabupaten / Kota' }, { name: 'address_criteria', label: 'Petunjuk Lokasi', placeholder: 'Jelaskan kriteria lokasi Anda untuk mempermudah kurir dalam mencari lokasi pengiriman... (contoh: "dekat kantor kecamatan, jalan raya, rumah dengan pohon jambu, dll.")' }] as field}
				<div class="mb-4">
					<label for={field.name} class="text-sm font-bold mb-2">{field.label}</label>
					{#if field.name === 'address_criteria'}
						<textarea
							name={field.name}
							id={field.name}
							class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
							placeholder={field.placeholder}
							required
						/>
					{:else}
						<input
							type="text"
							name={field.name}
							id={field.name}
							class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
							placeholder={field.placeholder}
							required
						/>
					{/if}
				</div>
			{/each}

			<button
				type="submit"
				use:ripple
				class="bg-french-violet text-white font-bold
      py-2 px-4 rounded focus:outline-none focus:shadow-outline">Finalisasi Transaksi</button
			>
		</form>

		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
		</form>
	</div>
</dialog>

<dialog id="success_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<div class="mx-auto flex flex-col justify-center items-center">
			<h3 class="text-lg font-bold">Pembelian Berhasil</h3>

			<img src={transaction_success} alt="Transaction Success" class="size-64 my-2" />

			<p class="text-sm text-grey">Barang Anda sedang dalam perjalanan</p>
		</div>

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
		<h1 class="text-md font-bold">Troli Belanja</h1>
	</div>
</header>

<div class="pt-32 p-6">
	<!-- Payment Options Box -->
	<div class="bg-white p-4 shadow-lg mb-6 rounded-lg">
		<h2 class="text-lg font-semibold mb-4">Metode Pembayaran</h2>
		<button
			disabled={items.length < 1}
			on:click={openCodModal}
			use:ripple
			type="button"
			class={`p-4 rounded-lg border border-french-violet flex items-center ${items.length < 1 && 'cursor-not-allowed opacity-50'}`}
		>
			<img src={cod} alt="Cash on delivery" class="size-8" />
			<h3 class="font-semibold ms-2">Cash on delivery</h3>
		</button>
		<p class="mt-8 text-sm text-gray-500">Metode pembayaran lain akan segera datang...</p>
	</div>

	<!-- Product Box -->
	{#each items as item}
		<div class="bg-white p-4 shadow-lg rounded-lg my-4 relative">
			<button
				on:click={() => removeItem(item)}
				class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button
			>

			<!-- Owner/Shop Box -->
			<p class="text-lg font-semibold mb-2">Penjual:</p>
			<div class="flex items-center mb-4">
				<img
					src={item.owner.avatar ? store + item.owner.avatar.filename : userPlaceholder}
					alt="Profile"
					class="size-10 rounded-full me-2"
				/>
				<span class="font-semibold text-blue">@{item.owner.username}</span>
			</div>

			<!-- Product Details -->
			<div class="flex flex-col lg:flex-row">
				<!-- Product Image -->
				<img
					src={item.product.images && item.product.images.length > 0
						? store + item.product.images[0]
						: noimage}
					alt="Product"
					class="size-32 object-cover mr-4 border border-grey rounded"
				/>

				<!-- Product Details -->
				<div class="flex-1">
					<h2 class="text-lg font-semibold">{item.product.name}</h2>
					<p class="">
						{new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(
							item.product.price
						)}
					</p>

					<!-- Quantity and Total Price -->
					<div class="flex items-center mt-4">
						<div class="flex items-center me-2">
							<button
								use:ripple
								on:click={() => {
									if (item.quantity > 1) {
										changeQuantity(item, item.quantity - 1);
									}
								}}
								class="bg-french-violet text-white font-bold py-2 px-4 rounded-l">-</button
							>
							<span class="mx-2">{item.quantity}</span>
							<button
								use:ripple
								on:click={() => {
									changeQuantity(item, item.quantity + 1);
								}}
								class="bg-french-violet text-white font-bold py-2 px-4 rounded-r">+</button
							>
						</div>
						<span
							>Total: {new Intl.NumberFormat('id-ID', {
								style: 'currency',
								currency: 'IDR'
							}).format(item.product.price * item.quantity)}</span
						>
					</div>
				</div>
			</div>
		</div>
	{/each}
</div>

<style>
	:global(.map) {
		height: 50vh;
	}
</style>
