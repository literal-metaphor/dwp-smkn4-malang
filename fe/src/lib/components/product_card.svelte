<script lang="ts">
	import type ProductData from "$lib/types/ProductData";
  import { sessionPage } from "$lib/utils/page";

  import Carousel from "$lib/components/carousel.svelte";
  import noimage from "$lib/assets/noimage.svg";

  import { ripple } from 'svelte-ripple-action';

  export let data: ProductData;
</script>

<div class={`w-[40vw] p-2 border border-grey rounded-lg m-2`}>
  {#if data.images && data.images.length > 0}
    <Carousel
      items={
        data.images.map((image) => {
          return { img: image, alt: "Product" };
        })
      }
    />
  {:else}
    <img src={noimage} alt="Product" class={`w-full h-auto rounded-lg`}>
  {/if}

  <h3 class={`text-md font-medium text-center my-2`}>
      {data.name.length > 20 ? data.name.slice(0, 20) + "..." : data.name}
  </h3>

  <p class={`text-sm font-semibold text-center my-2`}>
      {new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data.price)}
  </p>

  <!-- () => { sessionPage.set(`produk?id${id}`) } --> <!-- Use this one later -->
  <button use:ripple type="button" class={`me-2 w-full text-white bg-french-violet rounded-lg text-sm px-4 py-2 active:scale-90 transition duration-300`}>
      Lihat Produk
  </button>
</div>