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

    // Depedencies
    import "svelte-ripple-action/ripple.css";
    import { ripple } from 'svelte-ripple-action';
	import { onMount } from "svelte";

    // Scroll to top functionality
    let isScrolled = false;
    onMount(() => {
    const handleScroll = () => {
        isScrolled = window.scrollY > 0;
    };
    window.addEventListener('scroll', handleScroll);
    return () => {
        window.removeEventListener('scroll', handleScroll);
    };
    });

    const layoutedPages = ["beranda", "koleksi", "riwayat", "profile"];

    // export let data: LayoutData;
</script>

{#if layoutedPages.includes($sessionPage)}
    <header class="overflow-x-hidden w-screen h-fit p-4 lg:p-8 bg-white border border-grey flex justify-between items-center fixed top-0 z-50">
        <div class="flex justify-center items-center">
            <img src={logo} alt="Logo" class="me-4 size-12">
            <h1 class="text-md font-bold">DWP SMKN 4 Malang</h1>
        </div>
        <button use:ripple class={`rounded-full size-12 ms-8 p-3 mx-1 ${$sessionPage === "troli" ? `bg-french-violet text-white`: ``} flex justify-center items-center active:scale-90 transition duration-300`}} on:click={() => sessionPage.set("troli")}>
            {#if $sessionPage === "troli"}
                <img src={troliactive} alt="Troli" class="size-12">
            {:else}
                <img src={troli} alt="Troli" class="size-12">
            {/if}
        </button>
    </header>

    <slot />

    <footer class="overflow-x-hidden w-screen h-fit lg:w-fit lg:col-span-1 lg:h-screen p-4 bg-white border border-grey flex lg:flex-col justify-center items-center fixed bottom-0">
        <NavbarButton option={`beranda`} />
        <NavbarButton option={`koleksi`} />
        <NavbarButton option={`riwayat`} />
        <NavbarButton option={`profile`} />
    </footer>

    <!-- Scroll to top -->
    <button type="button" on:click={() => window.scrollTo({ top: 0, behavior: 'smooth' })} class={`fixed bottom-40 lg:bottom-16 end-12 rounded-full shadow-lg ${!isScrolled && `opacity-0`} transition duration-300 z-50`}>
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#7F2CCB" class="bi bi-arrow-up-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
        </svg>
    </button>
{/if}