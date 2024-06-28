<script lang="ts">
  export let photo: string;
  export let name: string;
  export let price: string;
</script>

<div class={`p-4 border border-grey rounded-lg mx-2`}>
  <img src={photo} alt="" class={`w-[30vw] h-auto rounded-lg`}>
  <h3 class={`text-md font-medium`}>
      {name}
  </h3>
  <p class={`text-sm font-medium`}>
      {price}
  </p>
  <br>
  <button type="button" class={`me-2 w-full text-white bg-french-violet rounded-lg text-sm px-4 py-2 focus:scale-95 transition duration-300`}>
      Lihat Produk
  </button>
</div>