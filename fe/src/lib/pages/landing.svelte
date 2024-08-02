<script lang="ts">
	// Images
	import logo from '$lib/assets/logo.svg';
	import jumbotron from '$lib/assets/jumbotron.png';
	import furniture from '$lib/assets/furniture.png';
	import food from '$lib/assets/food.png';
	import drink from '$lib/assets/drink.png';
	import fashion from '$lib/assets/fashion.png';
	import tags from '$lib/assets/tags.svg';
	import shield from '$lib/assets/shield.svg';
	import headset from '$lib/assets/headset.svg';

	// Depedencies
	import { ripple } from 'svelte-ripple-action';
	import { initializeApp } from 'firebase/app';
	import { GoogleAuthProvider, getAuth, signInWithPopup } from 'firebase/auth';
	import { onMount } from 'svelte';
	import { api } from '$lib/utils/api';
	import { generateFromEmail } from 'unique-username-generator';
	import type UserData from '$lib/types/UserData';
	import { authStatus } from '$lib/utils/guard';
	import { sessionPage } from '$lib/utils/page';
	import { AxiosError } from 'axios';

	// Reactive state
	$: isRegister = true;
	$: $authStatus;
	$: showPassword = false;

	// * Firebase auth setup
	onMount(() => {
		initializeApp({
			apiKey: 'AIzaSyBcMUc86y62vAYKVCfBxu2kGNZ49tyxFPk',
			authDomain: 'dwp-smkn4-malang.firebaseapp.com',
			projectId: 'dwp-smkn4-malang',
			storageBucket: 'dwp-smkn4-malang.appspot.com',
			messagingSenderId: '322314388040',
			appId: '1:322314388040:web:12281034bb207be67c4e7d',
			measurementId: 'G-DD0K8VNJ12'
		});
	});

	// Functions
	function showAuthModal() {
		const authModal = document.getElementById('auth_modal') as HTMLDialogElement;
		authModal.showModal();
	}

	async function authWithEmail(e: SubmitEvent) {
		e.preventDefault();

		try {
			const form = e as SubmitEvent & { target: HTMLFormElement }; // Type assertion to ensure e is a SubmitEvent

			if (!form.target.email.value || !form.target.password.value) {
				throw new Error('Email dan password harus diisi');
			}
			const formData = new FormData(form.target);
			formData.set(
				'username',
				generateFromEmail(form.target.email.value) + Math.floor(Math.random() * 9999).toString()
			);
			if (!isRegister) {
				formData.append(`_method`, 'PUT');
			}

			const authRes = await api.post(`/auth`, formData);

			const userData: UserData = authRes.data;
			if (!userData) {
				throw new Error('Terjadi kesalahan. Mohon coba lagi nanti');
			}

			localStorage.setItem('userData', JSON.stringify(userData));
			location.reload();
		} catch (err) {
			console.log(err);
			if (err instanceof AxiosError) {
				alert(err.response?.data.message);
			} else {
				alert(err);
			}
		}
	}

	async function authWithGoogle() {
		const provider = new GoogleAuthProvider();
		const auth = getAuth();
		provider.setCustomParameters({ prompt: 'select_account' });

		try {
			const frbRes = await signInWithPopup(auth, provider);

			if (!frbRes.user.email) {
				throw new Error('Email tidak ditemukan');
			}

			// Format the correct first and last name
			let fullName = frbRes.user.displayName;
			let first_name = '';
			let last_name = null;
			if (fullName) {
				const nameParts = fullName.split(' ');
				first_name = nameParts[0];
				if (nameParts.length > 1) {
					last_name = nameParts[nameParts.length - 1];
				}
			}

			const credentials = {
				username:
					generateFromEmail(frbRes.user.email) + Math.floor(Math.random() * 9999).toString(),
				email: frbRes.user.email,
				password: frbRes.user.uid,
				first_name,
				last_name
			};

			const authRes = await api.post('/auth/oauth', credentials);
			const userData: UserData = authRes.data;
			if (!userData) {
				throw new Error('Terjadi kesalahan. Mohon coba lagi nanti');
			}

			localStorage.setItem('userData', JSON.stringify(userData));
			location.reload();
		} catch (err) {
			console.log(err);
			if (err instanceof AxiosError) {
				alert(err.response?.data.message);
			} else {
				alert(err);
			}
		}
	}
</script>

<!-- <button class="daisy-btn" onclick="auth_modal.showModal()">open modal</button> -->
<dialog id="auth_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<h3 class="text-lg font-bold">
			{isRegister ? 'Daftar' : 'Masuk'} /
			<button on:click={() => (isRegister = !isRegister)} class="text-french-violet"
				>{isRegister ? 'Masuk' : 'Daftar'}</button
			>
		</h3>

		<hr class="my-4" />

		<!-- Login with email and password -->
		<form on:submit={authWithEmail}>
			<div class="mb-4">
				<label for="email" class="text-sm font-bold mb-2">Email</label>
				<input
					name="email"
					type="email"
					id="email"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="Masukkan Email"
					required
				/>
			</div>
			<div class={`mb-4 ${!isRegister && `hidden`}`}>
				<label for="first_name" class="text-sm font-bold mb-2">Nama Awal</label>
				<input
					name="first_name"
					type="text"
					id="first_name"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="Nama Awal"
					value="Nama Awal"
					required
				/>
			</div>
			<div class={`mb-4 ${!isRegister && `hidden`}`}>
				<label for="last_name" class="text-sm font-bold mb-2">Nama Awal</label>
				<input
					name="last_name"
					type="text"
					id="last_name"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="Nama Akhir"
					value="Nama Akhir"
				/>
			</div>
			<div class="mb-6">
				<label for="password" class="text-sm font-bold mb-2">Password</label>
				<input
					name="password"
					type={showPassword ? `text` : `password`}
					id="password"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="******************"
					required
				/>
				<div class="flex items-center my-2">
					<label for="showPassword" class="text-sm font-bold me-2">Show Password</label>
					<input
						name="showPassword"
						type="checkbox"
						on:click={() => (showPassword = !showPassword)}
					/>
				</div>
			</div>
			<div class="flex items-center justify-start space-x-4">
				<button
					type="submit"
					use:ripple
					class="bg-french-violet text-white font-bold
py-2 px-4 rounded focus:outline-none focus:shadow-outline">{isRegister ? 'Daftar' : 'Masuk'}</button
				>
				<button
					type="reset"
					use:ripple
					class="bg-white text-black border border-grey font-bold
py-2 px-4 rounded focus:outline-none focus:shadow-outline">Reset</button
				>
				<!-- TODO: -->
				<!-- <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">Forgot Password?</a> -->
			</div>
		</form>

		<hr class="my-4" />

		<!-- Login with Google -->
		<button
			use:ripple
			type="button"
			on:click={authWithGoogle}
			class="flex items-center bg-white shadow-lg p-4 rounded-lg"
		>
			<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"
				><path
					d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
					fill="#4285F4"
				/><path
					d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
					fill="#34A853"
				/><path
					d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
					fill="#FBBC05"
				/><path
					d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
					fill="#EA4335"
				/><path d="M1 1h22v22H1z" fill="none" /></svg
			>
			<span class="ms-2">Lanjutkan dengan Google</span>
		</button>

		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
		</form>
	</div>
</dialog>

<header class="bg-background">
	<div class="lg:container mx-auto py-4 flex justify-between items-center">
		<div class="flex items-center p-4">
			<button on:click={() => location.reload()} type="button">
				<img src={logo} alt="Logo" class="size-12 me-8" />
			</button>
			<nav class="hidden lg:block">
				<ul class="flex space-x-4">
					<!-- <li><a href="#hero" class="">Beranda</a></li> -->
					<li><a href="#produk_kami" class="">Produk Kami</a></li>
					<li><a href="#keunggulan" class="">Keunggulan</a></li>
				</ul>
			</nav>
		</div>
		{#if !$authStatus}
			<div class={`flex items-center p-4 space-x-4`}>
				<button
					on:click={() => {
						isRegister = false;
						showAuthModal();
					}}
					use:ripple
					class="bg-white px-6 py-2 font-semibold rounded-md border border-grey">Masuk</button
				>
				<button
					on:click={() => {
						isRegister = true;
						showAuthModal();
					}}
					use:ripple
					class="bg-french-violet text-white font-semibold px-6 py-2 rounded-md">Daftar</button
				>
			</div>
		{:else}
			<button
				on:click={() => {
					setTimeout(() => {
						sessionPage.set('beranda');
					}, 250);
				}}
				use:ripple
				class="bg-french-violet text-white font-semibold px-6 py-2 me-4 rounded-md">Beranda</button
			>
		{/if}
	</div>
</header>

<!-- Hero -->
<section id="hero" class="bg-background py-20">
	<div class="lg:container w-[90vw] lg:w-[50vw] mx-auto text-center">
		<img src={logo} alt="Logo" class="mx-auto size-32 mb-6" />

		<h1 class="text-h6 font-bold mb-6">
			Temukan Kebutuhan Rumah Tangga dan Banyak Lagi <br /> Hanya di
			<span class="text-french-violet">DWP Grafika Market</span>
		</h1>

		<p class="mb-6">
			Rasakan sensasi berbelanja tanpa rasa khawatir. Nikmati kumpulan produk kami yang berkualitas
			tinggi dengan harga terjangkau.
		</p>

		{#if !$authStatus}
			<button
				on:click={showAuthModal}
				use:ripple
				class={`m-2 bg-french-violet text-white font-semibold px-12 py-4 mx-1 rounded-md`}
				>Gabung Sekarang</button
			>
		{:else}
			<button
				on:click={() => {
					setTimeout(() => {
						sessionPage.set('beranda');
					}, 250);
				}}
				use:ripple
				class={`m-2 bg-french-violet text-white font-semibold px-12 py-4 mx-1 rounded-md`}
				>Pergi ke Beranda</button
			>
		{/if}
		<button
			use:ripple
			on:click={() =>
				document.getElementById('produk_kami')?.scrollIntoView({ behavior: 'smooth' })}
			class={`m-2 bg-white px-12 py-4 font-semibold rounded-md border border-grey mx-1`}
			>Lihat Lebih Lanjut</button
		>
	</div>
</section>

<!-- Jumbotron -->
<img src={jumbotron} alt="Jumbo" class="w-full" />

<!-- Produk Kami -->
<section id="produk_kami" class="py-12 px-4 bg-white">
	<div class="lg:container mx-auto">
		<div class="mb-6 text-center lg:text-start">
			<h1 class="text-h6 font-bold">Produk Kami</h1>
			<p class="opacity-50">Apa saja produk yang kami tawarkan?</p>
		</div>
		<div class="grid grid-cols-4 gap-4">
			<div class="col-span-4 lg:col-span-2 h-fit">
				<img src={furniture} alt="Furniture" class="h-auto w-full rounded-2xl mb-2" />
				<h2 class="text-lg font-bold">Perabotan</h2>
				<p class="text-sm opacity-50">Alat Rumah Tangga</p>
			</div>
			<div class="col-span-2 lg:col-span-1 h-fit">
				<img src={food} alt="Food" class="h-auto w-full rounded-2xl mb-2" />
				<h2 class="text-lg font-bold">Makanan</h2>
				<p class="text-sm opacity-50">Lezat dan Bergizi</p>
			</div>
			<div class="col-span-2 lg:col-span-1 h-fit">
				<img src={drink} alt="Drink" class="h-auto w-full rounded-2xl mb-2" />
				<h2 class="text-lg font-bold">Minuman</h2>
				<p class="text-sm opacity-50">Legakan Dahaga</p>
			</div>
		</div>
		<img src={fashion} alt="Fashion" class="h-auto w-full rounded-2xl mb-2 mt-8" />
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
			<div
				class="col-span-3 lg:col-span-1 bg-white p-6 rounded-md shadow-md flex justify-between items-center"
			>
				<div class="flex flex-col justify-center items-center lg:block w-full">
					<img src={tags} alt="Tags" class="size-12 lg:hidden" />
					<br class="lg:hidden" />
					<h3 class="text-xl font-bold mb-2">Harga Terjangkau</h3>
					<p>Nikmati penawaran serba hemat</p>
				</div>
				<img src={tags} alt="Tags" class="size-12 hidden lg:block" />
			</div>
			<div
				class="col-span-3 lg:col-span-1 bg-white p-6 rounded-md shadow-md flex justify-between items-center"
			>
				<div class="flex flex-col justify-center items-center lg:block w-full">
					<img src={shield} alt="Shield" class="size-12 lg:hidden" />
					<br class="lg:hidden" />
					<h3 class="text-xl font-bold mb-2">Keamanan Transaksi</h3>
					<p>Pembayaran terjamin aman</p>
				</div>
				<img src={shield} alt="Shield" class="size-12 hidden lg:block" />
			</div>
			<div
				class="col-span-3 lg:col-span-1 bg-white p-6 rounded-md shadow-md flex justify-between items-center"
			>
				<div class="flex flex-col justify-center items-center lg:block w-full">
					<img src={headset} alt="Headset" class="size-12 lg:hidden" />
					<br class="lg:hidden" />
					<h3 class="text-xl font-bold mb-2">Customer Support</h3>
					<p>Pembayaran terjamin aman</p>
				</div>
				<img src={headset} alt="Headset" class="size-12 hidden lg:block" />
			</div>
		</div>
	</div>
</section>

<!-- CTA -->
<section class="py-12 p-4 bg-french-violet text-white">
	<div class="lg:container mx-auto flex flex-col items-center lg:items-start">
		<h2 class="text-3xl font-bold mb-2">Tunggu Apa Lagi?</h2>
		<p>Ayo mulai berbelanja di DWP Grafika Market!</p>
		<br />
		{#if !$authStatus}
			<button on:click={showAuthModal} use:ripple class="bg-white text-black px-8 py-3 rounded-md"
				>Gabung Sekarang</button
			>
		{:else}
			<button
				on:click={() => {
					setTimeout(() => {
						sessionPage.set('beranda');
					}, 250);
				}}
				use:ripple
				class="bg-white text-black px-8 py-3 rounded-md">Belanja Sekarang</button
			>
		{/if}
		<!-- <img src="" alt="CTA"> -->
	</div>
</section>

<footer class="bg-white py-8">
	<div class="lg:container mx-auto flex justify-center items-center">
		<div class="flex items-center">
			<img src={logo} alt="Logo" class="size-8 me-3" />
			<p>&copy; 2024 DWP Grafika Market. <br class="lg:hidden" /> Seluruh Hak Cipta.</p>
		</div>
	</div>
</footer>
