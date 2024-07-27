<script lang="ts">
  // Images
  import logo from "$lib/assets/logo.svg";
  import jumbotron from "$lib/assets/jumbotron.png";
  import furniture from "$lib/assets/furniture.png";
  import food from "$lib/assets/food.png";
  import drink from "$lib/assets/drink.png";
  import fashion from "$lib/assets/fashion.png";
  import tags from "$lib/assets/tags.svg";
  import shield from "$lib/assets/shield.svg";
  import headset from "$lib/assets/headset.svg";

	import { ripple } from "svelte-ripple-action";
</script>

<header class="bg-background shadow-md">
  <div class="lg:container mx-auto py-4 flex justify-between items-center">
      <div class="flex items-center p-4">
          <img src={logo} alt="Logo" class="size-12 me-8">
          <nav class="hidden lg:block">
              <ul class="flex space-x-4">
                  <li><a href="#hero" class="">Beranda</a></li>
                  <li><a href="#produk_kami" class="">Produk Kami</a></li>
                  <li><a href="#keunggulan" class="">Keunggulan</a></li>
              </ul>
          </nav>
      </div>
      <div class="flex items-center p-4 space-x-4">
          <button use:ripple class="bg-white px-6 py-2 font-semibold rounded-md border border-grey ">Masuk</button>
          <button use:ripple class="bg-french-violet text-white font-semibold px-6 py-2 rounded-md ">Daftar</button>
      </div>
  </div>
</header>

<!-- Hero -->
<section id="hero" class="bg-background py-20">
  <div class="lg:container w-[90vw] lg:w-[50vw] mx-auto text-center">
      <img src={logo} alt="Logo" class="mx-auto size-32 mb-6">

      <h1 class="text-h6 font-bold mb-6">Temukan Kebutuhan Rumah Tangga dan Banyak Lagi <br> Hanya di <span class="text-french-violet">DWP SMKN 4 Malang</span></h1>

      <p class="mb-6">Rasakan sensasi berbelanja tanpa rasa khawatir. Nikmati kumpulan produk kami yang berkualitas tinggi dengan harga terjangkau.</p>

      <button use:ripple class="m-2 bg-french-violet text-white font-semibold px-12 py-4 mx-1 rounded-md ">Gabung Sekarang</button>
      <button use:ripple on:click={() => document.getElementById("produk_kami")?.scrollIntoView({ behavior: "smooth" })} class="m-2 bg-white px-12 py-4 font-semibold rounded-md border border-grey mx-1 ">Lihat Lebih Lanjut</button>
  </div>
</section>

<!-- Jumbotron -->
<img src={jumbotron} alt="Jumbo" class="w-full">

<!-- Produk Kami -->
<section id="produk_kami" class="py-12 px-4 bg-white">
  <div class="lg:container mx-auto">
    <div class="mb-6 text-center lg:text-start">
      <h1 class="text-h6 font-bold">Produk Kami</h1>
      <p class="opacity-50">Apa saja produk yang kami tawarkan?</p>
    </div>
    <div class="grid grid-cols-4 gap-4">
      <div class="col-span-4 lg:col-span-2 h-fit">
        <img src={furniture} alt="Furniture" class="h-auto w-full rounded-2xl mb-2">
        <h2 class="text-lg font-bold">Perabotan</h2>
        <p class="text-sm opacity-50">Alat Rumah Tangga</p>
      </div>
      <div class="col-span-2 lg:col-span-1 h-fit">
        <img src={food} alt="Food" class="h-auto w-full rounded-2xl mb-2">
        <h2 class="text-lg font-bold">Makanan</h2>
        <p class="text-sm opacity-50">Lezat dan Bergizi</p>
      </div>
      <div class="col-span-2 lg:col-span-1 h-fit">
        <img src={drink} alt="Drink" class="h-auto w-full rounded-2xl mb-2">
        <h2 class="text-lg font-bold">Minuman</h2>
        <p class="text-sm opacity-50">Legakan Dahaga</p>
      </div>
    </div>
    <img src={fashion} alt="Fashion" class="h-auto w-full rounded-2xl mb-2 mt-8">
    <h2 class="text-lg font-bold">Fashion</h2>
    <p class="text-sm opacity-50">Trendy dan Stylish</p>
  </div>
</section>

<!-- Keunggulan -->
<section id="keunggulan" class="py-12 p-4 bg-white">
  <div class="lg:container mx-auto">
    <div class="mb-6 text-center lg:text-start">
      <h1 class="text-h6 font-bold">Keunggulan</h1>
      <p class="opacity-50">Apa saja keuntungan memakai platform kami?</p>
    </div>
    <div class="grid grid-cols-3 gap-6">
      <div class="col-span-3 lg:col-span-1 bg-white p-6 rounded-md shadow-md flex justify-between items-center">
        <div class="flex flex-col justify-center items-center lg:block w-full">
          <img src={tags} alt="Tags" class="size-12 lg:hidden">
          <br class="lg:hidden">
          <h3 class="text-xl font-bold mb-2">Harga Terjangkau</h3>
          <p>Nikmati penawaran serba hemat</p>
        </div>
        <img src={tags} alt="Tags" class="size-12 hidden lg:block">
      </div>
      <div class="col-span-3 lg:col-span-1 bg-white p-6 rounded-md shadow-md flex justify-between items-center">
        <div class="flex flex-col justify-center items-center lg:block w-full">
          <img src={shield} alt="Shield" class="size-12 lg:hidden">
          <br class="lg:hidden">
          <h3 class="text-xl font-bold mb-2">Keamanan Transaksi</h3>
          <p>Pembayaran terjamin aman</p>
        </div>
        <img src={shield} alt="Shield" class="size-12 hidden lg:block">
      </div>
      <div class="col-span-3 lg:col-span-1 bg-white p-6 rounded-md shadow-md flex justify-between items-center">
        <div class="flex flex-col justify-center items-center lg:block w-full">
          <img src={headset} alt="Headset" class="size-12 lg:hidden">
          <br class="lg:hidden">
          <h3 class="text-xl font-bold mb-2">Customer Support</h3>
          <p>Pembayaran terjamin aman</p>
        </div>
        <img src={headset} alt="Headset" class="size-12 hidden lg:block">
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-12 p-4 bg-french-violet text-white">
  <div class="lg:container mx-auto flex flex-col items-center lg:items-start">
    <h2 class="text-3xl font-bold mb-2">Tunggu Apa Lagi?</h2>
    <p>Ayo mulai berbelanja di DWP SMKN 4 Malang!</p>
    <br>
    <button use:ripple class="bg-white text-black px-8 py-3 rounded-md">Gabung Sekarang</button>
    <!-- <img src="" alt="CTA"> -->
  </div>
</section>

<footer class="bg-white py-8">
  <div class="lg:container mx-auto flex justify-center items-center">
      <div class="flex items-center">
          <img src={logo} alt="Logo" class="size-8 me-3">
          <p>&copy; 2024 DWP SMKN 4 Malang. <br class="lg:hidden"> Seluruh Hak Cipta.</p>
      </div>
  </div>
</footer>