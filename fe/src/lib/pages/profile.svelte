<script lang="ts">
	// Get profile data
	let userData = localStorage.getItem('userData');
	let parsedUserData = {} as UserData;
	if (userData && typeof userData === 'string') {
		parsedUserData = JSON.parse(userData) as UserData;
	}

	// Images
	import userPlaceholder from '$lib/assets/profile.svg';
	import tokoBtn from '$lib/assets/toko.svg';
	import callBtn from '$lib/assets/call.svg';
	import tosBtn from '$lib/assets/tos.svg';
	import securityBtn from '$lib/assets/padlock.svg';

	// Depedencies
	import { ripple } from 'svelte-ripple-action';
	import type UserData from '$lib/types/UserData';
	import { sessionPage } from '$lib/utils/page';
	import { AxiosError } from 'axios';
	import { api, store } from '$lib/utils/api';
	import { onMount } from 'svelte';

	async function getAvatar(id: string) {
		try {
			if (!parsedUserData.avatar_id) return;
			const avatarRes = await api.get(`/user/avatar/${id}`);
			parsedUserData.avatar = avatarRes.data;
		} catch (err) {
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
	}

	onMount(async () => {
		await getAvatar(parsedUserData.id);
	});

	// Determine user's status
	$: isShop = (JSON.parse(localStorage.getItem('userData') || '{}') as UserData).is_shop;

	// Functions
	function openModal(modalId: string) {
		const element = document.getElementById(modalId) as HTMLDialogElement;
		element.showModal();
	}

	async function changePassword(e: SubmitEvent) {
		e.preventDefault();

		try {
			const form = e as SubmitEvent & { target: HTMLFormElement };

			if (!form.target.old_password.value || !form.target.new_password.value) {
				throw new Error('Password lama dan password baru harus diisi');
			}

			if (form.target.new_password.value !== form.target.confirm_password.value) {
				throw new Error('Password baru dan konfirmasi password baru harus sama');
			}

			const formData = new FormData(form.target)
			formData.append(`_method`, 'PUT');

			const res = await api.post(`/auth/${parsedUserData.id}/password`, formData, { headers: { Authorization: `Bearer ${parsedUserData.remember_token}` } });
			alert(res.data.message);
			location.reload();
		} catch (err) {
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
	}

	async function handleImageUpload(e: Event) {
		try {
			const target = e.target as HTMLInputElement;
			const file = target.files?.[0];
			if (!file) {
				throw new Error('File tidak ditemukan');
			}

			const formData = new FormData();
			formData.append('file', file);
			const res = await api.post(`/user/avatar/${parsedUserData.id}`, formData, { headers: { Authorization: `Bearer ${parsedUserData.remember_token}` } });

			localStorage.setItem('userData', JSON.stringify({ ...parsedUserData, avatar_id: res.data.id, avatar: res.data }));

			alert("Avatar berhasil diperbarui");
			location.reload();
		} catch (err) {
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
	}
</script>

<dialog id="password_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<h3 class="text-lg font-bold">Ubah Password</h3>

		<form on:submit={changePassword}>
			<div class="mb-4">
				<label for="password" class="text-sm font-bold mb-2">Password Lama</label>
				<input
					name="old_password"
					type="password"
					id="old_password"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="******************"
          required
				/>
			</div>
			<div class="mb-4">
				<label for="password" class="text-sm font-bold mb-2">Password Baru</label>
				<input
					name="new_password"
					type="password"
					id="new_password"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="******************"
          required
				/>
			</div>
			<div class="mb-6">
				<label for="password" class="text-sm font-bold mb-2">Konfirmasi Password</label>
				<input
					name="confirm_password"
					type="password"
					id="confirm_password"
					class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
					placeholder="******************"
          required
				/>
			</div>
			<button
				type="submit"
				use:ripple
				class="bg-french-violet text-white font-bold
				py-2 px-4 rounded focus:outline-none focus:shadow-outline">Ubah Password</button
			>
		</form>

		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
		</form>
	</div>
</dialog>

<dialog id="tos_modal" class="daisy-modal">
	<div class="daisy-modal-box">
		<h3 class="text-lg font-bold">Ketentuan Layanan</h3>

		Pembaruan Terakhir : [26 Juli 2024]

		Selamat datang di [Nama Olshop Anda]! Kami sangat senang Anda memilih untuk berbelanja dengan kami. Dengan mengakses atau menggunakan layanan kami, Anda menyetujui untuk terikat dengan Ketentuan Layanan berikut. Mohon baca dengan cermat.
		
		1. Penerimaan Syarat
		Dengan mengakses atau menggunakan situs web dan layanan kami, Anda menyetujui untuk mematuhi dan terikat oleh Ketentuan Layanan ini. Jika Anda tidak menyetujui salah satu dari syarat ini, Anda tidak boleh menggunakan layanan kami.
		
		2. Modifikasi Ketentuan
		Kami berhak untuk mengubah atau memperbarui Ketentuan Layanan ini kapan saja. Setiap perubahan akan diberlakukan segera setelah dipublikasikan di situs web kami. Penggunaan berkelanjutan Anda terhadap layanan kami setelah perubahan tersebut merupakan penerimaan Anda terhadap perubahan tersebut.
		
		3. Kualifikasi Pengguna
		Layanan kami hanya tersedia bagi individu yang berusia minimal 12 tahun. Dengan menggunakan layanan kami, Anda menyatakan dan menjamin bahwa Anda berusia setidaknya 12 tahun.
		
		4. Akun Pengguna
		Untuk mengakses beberapa fitur di situs web kami, Anda mungkin perlu membuat akun pengguna. Anda bertanggung jawab untuk menjaga kerahasiaan informasi akun Anda, termasuk kata sandi Anda, dan untuk semua aktivitas yang terjadi di bawah akun Anda. Anda setuju untuk segera memberi tahu kami tentang penggunaan yang tidak sah dari akun Anda atau pelanggaran keamanan lainnya.

		<form method="dialog">
			<button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
		</form>
	</div>
</dialog>

<div class={`lg:container overflow-x-hidden flex justify-center lg:items-center flex-col w-screen h-fit p-4`}>
	<!-- Profile -->
	<div
		class={`flex justify-start items-center w-full lg:w-[32%] h-fit p-4 bg-white mb-8 border border-grey rounded-lg shadow-xl`}
	>
		<label use:ripple for="avatar" class="hover:cursor-pointer rounded-full me-4">
			<input type="file" name="avatar" id="avatar" on:change={handleImageUpload} class="hidden">
			<img src={parsedUserData.avatar ? store + parsedUserData.avatar.filename : userPlaceholder} alt="Profile" class="size-12 rounded-full" />
		</label>
		<div class="flex flex-col items-start">
			<p class="text-md font-semibold">
				{parsedUserData.first_name + ' ' + (parsedUserData.last_name || '')} (<span class="text-blue">@{parsedUserData.username}</span>)
			</p>
			<p class="text-sm opacity-50">{parsedUserData.email}</p>
		</div>
	</div>

	<!-- Options -->
	<div
		class={`flex justify-center flex-col w-full lg:w-[32%] h-fit p-4 bg-white mb-8 border border-grey rounded-lg shadow-xl`}
	>
			{#each [
				{ icon: tokoBtn, label: 'Toko Anda', condition: isShop, callback: () => $sessionPage = 'toko' },
				{ icon: securityBtn, label: 'Ubah Password', condition: true, callback: () => openModal('password_modal') },
				{ icon: callBtn, label: 'Kontak Admin', condition: true, callback: () => { window.open('https://wa.me/number'); return; } },
				{ icon: tosBtn, label: 'Ketentuan Layanan', condition: true, callback: () => openModal('tos_modal') },
			] as item}

			{#if item.condition}
				<button
					use:ripple
					on:click={(e) => {
						e.preventDefault();
						item.callback();
					}}
					class={`flex justify-start items-center w-full h-fit p-2 bg-white my-2 border border-grey rounded-lg`}
				>
					<img src={item.icon} alt={item.label} class="size-6 me-2" />
					<p class="text-[16px] font-semibold">{item.label}</p>
				</button>
			{/if}
		{/each}

		<br />

		<button
			on:click={() => {
				if (confirm('Apakah Anda yakin ingin keluar?')) {
					localStorage.clear();
					sessionStorage.clear();
					location.reload();
				}
			}}
			use:ripple
			class={`flex justify-center items-center w-full h-fit p-2 bg-french-violet text-white my-2 border border-grey rounded-lg active:scale-90 transition duration-300`}
		>
			Keluar
		</button>
	</div>
</div>
