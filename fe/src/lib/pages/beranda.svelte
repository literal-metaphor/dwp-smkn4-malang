<script lang="ts">
  // import type { PageData } from './$types';

  // Images
  import bannerex from "$lib/assets/bannerex.svg";
  import ProductCard from '$lib/components/product_card.svelte';
	import type ProductData from '$lib/types/ProductData';
	import { productData } from "$lib/types/Sample";
  // import noimage from "$lib/assets/noimage.svg";

  import { ripple } from 'svelte-ripple-action';

  const productDatas: ProductData[] = [productData, productData, productData, productData, productData];

  const categories: { value: string, label: string }[] = [
    { value: "all", label: "Semua" },
    { value: "food", label: "Makanan" },
    { value: "drink", label: "Minuman" },
    { value: "male_fashion", label: "Fashion Pria" },
    { value: "female_fashion", label: "Fashion Wanita" },
    { value: "child_fashion", label: "Fashion Anak" },
    { value: "furniture", label: "Perabotan" }
  ]

  $: currentCategory = "Semua";

  // export let data: PageData;
</script>

<div class={`container overflow-x-hidden flex justify-content flex-col w-screen h-fit p-4`}>

  <!-- Featured product -->
  <h2 class={`text-lg font-bold`}>
      Produk Unggulan
  </h2>

  <br>

  <div class={`relative w-[90vw] h-[25vh] flex justify-center items-center`}>
    <img src={bannerex} class={`absolute inset-0 w-[90vw] h-[25vh] object-cover rounded-lg`} alt=""/>
    <div class={`relative text-center`}>
        <h1 class={`text-lg text-white mb-4`}>
          "Koleksi Baju Modern"
        </h1>
        <button use:ripple type="button" class={`text-black bg-white active:scale-90 rounded-full text-sm px-4 py-2 transition duration-300`}>
            Lihat Selengkapnya →
        </button>
    </div>
  </div>

  <br>

  <!-- Categories -->
  <div class={`flex items-center overflow-x-auto`}>
    {#each categories as category}
      <button use:ripple on:click={() => currentCategory = category.value} type="button" class={`me-2 ${currentCategory === category.value ? `text-white bg-french-violet` : `text-black bg-white`} rounded-full text-sm px-4 py-2 transition duration-300 text-nowrap min-w-fit active:scale-90`}>
        {category.label}
      </button>
    {/each}
   </div>

   <br>

   <!-- Search bar -->
   <form on:submit={(e)=>e.preventDefault()}>
      <label for="search" class={`mb-2 text-sm font-medium text-gray-900 sr-only`}>Search</label>
      <div class={`relative`}>
          <div class={`absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none`}>
              <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
              </svg>
          </div>
          <input type="text" id="search" name="search" class={`block w-full p-4 ps-10 text-sm text-black rounded-lg bg-white border border-grey focus:ring-1 focus:ring-french-violet focus:outline-none transition duration-300`} placeholder="Cari merek atau produk" autocomplete="off" required/>
      </div>
  </form>

  <br>

  <!-- Products -->
  <div class={`flex items-center flex-wrap`}>
    {#each productDatas as productData}
      {#if currentCategory === "all" || currentCategory === productData.category}
        <ProductCard data={productData} />
      {/if}
    {/each}
  </div>

</div>