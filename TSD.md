# 📝 Tech Stack Documentation

### Skala Prioritas Berbasis SPICE:

- Scalability: Pada waktu dekat ini, aplikasinya hanya digunakan di sekitar wilayah SMKN 4 Malang.
- Performance: Membutuhkan kecepatan sedang, standar aplikasi e-commerce.
- Integrasi: Pada waktu dekat ini akan ada metode pembayaran berupa COD, serta penggunaan WhatsApp untuk chat antara customer-merchant.
- Cybersecurity: Prioritas tinggi, karena berurusan dengan keuangan.
- Essential Features: Autentikasi akun, pembayaran COD, review produk, CRUD produk, riwayat jual beli, user moderation oleh admin.

### Frontend:

- Svelte -> Framework JS yang sederhana pada frontend.
- SvelteKit -> Memperkuat Svelte dengan berbagai fitur, termasuk server-side rendering.
- TailwindCSS -> CSS framework untuk styling yang cepat dan konsisten.
- Fetch API -> Native JS API untuk melakukan HTTP requests (berkomunikasi dengan backend).
- Yup -> Library untuk validasi schema, digunakan dalam form handling.

### Backend:

- PHP -> Bahasa pemrograman utama untuk server-side.
- Slim -> Micro-framework PHP yang ringan dan cepat, digunakan untuk membangun RESTful API.
- MySQL -> Relational database management system dengan arsitektur database yang terstruktur.
- Eloquent ORM -> ORM yang digunakan untuk mempermudah interaksi dengan database.
- BCrypt -> Untuk hashing password dan keamanan data pengguna.
- JWT -> Convention untuk token-based authentication.
- PHPUnit -> Untuk unit testing dan memastikan kualitas kode.
- Composer -> Untuk depedency management PHP.

---

## 👤 Autentikasi Akun

- Registrasi Pengguna:
  - Frontend (Svelte, Yup): Menggunakan form registrasi untuk mengumpulkan data pengguna (email, password, dll.). Validasi data dilakukan menggunakan Yup.
  - Backend (Slim, BCrypt, JWT, MySQL): Data form dikirim ke backend menggunakan Fetch API. Backend meng-hash password dengan BCrypt dan menyimpan data pengguna ke MySQL. Setelah berhasil registrasi, backend mengirimkan token JWT ke frontend sebagai bukti autentikasi.

- Login Pengguna:
  - Frontend (Svelte): Menggunakan form login untuk memasukkan email dan password. Validasi data dilakukan menggunakan Yup.
  - Backend (Slim, BCrypt, JWT, MySQL): Data form dikirim ke backend menggunakan Fetch API. Backend memverifikasi email dan password yang di-hash, dan jika valid, mengirimkan token JWT ke frontend. Token ini disimpan di local storage atau cookie untuk autentikasi selanjutnya.

- Proteksi Rute:
  - Frontend (Svelte): Melakukan pengecekan token JWT sebelum mengizinkan akses ke halaman yang dilindungi.
  - Backend (Slim): Memverifikasi token JWT pada setiap request ke endpoint yang dilindungi.

## 🛒 Menggunakan Troli untuk Membuat Pesanan

- CRUD Troli:
  - Frontend (Svelte): Menggunakan state management (Pada kasus ini, Svelte store) untuk menyimpan data troli sementara. Data troli ini dapat disimpan di local storage agar tetap ada jika halaman di-refresh.

- Membuat Pesanan:
  - Frontend (Svelte): Setelah pengguna siap untuk checkout, data troli dikirim ke backend menggunakan Fetch API.
  - Backend (Slim, MySQL): Backend menerima data pesanan, menyimpannya di database MySQL, dan mengirimkan konfirmasi pesanan ke frontend.

## 💰 Pembayaran

- Konfirmasi Pesanan:
  - Frontend (Svelte): Data pesanan dan pilihan pembayaran dikirim ke backend menggunakan Fetch API.
  - Backend (Slim, MySQL): Backend menyimpan informasi pesanan dan metode pembayaran di MySQL. Mengirimkan konfirmasi pesanan ke frontend dan meng-update status pesanan.

⭐ Ulasan Produk oleh User
- CRUD Ulasan:
  - Frontend (Svelte): Untuk read, mengambil ulasan dari backend menggunakan Fetch API dan menampilkan ulasan di halaman produk. Untuk create, update, dan delete menggunakan Fetch API untuk berinteraksi dengan backend.
  - Backend (Slim, MySQL): Endpoint untuk CRUD ulasan produk dari database MySQL dan mengirimkannya ke frontend.

## 📦 Pengelolaan (CRUD) Produk untuk Guru-guru Penjual

- CRUD Produk:
  - Frontend (Svelte): Menggunakan form untuk memasukkan data produk baru atau mengedit data produk yang sudah ada. Validasi data dilakukan menggunakan Yup.
  - Backend (Slim, MySQL): Data produk dikirim ke backend menggunakan Fetch API. Backend memvalidasi dan menyimpan data produk ke database MySQL.

## ⏲ Riwayat Jual Beli

- Menyimpan Riwayat Jual Beli:
  - Backend (Slim, MySQL): Setiap kali pesanan dibuat atau statusnya diperbarui, informasi ini disimpan di database MySQL.

- Menampilkan Riwayat Jual Beli:
  - Frontend (Svelte): Mengambil data riwayat jual beli dari backend menggunakan Fetch API dan menampilkannya di halaman riwayat.
  - Backend (Slim, MySQL): Endpoint untuk mendapatkan data riwayat jual beli dari database MySQL dan mengirimkannya ke frontend.

## 👥 User Moderation oleh Admin Sekolah

- Moderasi Konten:
  - Frontend (Svelte): Admin memiliki akses ke halaman moderasi untuk CRUD konten yang dibuat oleh pengguna.
  - Backend (Slim, MySQL): Endpoint untuk melakukan operasi CRUD pada data pengguna, produk, dan ulasan di database MySQL.

---

# ⚡ Rangkuman

- Frontend (Svelte) menangani antarmuka pengguna dan interaksi. Menggunakan Fetch API untuk berkomunikasi dengan backend.
- Backend (Slim) mengelola logika bisnis dan menyediakan endpoint API untuk operasi CRUD, autentikasi, dan validasi.
- Database (MySQL) menyimpan semua data aplikasi termasuk pengguna, produk, pesanan, ulasan, dan riwayat jual beli.
- Keamanan dijamin dengan penggunaan JWT untuk autentikasi dan BCrypt untuk hashing password.
- Dependency Management dilakukan dengan Composer untuk mengelola libraries PHP yang digunakan.