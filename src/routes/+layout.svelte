<script lang="ts">
    import type { LayoutData } from './$types';
    import "../app.css";

    export let data: LayoutData;
</script>

<header class="w-screen h-fit p-4 bg-white border-b border-b-grey flex justify-center items-center">
    <img src="" alt="Logo" class="me-4">
    <h1 class="text-md font-bold">DWP SMKN 4 Malang</h1>
</header>
<slot />