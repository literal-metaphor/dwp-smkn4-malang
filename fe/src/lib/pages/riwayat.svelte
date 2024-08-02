<script lang="ts">
	// Images
	import transactionImg from '$lib/assets/transaction.svg';
	import userPlaceholder from '$lib/assets/profile.svg';
	import noimage from '$lib/assets/noimage.svg';

	// Depedencies
	import type TransactionData from '$lib/types/TransactionData';
	import { ripple } from 'svelte-ripple-action';
	import { onMount } from 'svelte';
	import { api, store } from '$lib/utils/api';
	import { transactionData } from '$lib/types/Sample';
	import { DefaultMarker, MapLibre } from 'svelte-maplibre';
	import { AxiosError } from 'axios';

	$: transactions = [] as TransactionData[];
	onMount(async () => {
		const res = await api.get(
			`/transaction/user/${JSON.parse(localStorage.getItem('userData') || '{}').id}`
		);
		transactions = res.data;
	});
	$: filteredTransactions = transactions;

	$: currentTransactionData = transactionData;

	function openTransactionModal(transactionData: TransactionData) {
		currentTransactionData = transactionData;
		const element = document.getElementById('transaction_modal') as HTMLDialogElement;
		element.showModal();
	}

	function filterByDate(e: Event) {
		e.preventDefault();

		const target = e.target as HTMLInputElement;
		filteredTransactions = transactions.filter((transaction) =>
			transaction.created_at.includes(target.value)
		);
	}

	async function confirmDelivery(id: string) {
		if (confirm('Apakah Anda yakin ingin mengkonfirmasi pengiriman ini?')) {
			const formData = new FormData();
			formData.append('_method', 'PUT');
			try {
				const res = api.post(`/transaction/confirm/${id}`, formData, {
					headers: {
						Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}`
					}
				});
				location.reload();
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
	}

	const methods: { [key: string]: string } = {
		cod: 'COD'
	};
</script>

<dialog id="transaction_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<h3 class="text-lg font-bold">Detail Transaksi</h3>
		<h5 class="text-md font-semibold">Lokasi Pengiriman</h5>
		<MapLibre
			center={[currentTransactionData.longitude, currentTransactionData.latitude]}
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
			<DefaultMarker lngLat={[currentTransactionData.longitude, currentTransactionData.latitude]} />
		</MapLibre>

		<br />
		<button
			use:ripple
			type="button"
			on:click={() =>
				setTimeout(
					() =>
						open(
							`https://www.google.com/maps/search/${currentTransactionData.latitude},${currentTransactionData.longitude}/@${currentTransactionData.latitude},${currentTransactionData.longitude}m/data=!3m1!1e3?entry=ttu`
						),
					250
				)}
			class="flex justify-center items-center bg-white shadow-lg p-4 rounded-lg w-full"
		>
			<svg
				xmlns="http://www.w3.org/2000/svg"
				height="24"
				viewBox="14.32 4.87961494 37.85626587 52.79038506"
				width="24"
				><path
					d="m37.34 7.82c-1.68-.53-3.48-.82-5.34-.82-5.43 0-10.29 2.45-13.54 6.31l8.35 7.02z"
					fill="#1a73e8"
				/><path
					d="m18.46 13.31a17.615 17.615 0 0 0 -4.14 11.36c0 3.32.66 6.02 1.75 8.43l10.74-12.77z"
					fill="#ea4335"
				/><path
					d="m32 17.92a6.764 6.764 0 0 1 5.16 11.13l10.52-12.51a17.684 17.684 0 0 0 -10.35-8.71l-10.51 12.51a6.74 6.74 0 0 1 5.18-2.42"
					fill="#4285f4"
				/><path
					d="m32 31.44c-3.73 0-6.76-3.03-6.76-6.76a6.7 6.7 0 0 1 1.58-4.34l-10.75 12.77c1.84 4.07 4.89 7.34 8.03 11.46l13.06-15.52a6.752 6.752 0 0 1 -5.16 2.39"
					fill="#fbbc04"
				/><path
					d="m36.9 48.8c5.9-9.22 12.77-13.41 12.77-24.13 0-2.94-.72-5.71-1.99-8.15l-23.57 28.05c1 1.31 2.01 2.7 2.99 4.24 3.58 5.54 2.59 8.86 4.9 8.86s1.32-3.33 4.9-8.87"
					fill="#34a853"
				/></svg
			>
			<span class="ms-2">Lihat di Google Maps</span>
		</button>
		<br />
		<!-- Address Criteria -->
		<div class="">{@html currentTransactionData.address_criteria}</div>

		{#each currentTransactionData.items as item}
			<div class="bg-white p-4 shadow-lg rounded-lg my-4 relative">
				<!-- Owner/Shop Box -->
				<p class="text-lg font-semibold mb-2">Penjual:</p>
				<div class="flex items-center mb-4">
					<img
						src={item.product?.owner?.avatar?.filename
							? store + item.product?.owner?.avatar?.filename
							: userPlaceholder}
						alt="Profile"
						class="size-10 rounded-full me-2"
					/>
					<span class="font-semibold text-blue">@{item.product?.owner?.username}</span>
				</div>

				<!-- Product Details -->
				<div class="flex flex-col lg:flex-row">
					<!-- Product Image -->
					<img
						src={item.product?.images && item.product?.images?.length > 0
							? store + item.product?.images[0]
							: noimage}
						alt="Product"
						class="size-32 object-cover mr-4 border border-grey rounded"
					/>

					<!-- Product Details -->
					<div class="flex-1">
						<h2 class="text-lg font-semibold">{item.product?.name}</h2>
						<!-- <p class="">{new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(Number(item.product?.price))}</p> -->

						<!-- Quantity and Total Price -->
						<div class="mt-4">
							<div class="font-bold">
								Jumlah:
								<span class="font-normal">{item.quantity}</span>
							</div>
							<div class="font-bold">
								Total:
								<span class="font-normal"
									>{new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(
										Number(item.product?.price) * item.quantity
									)}</span
								>
							</div>
						</div>

						<br />

						<!-- Delivery Status -->
						<div
							class={`flex items-center text-${item.status === 'delivered' ? 'success' : 'red'}`}
						>
							{#if item.status === 'delivered'}
								<svg
									xmlns="http://www.w3.org/2000/svg"
									width="16"
									height="16"
									fill="currentColor"
									class="bi bi-check-circle-fill"
									viewBox="0 0 16 16"
								>
									<path
										d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
									/>
								</svg>
								<div class="ms-2 text-success">Paket Terkirim</div>
							{:else}
								<svg
									xmlns="http://www.w3.org/2000/svg"
									width="16"
									height="16"
									fill="currentColor"
									class="bi bi-clock"
									viewBox="0 0 16 16"
								>
									<path
										d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"
									/>
									<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
								</svg>
								<div class="ms-2 text-red">Dalam Perjalanan</div>
							{/if}
						</div>
						{#if item.product?.owner?.id === JSON.parse(localStorage.getItem('userData') || '{}').id}
							<br />

							<!-- Confirm Delivery for Shop -->
							{#if item.status === 'pending' && !item.delivery_date}
								<button
									on:click={async () => {
										await confirmDelivery(item.id);
									}}
									type="button"
									use:ripple
									class="bg-french-violet text-white font-bold
								py-2 px-4 rounded focus:outline-none focus:shadow-outline">Konfirmasi Barang Sudah Terkirim</button
								>
							{/if}
						{/if}
					</div>
				</div>
			</div>
		{/each}

		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
		</form>
	</div>
</dialog>

<div class={`lg:container overflow-x-hidden flex lg:items-center flex-col w-screen h-fit p-4`}>
	<h2 class={`text-xl ms-2 mb-2 font-bold`}>Transaksi Terakhir</h2>

	<div class="flex items-center">
		<span class="me-2">Filter pada tanggal:</span>
		<input on:change={filterByDate} type="date" name="date_search" id="date_search" />
	</div>

	<div class="flex lg:items-center w-full lg:w-[32%] h-full flex-col overflow-y-auto">
		{#each filteredTransactions as data}
			<button
				on:click={() => openTransactionModal(data)}
				use:ripple
				class={`flex justify-between items-center w-full h-fit px-4 py-4 bg-white my-2 border border-grey rounded-lg`}
			>
				<div class="flex items-center">
					<img src={transactionImg} alt="Transaction" class={`size-8 rounded-full me-3`} />
					<div class="flex flex-col items-start">
						<p class="">Pembayaran / {methods[data.method]}</p>
						{#if data.items.some((item) => item.product?.owner?.id === JSON.parse(localStorage.getItem('userData') || '{}').id)}
							<p class="text-sm text-red">Anda penjual di transaksi ini!</p>
						{/if}
					</div>
				</div>
				<div class="flex flex-col items-end">
					<p class="text-sm">
						- {new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(
							data.items.reduce((a, b) => {
								return b.product ? a + b.product.price * b.quantity : a;
							}, 0)
						)}
					</p>
					<p class="text-sm text-french-violet">
						{new Date(data.created_at).toISOString().split('T')[0]}
					</p>
				</div>
			</button>
		{/each}
	</div>
</div>

<style>
	:global(.map) {
		height: 50vh;
	}
</style>
