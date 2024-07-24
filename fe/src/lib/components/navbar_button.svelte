<script lang="ts">
  import { sessionPage } from '$lib/utils/page';

  export let option: string;
  const options: string[] = ["beranda", "koleksi", "riwayat", "profile"];

  $: isActive = $sessionPage === option;

  import { ripple } from 'svelte-ripple-action';
</script>

<button use:ripple class={`rounded-full size-16 p-4 mx-1 ${isActive ? `bg-french-violet text-white`: ``} flex justify-center items-center flex-col active:scale-90 transition duration-300`} on:click={() => { setTimeout(() => {sessionPage.set(option)}, 100) }}>
  <img src={options.includes(option) ? `/src/lib/assets/${option}${isActive ? `active` : ``}.svg` : ``} alt={option} class="size-6 mb-1">
  <p class="text-sm">{option.charAt(0).toUpperCase() + option.slice(1)}</p>
</button>
