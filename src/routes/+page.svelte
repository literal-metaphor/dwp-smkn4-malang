<script lang="ts">
    import type { PageData } from './$types';

    // Images
    import bannerex from "$lib/assets/bannerex.svg";
    import shirtex from "$lib/assets/shirtex.svg";

    export let data: PageData;
</script>

<div class={`container flex justify-content flex-col w-screen h-fit p-4`}>
    <!-- Features Product -->
    <h2 class={`text-lg font-bold`}>
        Produk Unggulan
    </h2>

    <br>

    <div class={`relative w-[90vw] h-[180px] flex justify-center items-center`}>
        <img src={`${bannerex}`} class={`absolute inset-0 w-[90vw] h-[180px] object-cover rounded-lg`} alt=""/>
        <div class={`relative text-center`}>
            <h1 class={`text-lg text-white mb-4`}>
                Koleksi Baju Modern
            </h1>
            <button type="button" class={`text-black bg-white focus:scale-95 rounded-full text-sm px-4 py-2 transition duration-300`}>
                Lihat Selengkapnya →
            </button>
        </div>
    </div>

    <br>

    <!-- Categories -->
    <div class={`flex items-center overflow-x-auto`}>
        <button type="button" class={`me-2 text-white bg-french-violet rounded-full text-sm px-4 py-2 transition duration-300`}>
            Semua
        </button>
        <button type="button" class={`me-2 text-black bg-white rounded-full text-sm px-4 py-2 transition duration-300`}>
            Fashion
        </button>
        <button type="button" class={`me-2 text-black bg-white rounded-full text-sm px-4 py-2 transition duration-300`}>
            Peralatan
        </button>
        <button type="button" class={`me-2 text-black bg-white rounded-full text-sm px-4 py-2 transition duration-300`}>
            Sembako
        </button>
        <button type="button" class={`me-2 text-black bg-white rounded-full text-sm px-4 py-2 transition duration-300`}>
            Perabotan
        </button>
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
    
</div>