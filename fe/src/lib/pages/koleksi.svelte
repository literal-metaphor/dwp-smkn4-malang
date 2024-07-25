<script lang="ts">
  import ProductCard from "$lib/components/product_card.svelte";
  import { productData } from "$lib/types/Sample";
</script>

<div class={`container overflow-x-hidden flex justify-content flex-col w-screen h-fit p-4`}>

  <h2 class={`text-xl font-bold ms-2`}>
      Koleksi
  </h2>

  <div class={`flex items-center flex-wrap`}>
    <ProductCard data={productData} />
    <ProductCard data={productData} />
    <ProductCard data={productData} />
    <ProductCard data={productData} />
    <ProductCard data={productData} />
  </div>

</div>