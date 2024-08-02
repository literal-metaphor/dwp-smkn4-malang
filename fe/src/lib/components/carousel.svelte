<script lang="ts">
	export let items: { img: string; alt: string }[];
	export let duration: number = 3000;

	// Just for demonstration purpose
	// items = [
	//   {
	//     img: "https://flowbite.com/docs/images/carousel/carousel-1.svg",
	//     alt: "Image 1",
	//   },
	//   {
	//     img: "https://flowbite.com/docs/images/carousel/carousel-2.svg",
	//     alt: "Image 2",
	//   },
	//   {
	//     img: "https://flowbite.com/docs/images/carousel/carousel-3.svg",
	//     alt: "Image 3",
	//   },
	// ];

	let current = 0;

	setInterval(() => {
		current = (current + 1) % items.length;
	}, duration);
</script>

<div class="relative">
	<div class="overflow-hidden relative rounded-lg">
		{#each items as item, index}
			<div
				class="absolute inset-0 transition-transform duration-700 ease-in-out transform {current ===
				index
					? 'translate-x-0'
					: 'translate-x-full'}"
			>
				<img src={item.img} alt={item.alt} class="block w-full h-full object-cover" />
			</div>
		{/each}
	</div>
</div>
