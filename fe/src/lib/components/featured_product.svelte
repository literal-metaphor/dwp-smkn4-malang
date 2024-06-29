<script lang="ts">
  export let banner: string;
  export let heading: string;
</script>

<div class={`relative w-[90vw] h-[25vh] flex justify-center items-center`}>
  <img src={banner} class={`absolute inset-0 w-[90vw] h-[25vh] object-cover rounded-lg`} alt=""/>
  <div class={`relative text-center`}>
      <h1 class={`text-lg text-white mb-4`}>
          {heading}
      </h1>
      <button type="button" class={`text-black bg-white focus:scale-95 rounded-full text-sm px-4 py-2 transition duration-300`}>
          Lihat Selengkapnya →
      </button>
  </div>
</div>