<script lang="ts">
    import { sessionPage } from "$lib/utils/page";

    import Beranda from "$lib/pages/beranda.svelte";
    import Koleksi from "$lib/pages/koleksi.svelte";
	import Riwayat from "$lib/pages/riwayat.svelte";
	import Profile from "$lib/pages/profile.svelte";

    $: $sessionPage;
</script>

<div class="pt-24 lg:pt-32 pb-32 lg:pb-0 lg:ps-32"> <!-- Padding because fixed navbar and header would block the contents -->
    {#if $sessionPage === "beranda"}
        <Beranda />
    {/if}

    {#if $sessionPage === "koleksi"}
        <Koleksi />
    {/if}

    {#if $sessionPage === "riwayat"}
        <Riwayat />
    {/if}

    {#if $sessionPage === "profile"}
        <Profile />
    {/if}
</div>