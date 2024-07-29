<script lang="ts">
  // Images
  import noimage from "$lib/assets/noimage.svg";
	import type { CommentData } from "$lib/types/CommentData";
	import type ProductData from "$lib/types/ProductData";
	import type { RatingData } from "$lib/types/RatingData";
  // import { productData, ratingData, commentData } from "$lib/types/Sample";
  import heart from "$lib/assets/heart.svg";
  import heartfill from "$lib/assets/heartfill.svg";
  import star from "$lib/assets/star.svg";
  import starfill from "$lib/assets/starfill.svg";
  import pen from "$lib/assets/pen.svg";
  import userPlaceholder from "$lib/assets/profile.svg";

	import { ripple } from "svelte-ripple-action";
	import { sessionPage } from "$lib/utils/page";
	import { AxiosError } from "axios";
	import { api, store } from "$lib/utils/api";
	import { onMount } from "svelte";

  const userId = JSON.parse(sessionStorage.getItem('user') || '{}').id;

  $: isFavorite = false;
  $: quantity = 1;
  $: yourRating = 0;

  // Data
  let product: ProductData = JSON.parse(sessionStorage.getItem('product') || '{}') as ProductData;

  // Get reviews
  let ratings: RatingData[] | [] = [];
  let comments: CommentData[] | [] = [];
  // Helper function to handle errors
  function handleError(err: any) {
    console.log(err);
    switch (true) {
      case err instanceof AxiosError:
        alert(err.response?.data.message);
        break;
      case err instanceof Error:
        alert(err.message);
        break;
      default:
        alert('Terjadi kesalahan');
    }
  }

  // Get reviews
  async function getReviews() {
    try {
      const ratingsRes = await api.get(`/rating/product/${product.id}`);
      ratings = ratingsRes.data;

      const commentsRes = await api.get(`/comment/product/${product.id}`);
      comments = commentsRes.data;

      // Get user for each comment
      comments = await Promise.all(
        comments.map(async (comment) => {
          const userRes = await api.get(`/user/${comment.user_id}`);
          comment.user = userRes.data;
          return comment;
        })
      );
    } catch (err) {
      handleError(err);
    }
  }

  // Get owner
  async function getOwner() {
    try {
      const ownerRes = await api.get(`/user/${product.owner_id}`);
      product.owner = ownerRes.data;
    } catch (err) {
      handleError(err);
    }
  }

  // Get favorited status
  async function getFavoritedStatus() {
    try {
      const res = await api.get(`/product/wishlist/${JSON.parse(localStorage.getItem('userData') || '{}').id}/${product.id}`);
      isFavorite = res.data;
    } catch (err) {
      handleError(err);
    }
  }

  // Get your rating
  async function getYourRating() {
    try {
      const res = await api.get(`/rating/user/${product.id}`, { headers: { Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}` } });
      yourRating = parseInt(res.data.rating) as 1 | 2 | 3 | 4 | 5;
    } catch (err) {
      handleError(err);
    }
  }

  onMount(async () => {
    try {
      await Promise.all([getReviews(), getOwner(), getFavoritedStatus(), getYourRating()]);
    } catch (err) {
      handleError(err);
    }
  });

  // Functions
  async function toggleFavorite() {
    try {
      const res = await api.post(`/product/wishlist/${JSON.parse(localStorage.getItem('userData') || '{}').id}/${product.id}`);
      if (res.status.toString().startsWith('2')) {
        await getFavoritedStatus();
      }
    } catch (err) {
      handleError(err);
    }
  }
  async function rateProduct(newRating: number) {
    try {
      const res = await api.post(`/rating`, { product_id: product.id, rating: newRating }, { headers: { Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}` } });
      yourRating = parseInt(res.data.rating) as 1 | 2 | 3 | 4 | 5;
    } catch (err) {
      handleError(err);
    }
  }
  async function submitComment(e: SubmitEvent) {
    e.preventDefault();

    try {
      const formData = new FormData(e.target as HTMLFormElement);
      formData.append('product_id', product.id);
      const res = await api.post(`/comment`, formData, { headers: { Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}` } });
      console.log(res);
      if (res.status.toString().startsWith('2')) {
        getReviews();
      }
    } catch (err) {
      handleError(err);
    }
  }

</script>

<header class="overflow-x-hidden w-screen h-fit p-4 lg:p-8 bg-white border border-grey flex justify-between items-center fixed top-0 z-50">
  <div class="flex justify-center items-center">
    <button on:click={() => sessionPage.set(userId === product.owner_id ? `toko` : `beranda`)} type="button" class="me-2" >
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
      </svg>
    </button>
    <h1 class="text-md font-bold">Produk</h1>
  </div>
  <div class="flex justify-center items-center">
    {#if userId === product.owner_id}
      <button use:ripple type="button" class="me-2 p-2 rounded-full">
        <img src={pen} alt="Edit product" class="size-8">
      </button>
    {/if}
    <button use:ripple on:click={toggleFavorite} type="button" class="me-2 p-2 rounded-full">
      <img src={isFavorite ? heartfill : heart} alt="Favorite" class="size-8">
    </button>
  </div>
</header>

<div class="mx-auto px-4 pt-32 flex flex-col lg:flex-row space-x-4">
  <!-- Left part: Product Image -->
  <div class="lg:w-1/2">
      <img src={product.images && product.images.length > 0 ? store + product.images[0] : noimage} alt="Product" class="w-full h-auto mb-8">
  </div>

  <!-- Right part: Product Details -->
  <div class="lg:w-1/2 px-4">
      <!-- Product Name, Rating, and Price -->
      <h1 class="text-3xl font-bold">{product.name}</h1>
      <div class="flex items-center">
        <!-- <img src={starfill} alt="Review star icon" class="size-4 me-2"> -->
        <span>⭐ {ratings && ratings.length > 0 ? ratings.reduce((a, b) => a + b.rating, 0) / ratings.length : `0`} ({ratings.length} ulasan)</span>
      </div>
      <p class="text-xl font-bold mt-2">
        {new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(product.price)}
      </p>

      <!-- Product Description -->
      <p class="mt-4" >{product.description}</p>

      <!-- Quantity and Add to Cart -->
      <div class="flex items-center mt-4">
          <button use:ripple on:click={() => quantity > 1 && quantity--} class="bg-french-violet text-white font-bold py-2 px-4 rounded-l">-</button>
          <span class="mx-2">{quantity}</span>
          <button use:ripple on:click={() => quantity++} class="bg-french-violet text-white font-bold py-2 px-4 rounded-r">+</button>
          <button use:ripple class="bg-french-violet text-white font-bold py-2 px-4 rounded ml-4">🛒 Tambahkan ke Troli</button>
      </div>

      <!-- Shop Details -->
      <div class="mt-8 border-t pt-4">
        <h2 class="m-2 font-medium text-xl">Penjual:</h2>
          <div class="flex items-center">
              <img src={product.owner?.avatar ? product.owner.avatar : userPlaceholder} alt="Shop" class="w-12 h-12 rounded-full">
              <span class="ml-2 text-lg font-bold">{product.owner ? product.owner.username: `Loading...`}</span>
          </div>
      </div>

      <!-- Reviews -->
      <div class="mt-8 border-t pt-4">
          <h2 class="text-2xl font-bold">Ulasan</h2>

          <!-- Rating Stars -->
          <div class="flex mt-2">
              <!-- Replace the following with your star rating component -->
            {#each Array(5) as _, i}
              <button use:ripple class="size-8 p-2 rounded-full" on:click={() => { rateProduct(i + 1) }} type="button">
                <img src={i < yourRating ? starfill : star} alt="Rating indicator icon" class="size-full">
              </button>
            {/each}
              <!-- Repeat the above SVG for each star -->
          </div>

          <!-- Comment Input -->
          <form on:submit={submitComment}>
            <textarea name="content" class="mt-4 w-full p-2 border rounded" placeholder="Tuliskan komentarmu di sini..."></textarea>
            <div class="flex items-center justify-start space-x-4 mt-2">
              <button
                type="submit"
                use:ripple
                class="bg-french-violet text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Kirim</button
              >
              <button
                type="reset"
                use:ripple
                class="bg-white text-black border border-grey font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Reset</button
              >
            </div>
          </form>


          <!-- Comments Section -->
          <div class="mt-4 h-64 overflow-y-auto">
              <!-- Replace the following with your comment component -->
               {#each comments as data}
               <!-- data because comment is currently used in PostCSS -->
                <div class="border-t py-2">
                    <!-- <p class="font-bold">@{data.user && data.user.username} - {Math.floor((new Date().getTime() - new Date(data.created_at).getTime()) / (1000 * 3600 * 24))} days ago</p> -->
                    <p class="font-bold"><span class="text-blue">@{data.user && data.user.username}</span> - {(() => { const days = Math.floor((new Date().getTime() - new Date(data.created_at).getTime()) / (1000 * 3600 * 24)); return days > 365 ? `${Math.floor(days / 365)} year${Math.floor(days / 365) > 1 ? 's' : ''} ago` : days > 30 ? `${Math.floor(days / 30)} month${Math.floor(days / 30) > 1 ? 's' : ''} ago` : `${days} day${days > 1 ? 's' : ''} ago`; })()}</p>
                    <p>{data.content}</p>
                </div>
              {/each}
              <!-- Repeat the above div for each comment -->
          </div>
      </div>
  </div>
</div>