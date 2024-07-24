<script lang="ts">
    // import type { LayoutData } from './$types';
    import { sessionPage } from "$lib/utils/page";

    // Components
    import "../app.css";
	import NavbarButton from '$lib/components/navbar_button.svelte';

    // Images
    import logo from "$lib/assets/logo.svg";
    import troli from "$lib/assets/troli.svg";
    import troliactive from "$lib/assets/troliactive.svg";

    const layoutedPages = ["beranda", "koleksi", "riwayat", "profile"];

    // export let data: LayoutData;
</script>

{#if layoutedPages.includes($sessionPage)}
    <header class="overflow-x-hidden w-screen h-fit p-4 bg-white border-b border-b-grey flex justify-center items-center">
        <div class="flex justify-center items-center">
            <img src={logo} alt="Logo" class="me-4 size-12">
            <h1 class="text-md font-bold">DWP SMKN 4 Malang</h1>
        </div>
        <button class={`rounded-full size-12 ms-8 p-3 mx-1 ${$sessionPage === "troli" ? `bg-french-violet text-white`: ``} flex justify-center items-center active:scale-90 transition duration-300`} on:click={() => sessionPage.set("troli")}>
            {#if $sessionPage === "troli"}
                <img src={troliactive} alt="Troli" class="size-12">
            {:else}
                <img src={troli} alt="Troli" class="size-12">
            {/if}
        </button>
    </header>

    <slot />

    <footer class="overflow-x-hidden w-screen h-fit p-4 bg-white border-t border-t-grey flex justify-around items-center fixed bottom-0">
        <NavbarButton option={`beranda`} />
        <NavbarButton option={`koleksi`} />
        <NavbarButton option={`riwayat`} />
        <NavbarButton option={`profile`} />
    </footer>
{/if}