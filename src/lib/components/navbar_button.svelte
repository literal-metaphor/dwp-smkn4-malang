<script lang="ts">
  export let isActive: boolean;
  export let option: string;
  const options: string[] = ["beranda", "koleksi", "troli", "profile"];
</script>

{#if option !== "profile"}
<div class={`rounded-full w-[72px] h-[72px] p-4 mx-1 ${isActive ? `bg-french-violet text-white`: ``} flex justify-center items-center flex-col`}>
  <img src={options.includes(option) ? `/src/lib/assets/${option}${isActive ? `active` : ``}.svg` : ``} alt={option} class="w-[24px] h-[24px] mb-1">
  <p class="text-sm">{option.charAt(0).toUpperCase() + option.slice(1)}</p>
</div>
{:else}
<div class={`rounded-full w-[72px] h-[72px] p-4 mx-1 ${isActive ? `bg-black text-white`: ``} flex justify-center items-center flex-col`}>
  <img src={``} alt={option} class="w-[24px] h-[24px] mb-1">
  <p class="text-sm">Profile</p>
</div>
{/if}