<script lang="ts">
    import type { PageData } from './$types';

    // Components
    import FeaturedProduct from '$lib/components/featured_product.svelte';

    // Images
    import bannerex from "$lib/assets/bannerex.svg";
    import shirtex from "$lib/assets/shirtex.svg";
	import CategoryButton from '$lib/components/category_button.svelte';
	import ProductCard from '$lib/components/product_card.svelte';

    // export let data: PageData;
</script>

<div class={`container overflow-x-hidden flex justify-content flex-col w-screen h-fit p-4`}>

    <!-- Featured product -->
    <h2 class={`text-lg font-bold`}>
        Produk Unggulan
    </h2>

    <br>

    <FeaturedProduct banner={bannerex} heading={`Koleksi Baju Modern`} />

    <br>

    <!-- Categories -->
    <div class={`flex items-center overflow-x-auto`}>
        <CategoryButton current={true} label={`Semua`} />
        <CategoryButton current={false} label={`Fashion`} />
        <CategoryButton current={false} label={`Peralatan`} />
        <CategoryButton current={false} label={`Sembako`} />
        <CategoryButton current={false} label={`Perabotan`} />
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
        <ProductCard name={`T-Shirt`} price={`Rp 10,000.00`} photo={shirtex} />
        <ProductCard name={`T-Shirt`} price={`Rp 10,000.00`} photo={shirtex} />
        <ProductCard name={`T-Shirt`} price={`Rp 10,000.00`} photo={shirtex} />
        <ProductCard name={`T-Shirt`} price={`Rp 10,000.00`} photo={shirtex} />
        <ProductCard name={`T-Shirt`} price={`Rp 10,000.00`} photo={shirtex} />
    </div>

</div>