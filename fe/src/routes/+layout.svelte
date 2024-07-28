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
	import { authStatus } from "$lib/utils/guard";
	import type UserData from "$lib/types/UserData";
	import { api } from "$lib/utils/api";

    // Guard functionality
    $: $authStatus;
    onMount(async () => {
        async function checkAuth() {
            const userData: UserData = JSON.parse(localStorage.getItem("userData") || "{}");

            function handleErr() {
                // Prevent user from accessing protected pages
                authStatus.set(false);
                localStorage.clear();
                sessionStorage.clear();
                location.reload();
            }

            if (userData.id && userData.remember_token) {
                try {
                    authStatus.set(true);
                    const res = await api.get(`/auth/${userData.id}`, { headers: { Authorization: `Bearer ${userData.remember_token}` } });

                    if (!res.status.toString().startsWith("2")) {
                        throw new Error(res.data.message);
                    }
                } catch (err) {
                    console.log(err);
                    alert(err instanceof Error ? err.message : "Terjadi kesalahan autentikasi.");
                    handleErr();
                }
            } else {
                if ($sessionPage !== "landing") {
                    handleErr();
                }
            }
        }
        await checkAuth();
        sessionPage.subscribe(checkAuth)
    })

    // Store sessionPage on sessionStorage
    onMount(() => {
        const sessionStoragePage = sessionStorage.getItem("sessionPage");
        if (sessionStoragePage && typeof sessionStoragePage === "string") {
            sessionPage.set(sessionStoragePage);
        }
        sessionPage.subscribe((val) => {
            sessionStorage.setItem("sessionPage", val);
        })
    })

    // Define which pages should be layouted
    const layoutedPages = ["beranda", "koleksi", "riwayat", "profile"];

    // export let data: LayoutData;
</script>

{#if $authStatus}

{#if layoutedPages.includes($sessionPage)}
    <header class="overflow-x-hidden w-screen h-fit p-4 lg:p-8 bg-white border border-grey flex justify-between items-center fixed top-0 z-50">
        <div class="flex justify-center items-center">
            <button on:click={() => sessionPage.set("landing")} type="button">
                <img src={logo} alt="Logo" class="me-4 size-12">
            </button>
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

    <footer class="overflow-x-hidden w-screen h-fit lg:w-fit lg:col-span-1 lg:h-screen p-4 bg-white border border-grey flex lg:flex-col justify-center items-center fixed bottom-0">
        <NavbarButton option={`beranda`} />
        <NavbarButton option={`koleksi`} />
        <NavbarButton option={`riwayat`} />
        <NavbarButton option={`profile`} />
    </footer>
{/if}

{/if}

<slot />