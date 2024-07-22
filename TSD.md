# 📝 Tech Stack Documentation

### Skala Prioritas Berbasis SPICE:

- **Scalability**: Pada waktu dekat ini, aplikasi hanya ditargetkan sekitar wilayah SMKN 4 Malang saja.
- **Performance**: Membutuhkan kecepatan sedang, standar aplikasi e-commerce.
- **Integration**: Pada waktu dekat ini metode pembayaran berupa COD, serta penggunaan WhatsApp untuk chat antara customer-merchant.
- **Cybersecurity**: Prioritas tinggi, karena berurusan dengan keuangan.
- **Essential Features**: Autentikasi akun, pembayaran COD, review produk, CRUD produk, riwayat jual beli, user moderation oleh admin.

### Frontend:

- **Svelte**: Framework JS yang sederhana pada frontend.
  - **Justifikasi**: Svelte memungkinkan pembuatan UI yang responsif dan interaktif dengan performa tinggi karena mengkompilasi kode menjadi vanilla JavaScript, sehingga cocok untuk aplikasi yang skalanya masih kecil namun membutuhkan performa yang baik.

- **SvelteKit**: Memperkuat Svelte dengan berbagai fitur, termasuk server-side rendering.
  - **Justifikasi**: SvelteKit memberikan kemampuan server-side rendering yang meningkatkan performa aplikasi serta SEO, sangat berguna untuk aplikasi e-commerce yang perlu cepat diakses dan ditemukan oleh pengguna.

- **TailwindCSS**: CSS framework untuk styling yang cepat dan konsisten.
  - **Justifikasi**: TailwindCSS memungkinkan pengembangan frontend dengan styling yang cepat dan konsisten, mempercepat pengembangan UI pada front end.

- **Axios**: Library untuk melakukan HTTP requests (berkomunikasi dengan backend).
  - **Justifikasi**: Axios menyediakan antarmuka yang sederhana untuk melakukan HTTP requests, mendukung integrasi yang mudah antara frontend dan backend.

### Backend:

- **PHP**: Bahasa pemrograman utama untuk server-side.
  - **Justifikasi**: PHP adalah bahasa yang telah banyak digunakan dalam pengembangan web, serta mendukung skalabilitas untuk aplikasi kecil hingga menengah.

- **Laravel**: Framework PHP yang digunakan untuk membangun RESTful API.
  - **Justifikasi**: Laravel menawarkan berbagai fitur built-in seperti autentikasi, validasi, dan manajemen database yang mempermudah pengembangan backend yang aman dan terstruktur, sangat sesuai dengan kebutuhan cybersecurity dan fitur esensial aplikasi.

- **PgSQL**: Relational database dengan arsitektur SQL yang terstruktur.
  - **Justifikasi**: PostgreSQL dikenal karena stabilitas dan kinerjanya yang baik dalam menangani transaksi dan data relational, cocok untuk kebutuhan aplikasi e-commerce yang memerlukan manajemen data yang handal dan aman.

### Kesesuaian Tech Stack dengan SPICE:

- **Scalability**: Kombinasi Svelte dan Laravel memungkinkan pengembangan aplikasi yang dapat diskalakan seiring pertumbuhan pengguna, dimulai dari wilayah SMKN 4 Malang dengan potensi ekspansi di masa depan.

- **Performance**: Svelte dan SvelteKit memastikan performa frontend yang tinggi, sementara Laravel dan PostgreSQL di backend menjamin kecepatan dan efisiensi dalam penanganan data dan permintaan pengguna.

- **Integration**: Dengan penggunaan Axios untuk komunikasi HTTP dan Laravel untuk RESTful API, integrasi metode pembayaran COD dapat dilakukan dengan mudah dan efisien.

- **Cybersecurity**: Laravel menyediakan berbagai fitur keamanan seperti proteksi CSRF, enkripsi, dan autentikasi yang kuat, sangat penting untuk aplikasi yang berurusan dengan transaksi keuangan.

- **Essential Features**: Tech stack yang dipilih menyediakan semua alat yang diperlukan untuk implementasi fitur-fitur esensial seperti autentikasi akun, pembayaran COD, review produk, CRUD produk, riwayat jual beli, dan moderasi user oleh admin.

Dengan demikian, tech stack yang dipilih sangat sesuai dengan skala prioritas SPICE yang telah ditetapkan.
