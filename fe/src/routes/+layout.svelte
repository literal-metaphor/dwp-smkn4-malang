<script lang="ts">
    import type { LayoutData } from './$types';
    import "../app.css";
	import NavbarButton from '$lib/components/navbar_button.svelte';

    import logo from "$lib/assets/logo.svg";
    import troli from "$lib/assets/troli.svg";

    function handlePageTroli() {
        alert("working");
    }

    // export let data: LayoutData;
</script>

<header class="overflow-x-hidden w-screen h-fit p-4 bg-white border-b border-b-grey flex justify-center items-center">
    <img src={logo} alt="Logo" class="me-4 size-6">
    <h1 class="text-md font-bold">DWP SMKN 4 Malang</h1>
    <button on:click={handlePageTroli}>
        <img src={troli} alt="Troli" class="ms-4 size-6">
    </button>
</header>

<slot />

<footer class="overflow-x-hidden w-screen h-fit p-4 bg-white border-t border-t-grey flex justify-center items-center sticky bottom-0">
    <NavbarButton option={`beranda`} />
    <NavbarButton option={`koleksi`} />
    <NavbarButton option={`riwayat`} />
    <NavbarButton option={`profile`} />
</footer>