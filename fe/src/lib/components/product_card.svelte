<script lang="ts">
	import type { ProductData } from "$lib/types/ProductData";
  import { sessionPage } from "$lib/utils/page";

  import Carousel from "$lib/components/carousel.svelte";
  import noimage from "$lib/assets/noimage.svg";

  export let data: ProductData;
</script>

<div class={`p-4 border border-grey rounded-lg mx-2`}>
  {#if data.images && data.images.length > 0}
  <Carousel
    items={
      data.images.map((image) => {
        return { img: image, alt: "Product" };
      })
    }
  />
  {:else}
    <img src={noimage} alt="Product" class={`w-[30vw] h-auto rounded-lg`}>
  {/if}

  <br>

  <h3 class={`text-md font-medium`}>
      {data.name}
  </h3>
  <p class={`text-sm font-medium`}>
      {data.price}
  </p>

  <br>

  <!-- () => { sessionPage.set(`produk?id${id}`) } --> <!-- Use this one later -->
  <button on:click={() => sessionPage.set("produk")} type="button" class={`me-2 w-full text-white bg-french-violet rounded-lg text-sm px-4 py-2 focus:scale-95 transition duration-300`}>
      Lihat Produk
  </button>
</div>