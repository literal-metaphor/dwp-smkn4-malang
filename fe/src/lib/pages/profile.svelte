<script lang="ts">
  import { userData } from "$lib/types/Sample";
  import { shopData } from "$lib/types/Sample";

  import userPlaceholder from "$lib/assets/profile.svg";
  import shopBtn from "$lib/assets/shop.svg";
  import callBtn from "$lib/assets/call.svg";
  import tosBtn from "$lib/assets/tos.svg";
  import securityBtn from "$lib/assets/padlock.svg";
	import { ripple } from "svelte-ripple-action";
</script>

<div class={`container overflow-x-hidden flex justify-content flex-col w-screen h-fit p-4`}>

  <!-- Profile -->
  <div class={`flex justify-start items-center w-full h-fit p-4 bg-white mb-8 border border-grey rounded-lg shadow-xl`}>
    <img src={userData.avatar || userPlaceholder} alt="Profile" class="size-12 me-4">
    <div class="flex flex-col items-start">
      <p class="text-md font-semibold">{shopData ? shopData.name : userData.first_name + " " + (userData.last_name || "")}</p>
      <p class="text-sm opacity-50">{userData.email}</p>
    </div>
  </div>

  <!-- Options -->
  <div class={`flex justify-center flex-col w-full h-fit p-4 bg-white mb-8 border border-grey rounded-lg shadow-xl`}>
    {#each [
      { icon: shopBtn, label: 'Toko Anda', condition: shopData },
      { icon: securityBtn, label: 'Ubah Kata Sandi', condition: true },
      { icon: callBtn, label: 'Kontak Admin', condition: true },
      { icon: tosBtn, label: 'Ketentuan Layanan', condition: true },
    ] as item}
      {#if item.condition}
        <button use:ripple class={`flex justify-start items-center w-full h-fit p-2 bg-white my-2 border border-grey rounded-lg`}>
          <img src={item.icon} alt={item.label} class="size-6 me-2">
          <p class="text-[16px] font-semibold">{item.label}</p>
        </button>
      {/if}
    {/each}

    <br>

    <button use:ripple class={`flex justify-center items-center w-full h-fit p-2 bg-french-violet text-white my-2 border border-grey rounded-lg active:scale-90 transition duration-300`}>
      Log out
    </button>
  </div>
</div>