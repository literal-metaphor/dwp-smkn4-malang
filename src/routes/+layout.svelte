<script lang="ts">
    import type { LayoutData } from './$types';
    import "../app.css";
	import NavbarButton from '$lib/components/navbar_button.svelte';

    export let data: LayoutData;
</script>

<header class="overflow-x-hidden w-screen h-fit p-4 bg-white border-b border-b-grey flex justify-center items-center">
    <img src="" alt="Logo" class="me-4">
    <h1 class="text-md font-bold">DWP SMKN 4 Malang</h1>
</header>

<slot />

<footer class="overflow-x-hidden w-screen h-fit p-4 bg-white border-t border-t-grey flex justify-center items-center sticky bottom-0">
    <NavbarButton isActive={true} option={`beranda`} />
    <NavbarButton isActive={false} option={`koleksi`} />
    <NavbarButton isActive={false} option={`troli`} />
    <NavbarButton isActive={false} option={`profile`} />
</footer>