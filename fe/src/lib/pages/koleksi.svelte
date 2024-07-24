<script lang="ts">
  import ProductCard from "$lib/components/product_card.svelte";
  import type { ProductData } from "$lib/types/ProductData";

  import noimage from "$lib/assets/noimage.svg";

  const productData: ProductData = {
    id: "1",
    shop_id: "1",
    name: "T-Shirt",
    description: "T-Shirt",
    price: 10000.00,
    category: "male_fashion",
    images: [noimage, noimage, noimage, noimage, noimage],
  }
</script>

<div class={`container overflow-x-hidden flex justify-content flex-col w-screen h-fit p-4`}>

  <div class={`flex items-center flex-wrap`}>
    <ProductCard data={productData} />
    <ProductCard data={productData} />
    <ProductCard data={productData} />
    <ProductCard data={productData} />
    <ProductCard data={productData} />
  </div>

</div>