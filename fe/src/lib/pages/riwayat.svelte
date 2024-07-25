<script lang="ts">
  import { transactionData } from "$lib/types/Sample";
	import type TransactionData from "$lib/types/TransactionData";

  const datas: TransactionData[] = [transactionData, transactionData, transactionData, transactionData, transactionData];

  import transactionImg from "$lib/assets/transaction.svg";
	import { ripple } from "svelte-ripple-action";

  const methods: { [key: string]: string } = {
    "cod": "COD"
  }
</script>

<div class={`container overflow-x-hidden flex justify-content flex-col w-screen h-fit p-4`}>
  <h2 class={`text-xl ms-2 font-bold`}>
    Transaksi Terakhir
  </h2>

  {#each datas as data}
    <button use:ripple class={`flex justify-between items-center w-full h-fit px-3 py-2 bg-white my-2 border border-grey rounded-lg`}>
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