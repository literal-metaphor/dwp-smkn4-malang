<script lang="ts">
	import { sessionPage } from "$lib/utils/page";


</script>

<header class="overflow-x-hidden w-screen h-fit p-4 lg:p-8 bg-white border border-grey flex justify-between items-center fixed top-0 z-50">
  <div class="flex justify-center items-center">
    <button on:click={() => sessionPage.set('profile')} type="button" class="me-2">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
      </svg>
    </button>
    <h1 class="text-md font-bold">Troli Belanja</h1>
  </div>
</header>

<div class="bg-gray-100 p-6">
  <!-- Payment Options Box -->
  <div class="bg-white p-4 shadow-md mb-6">
      <h2 class="text-lg font-semibold mb-2">Payment Options</h2>
      <p>Select your preferred payment method.</p>
      <p class="mt-2 text-sm text-gray-500">More methods coming soon...</p>
  </div>

  <!-- Product Box -->
  <div class="bg-white p-4 shadow-md">
      <!-- Owner/Shop Box -->
      <div class="flex items-center mb-4">
          <img src="profile-image.jpg" alt="Profile" class="w-10 h-10 rounded-full mr-2">
          <span class="font-semibold">Username</span>
      </div>

      <!-- Product Details -->
      <div class="flex">
          <!-- Product Image -->
          <img src="product-image.jpg" alt="Product" class="w-32 h-32 object-cover mr-4">

          <!-- Product Details -->
          <div class="flex-1">
              <h2 class="text-lg font-semibold">Product Name</h2>
              <p class="text-gray-600">$Price</p>

              <!-- Quantity and Total Price -->
              <div class="flex items-center mt-4">
                  <input type="number" value="1" min="1" class="w-16 mr-2 p-2 border border-gray-300 rounded">
                  <span>Total: $Total Price</span>
              </div>
          </div>
      </div>
  </div>
</div>

