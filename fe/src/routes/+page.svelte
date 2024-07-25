<script lang="ts">
    import { sessionPage } from "$lib/utils/page";

    import Beranda from "$lib/pages/beranda.svelte";
    import Koleksi from "$lib/pages/koleksi.svelte";
	import Riwayat from "$lib/pages/riwayat.svelte";

    $: $sessionPage;
</script>

<div class="pb-24"> <!-- Need to add bottom padding because position fixed block the last item -->
    {#if $sessionPage === "beranda"}
        <Beranda />
    {/if}

    {#if $sessionPage === "koleksi"}
        <Koleksi />
    {/if}

    {#if $sessionPage === "riwayat"}
        <Riwayat />
    {/if}
</div>