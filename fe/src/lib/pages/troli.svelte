<script lang="ts">
  // Depedencies
	import { sessionPage } from "$lib/utils/page";
  import cod from "$lib/assets/transaction.svg";
	import { ripple } from "svelte-ripple-action";
	import { store } from "$lib/utils/api";
  import userPlaceholder from "$lib/assets/profile.svg";
  import noimage from "$lib/assets/noimage.svg";
	import type TrolleyItem from "$lib/types/TrolleyItem";
  import { MapEvents, MapLibre, DefaultMarker } from "svelte-maplibre";
	import type { MapMouseEvent } from "maplibre-gl";
  import { onMount } from 'svelte';

  $: items = JSON.parse(sessionStorage.getItem('cart') || '[]') as TrolleyItem[];

  function removeItem(item: TrolleyItem) {
    const index = items.findIndex((i) => i === item);
    if (index > -1) {
      const newItems = items;
      newItems.splice(index, 1);
      items = newItems;
      sessionStorage.setItem('cart', JSON.stringify(newItems));
    } else {
      alert("Item tidak ditemukan");
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
      alert("Item tidak ditemukan");
    }
  }

  // Finalize delivery
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
    })
  })
  function setDeliveryLocation(e: CustomEvent<MapMouseEvent>) {
    console.log(`https://www.google.com/maps/search/${e.detail.lngLat.lat},${e.detail.lngLat.lng}/@${e.detail.lngLat.lat},${e.detail.lngLat.lng}m/data=!3m1!1e3?entry=ttu`);
    currentLng = e.detail.lngLat.lng;
    currentLat = e.detail.lngLat.lat;
  }

</script>

<style>
  :global(.map) {
    height: 50vh;
  }
</style>

<dialog id="cod_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<h3 class="text-lg font-bold">Cash on Delivery</h3>
    <MapLibre
      center={[currentLng, currentLat]}
      zoom={7}
      class="map"
      standardControls
      style={
        {
          version: 8,
          sources: {
            osm: {
              type: 'raster',
              tiles: ['https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'],
              tileSize: 256,
              attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }
          },
          layers: [
            {
              id: 'osm',
              type: 'raster',
              source: 'osm'
            }
          ]
        }
      }
    >
      <MapEvents on:click={setDeliveryLocation} />
      <DefaultMarker lngLat={[currentLng, currentLat]} />
  </MapLibre>

		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
		</form>
	</div>
</dialog>

<header class="overflow-x-hidden w-screen h-fit p-4 lg:p-8 bg-white border border-grey flex justify-between items-center fixed top-0 z-50">
  <div class="flex justify-center items-center">
    <button on:click={() => sessionPage.set('profile')} type="button" class="me-2">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
      </svg>
    </button>
    <h1 class="text-md font-bold">Troli Belanja</h1>
  </div>
</header>

<div class="pt-32 p-6">
  <!-- Payment Options Box -->
  <div class="bg-white p-4 shadow-lg mb-6 rounded-lg">
      <h2 class="text-lg font-semibold mb-4">Metode Pembayaran</h2>
      <button on:click={openCodModal} use:ripple type="button" class="p-4 rounded-lg border border-french-violet flex items-center">
        <img src={cod} alt="Cash on delivery" class="size-8">
        <h3 class="font-semibold ms-2">Cash on delivery</h3>
      </button>
      <p class="mt-8 text-sm text-gray-500">Metode pembayaran lain akan segera datang...</p>
  </div>

  <!-- Product Box -->
   {#each items as item}
    <div class="bg-white p-4 shadow-lg rounded-lg my-4 relative">
      <button on:click={() => removeItem(item)} class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>

      <!-- Owner/Shop Box -->
      <p class="text-lg font-semibold mb-2">Penjual:</p>
      <div class="flex items-center mb-4">
          <img src={item.owner.avatar ? store + item.owner.avatar.filename : userPlaceholder} alt="Profile" class="size-10 rounded-full me-2">
          <span class="font-semibold text-blue">@{item.owner.username}</span>
      </div>

      <!-- Product Details -->
      <div class="flex flex-col lg:flex-row">
          <!-- Product Image -->
          <img src={item.product.images && item.product.images.length > 0 ? store + item.product.images[0] : noimage} alt="Product" class="size-32 object-cover mr-4 border border-grey rounded">

          <!-- Product Details -->
          <div class="flex-1">
              <h2 class="text-lg font-semibold">{item.product.name}</h2>
              <p class="">{new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.product.price)}</p>

              <!-- Quantity and Total Price -->
              <div class="flex items-center mt-4">
                  <div class="flex items-center me-2">
                    <button use:ripple on:click={() => {
                      if (item.quantity > 1) {
                        changeQuantity(item, item.quantity - 1);
                      }
                    }} class="bg-french-violet text-white font-bold py-2 px-4 rounded-l">-</button>
                    <span class="mx-2">{item.quantity}</span>
                    <button use:ripple on:click={() => {
                      changeQuantity(item, item.quantity + 1);
                    }} class="bg-french-violet text-white font-bold py-2 px-4 rounded-r">+</button>
                  </div>
                  <span>Total: {new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.product.price * item.quantity)}</span>
              </div>
          </div>
      </div>
    </div>
   {/each}
</div>

