<script lang="ts">
  import { transactionData } from "$lib/types/Sample";
	import type TransactionData from "$lib/types/TransactionData";

  const datas: TransactionData[] = [transactionData, transactionData, transactionData, transactionData, transactionData, transactionData, transactionData, transactionData, transactionData, transactionData];

  import transactionImg from "$lib/assets/transaction.svg";
	import { ripple } from "svelte-ripple-action";

  const methods: { [key: string]: string } = {
    "cod": "COD"
  }
</script>

<div class={`lg:container overflow-x-hidden flex lg:items-center flex-col w-screen lg:h-[80vh] p-4`}>

  <h2 class={`text-xl ms-2 mb-2 font-bold`}>
    Transaksi Terakhir
  </h2>

  <div class="flex lg:items-center w-full lg:w-[32%] h-full flex-col overflow-y-auto">

    {#each datas as data}
      <button use:ripple class={`flex justify-between items-center w-full h-fit px-4 py-4 bg-white my-2 border border-grey rounded-lg`}>
        <div class="flex items-center">
          <img src={transactionImg} alt="Transaction" class={`size-8 rounded-full me-3`}>
          <p class="">Pembayaran / {methods[data.method]}</p>
        </div>
        <div class="flex flex-col items-end">
          <p class="text-sm">- {new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data.items.reduce((a, b) => { return a + ((b.product.price) * (b.quantity)) }, 0))}</p>
          <p class="text-sm text-french-violet">{data.created_at}</p>
        </div>
      </button>
    {/each}

  </div>

</div>