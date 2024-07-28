<script lang="ts">
  // Get profile data
  let userData = localStorage.getItem("userData");
  let parsedUserData = {} as UserData;
  if (userData && typeof userData === "string") {
    parsedUserData = JSON.parse(userData) as UserData;
  }

  // Images
  import userPlaceholder from "$lib/assets/profile.svg";
  import shopBtn from "$lib/assets/shop.svg";
  import callBtn from "$lib/assets/call.svg";
  import tosBtn from "$lib/assets/tos.svg";
  import securityBtn from "$lib/assets/padlock.svg";

  // Depedencies
	import { ripple } from "svelte-ripple-action";
	import type UserData from "$lib/types/UserData";
</script>

<div class={`lg:container overflow-x-hidden flex lg:items-center flex-col w-screen p-4`}>

  <!-- Profile -->
  <div class={`flex justify-start items-center w-full lg:w-[32%] h-fit p-4 bg-white mb-8 border border-grey rounded-lg shadow-xl`}>
    <img src={parsedUserData.avatar || userPlaceholder} alt="Profile" class="size-12 me-4">
    <div class="flex flex-col items-start">
      <p class="text-md font-semibold">{parsedUserData.first_name + " " + (parsedUserData.last_name || "")}</p>
      <p class="text-sm opacity-50">{parsedUserData.email}</p>
    </div>
  </div>

  <!-- Options -->
  <div class={`flex justify-center flex-col w-full lg:w-[32%] h-fit p-4 bg-white mb-8 border border-grey rounded-lg shadow-xl`}>
    {#each [
      { icon: securityBtn, label: 'Ubah Kata Sandi' },
      { icon: callBtn, label: 'Kontak Admin' },
      { icon: tosBtn, label: 'Ketentuan Layanan' },
    ] as item}
      <button use:ripple class={`flex justify-start items-center w-full h-fit p-2 bg-white my-2 border border-grey rounded-lg`}>
        <img src={item.icon} alt={item.label} class="size-6 me-2">
        <p class="text-[16px] font-semibold">{item.label}</p>
      </button>
    {/each}

    <br>

    <button on:click={() => {
      if (confirm("Apakah Anda yakin ingin keluar?")) {
        localStorage.clear();
        sessionStorage.clear();
        location.reload();
      }
    }} use:ripple class={`flex justify-center items-center w-full h-fit p-2 bg-french-violet text-white my-2 border border-grey rounded-lg active:scale-90 transition duration-300`}>
      Keluar
    </button>
  </div>
</div>