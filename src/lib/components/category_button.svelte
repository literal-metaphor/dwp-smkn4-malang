<script lang="ts">
  export let current: boolean;
  export let label: string;
</script>

<button type="button" class={`me-2 ${current ? `text-white bg-french-violet` : `text-black bg-white`} rounded-full text-sm px-4 py-2 transition duration-300`}>
  {label}
</button>