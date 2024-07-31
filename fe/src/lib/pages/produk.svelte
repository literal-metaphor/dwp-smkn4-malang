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
	import type UserData from "$lib/types/UserData";
	import type TrolleyItem from "$lib/types/TrolleyItem";

  const userId = JSON.parse(localStorage.getItem('userData') || '{}').id;

  $: isOwner = false;
  $: isFavorite = false;
  $: quantity = 1;
  $: yourRating = 0;
  $: loading = false;

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
      let ownerData: UserData = ownerRes.data;
      if (ownerData && ownerData.avatar_id) {
        const avatarRes = await api.get(`/user/avatar/${ownerData.id}`);
        ownerData.avatar = avatarRes.data;
      }
      product.owner = ownerData;
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

  // Check is owner
  async function checkIsOwner() {
    if (userId === product.owner_id) {
      isOwner = true;
    }
  }

  onMount(async () => {
    try {
      await Promise.all([getReviews(), getOwner(), getFavoritedStatus(), getYourRating(), checkIsOwner()]);
    } catch (err) {
      handleError(err);
    }
  });

  // Functions
  async function addToCart() {
    try {
      let carts = JSON.parse(sessionStorage.getItem('cart') || '[]') as TrolleyItem[];
      if (carts && carts.length > 0) {
        if (carts.some((item) => item.product.id === product.id)) throw new Error('Produk ini sudah ada di keranjang belanja');
      }
      if (!product.owner) throw new Error('Mohon tunggu proses loading selesai');
      carts.push({
        owner: product.owner,
        product: product,
        quantity: quantity
      });
      sessionStorage.setItem('cart', JSON.stringify(carts));
      setTimeout(() => {
        sessionPage.set('troli');
      }, 500)
    } catch (err) {
      handleError(err);
    }
  }
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
  // Format currency on create product form
	let price: string = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(product.price);
	let actualPrice: number = Number(price.replace(/[^0-9]/g, '').slice(0, 6));
	function formatPrice(e: Event) {
		const target = e.target as HTMLInputElement;
		let formattedVal = target.value.replace(/[^0-9]/g, '');
		if (formattedVal.length > 6) formattedVal = formattedVal.slice(0, 6); // max 6 digits
		const parsedVal = Number(formattedVal);
		actualPrice = parsedVal <= 999999 ? parsedVal : 999999;
		actualPrice = Number(formattedVal);
		price = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(Number(formattedVal));
	}
  function handleImageUpload(e: Event) {
		const target = e.target as HTMLInputElement;
		const file = target.files?.[0];
		if (!file) return;
		const reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = (e) => {
			const element = document.getElementById('preview') as HTMLImageElement;
			element.src = e.target?.result as string;
		};
	}
  function openUpdateProductModal() {
    const element = document.getElementById('update_product_modal') as HTMLDialogElement;
    element.showModal();
  }
  async function updateProduct(e: SubmitEvent) {
    e.preventDefault();

    loading = true;

    try {
      const formData = new FormData(e.target as HTMLFormElement);
      formData.set('owner_id', userId);
      formData.set('price', actualPrice.toFixed(0));
      formData.append(`_method`, 'PUT');
      const res = await api.post(`/product/${product.id}`, formData, { headers: { Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}` } });
      if (res.status.toString().startsWith('2')) {
        console.log(formData.get('image'));
        console.log(!formData.get('image'));
        if ((formData.get('image') as File).size < 1) {
          alert('Produk berhasil diperbarui');
          sessionPage.set('toko');
          location.reload();
        }
				const fileFormData = new FormData();
				const file = formData.get('image') as File;
				fileFormData.append('file', file);
        if (product.images && product.images.length > 0) {
          try {
            api.delete(`/product/photo/${product.id}`, { headers: { Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}` } });
          } catch (err) {
            throw err;
          }
        }

        const res = await api.post(`/product/photo/${product.id}`, fileFormData, { headers: { Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}` } });
        if (res.status.toString().startsWith('2')) {
          alert('Produk berhasil diperbarui');
          sessionPage.set('toko');
          location.reload();
        }
      }
    } catch (err) {
      handleError(err);
    }
  }
  async function deleteProduct() {
    loading = true;
    try {
      if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        const res = await api.delete(`/product/${product.id}`, { headers: { Authorization: `Bearer ${JSON.parse(localStorage.getItem('userData') || '{}').remember_token}` } });
        if (res.status.toString().startsWith('2')) {
          alert('Produk berhasil dihapus');
          sessionPage.set('toko');
          location.reload();
        }
      }
    } catch (err) {
      handleError(err);
    }
  }

</script>

{#if isOwner}
<dialog id="update_product_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<h3 class="text-lg font-bold">Edit Detail Produk</h3>

		<form on:submit={updateProduct}>
			<div class="mb-4">
				<label for="name" class="text-sm font-bold mb-2">Nama Produk</label>
				<input
					name="name"
					type="text"
					id="name"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="(Wajib)"
          value={product.name}
          required
				/>
			</div>
			<div class="mb-4">
				<label for="description" class="text-sm font-bold mb-2">Deskripsi</label>
				<textarea
					name="description"
					id="description"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="(Opsional)"
          value={product.description}
				/>
			</div>
			<div class="mb-4">
				<label for="price" class="text-sm font-bold mb-2">Harga</label>
				<input
						name="price"
						bind:value={price}
						on:change={formatPrice}
						type="text"
						id="price"
						class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
						placeholder="(Wajib)"
				/>
			</div>
			<div class="mb-4">
				<label for="category" class="text-sm font-bold mb-2">Kategori</label>
				<select
					name="category"
					id="category"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
          required
				>
					{#each [
            { value: 'food', label: 'Makanan' },
            { value: 'drink', label: 'Minuman' },
            { value: 'female_fashion', label: 'Fashion Wanita' },
            { value: 'male_fashion', label: 'Fashion Pria' },
            { value: 'child_fashion', label: 'Fashion Anak' },
            { value: 'furniture', label: 'Perabotan' },
          ] as category}
						<option
							selected={product.category === category.value}
							value={category.value}
						>
							{category.label}
						</option>
					{/each}
					</select>
			</div>
			<div class="mb-4">
				<label for="image" class="text-sm font-bold mb-2">Gambar</label>
				<img id="preview" class="size-32 my-2" src={product.images && product.images.length > 0 ? store + product.images[0] : noimage} alt="Preview" />
				<input
						on:change={handleImageUpload}
						name="image"
						type="file"
						id="image"
						class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
						placeholder="(Opsional)"
				/>
			</div>
			<button
        disabled={loading}
				type="submit"
				use:ripple
				class={`bg-french-violet text-white font-bold
				py-2 px-4 rounded focus:outline-none focus:shadow-outline ${loading && `opacity-50`} transition duration-300`}>Edit Produk</button
			>
      <button
        disabled={loading}
				on:click={deleteProduct}
				use:ripple
				class={`bg-red text-white font-bold
				py-2 px-4 rounded focus:outline-none focus:shadow-outline ${loading && `opacity-50`} transition duration-300`}>Hapus Produk</button
			>
		</form>

		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
		</form>
	</div>
</dialog>
{/if}

<header class="overflow-x-hidden w-screen h-fit p-4 lg:p-8 bg-white border border-grey flex justify-between items-center fixed top-0 z-50">
  <div class="flex justify-center items-center">
    <button on:click={() => sessionPage.set(isOwner ? `toko` : `beranda`)} type="button" class="me-2" >
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
      </svg>
    </button>
    <h1 class="text-md font-bold">Produk</h1>
  </div>
  <div class="flex justify-center items-center">
    {#if isOwner}
      <button on:click={openUpdateProductModal} use:ripple type="button" class="me-2 p-2 rounded-full">
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
          <span>⭐ {ratings && ratings.length > 0 ? (ratings.reduce((a, b) => a + b.rating, 0) / ratings.length / 5) * 100 + "%" : `0%`} ({ratings.length} ulasan)</span>
        </div>
        <p class="text-xl font-bold mt-2">
          {new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(product.price)}
        </p>

      <!-- Product Description -->
      <p class="mt-4" >{product.description}</p>

      <!-- Quantity and Add to Cart -->
      {#if !isOwner}
        <div class="flex items-center mt-4">
            <button use:ripple on:click={() => quantity > 1 && quantity--} class="bg-french-violet text-white font-bold py-2 px-4 rounded-l">-</button>
            <span class="mx-2">{quantity}</span>
            <button use:ripple on:click={() => quantity++} class="bg-french-violet text-white font-bold py-2 px-4 rounded-r">+</button>
            <button on:click={() => addToCart()} use:ripple class="bg-french-violet text-white font-bold py-2 px-4 rounded ml-4">🛒 Tambahkan ke Troli</button>
        </div>
      {/if}

      <!-- Shop Details -->
      <div class="mt-8 border-t pt-4">
        <h2 class="m-2 font-medium text-xl">Penjual:</h2>
          <div class="flex items-center space-x-4">
              <img src={product.owner?.avatar ? store + product.owner.avatar.filename : userPlaceholder} alt="Shop" class="w-12 h-12 rounded-full">
              <span class="ml-2 text-lg font-bold text-blue">{product.owner ? "@"+product.owner.username : `Loading...`}</span>
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
                    <p class="font-bold"><span class="text-blue">@{data.user && data.user.username}</span> - {(() => {
                      const days = Math.floor((new Date().getTime() - new Date(data.created_at).getTime()) / (1000 * 3600 * 24));
                      return days === 0 ? 'Today' :
                             days > 365 ? `${Math.floor(days / 365)} year${Math.floor(days / 365) > 1 ? 's' : ''} ago` :
                             days > 30 ? `${Math.floor(days / 30)} month${Math.floor(days / 30) > 1 ? 's' : ''} ago` :
                             `${days} day${days > 1 ? 's' : ''} ago`;
                    })()}</p>
                    <p>{data.content}</p>
                </div>
              {/each}
              <!-- Repeat the above div for each comment -->
          </div>
      </div>
  </div>
</div>