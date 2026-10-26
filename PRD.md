# PRD — Kedai Kopi Timor: Web Pemesanan dari Meja

> Dokumen ini adalah spesifikasi untuk AI agent. Baca seluruh dokumen sebelum mulai bekerja.

## 1. Latar Belakang

Kedai Kopi Timor adalah cafe kecil dengan ±10 meja. Saat ini pelanggan memesan secara lisan ke kasir. Akibatnya antrean menumpuk di jam ramai, pesanan sering salah catat, dan pelanggan tidak tahu apakah pesanannya sudah diproses.

## 2. Tujuan

1. Pelanggan memesan sendiri dari meja lewat HP dengan memindai QR code, tanpa install aplikasi dan tanpa login.
2. Staf mengelola menu dan memproses pesanan dari satu panel admin.
3. Pelanggan melihat status pesanannya berubah otomatis.

## 3. Pengguna

| Pengguna | Kebutuhan |
|---|---|
| **Pelanggan** | Melihat menu, memesan dari meja, memantau status pesanan |
| **Staf (admin)** | Mengelola kategori, menu, dan meja; memproses pesanan |

## 4. Fitur

### 4.1 Pemesanan (halaman pelanggan, publik, tanpa login)

- **F1 — Halaman meja** `/meja/{kode}`: menampilkan nama meja. Kode meja yang tidak terdaftar menampilkan halaman 404.
- **F2 — Daftar menu**: menu yang tersedia dikelompokkan per kategori, berisi nama, deskripsi singkat, dan harga.
- **F3 — Keranjang**: pelanggan menambah item, mengubah jumlah per item (tombol +/− atau mengetik angka), melihat subtotal dan total. Pelanggan mengisi nama panggilan, lalu menekan **Pesan**. Setelah terkirim, pelanggan diarahkan ke halaman status pesanan.
- **F4 — Status pesanan** `/pesanan/{nomor}`: menampilkan nomor pesanan, meja, item, total, dan status. Status diperbarui otomatis tanpa reload manual (polling setiap 5 detik sudah cukup).

### 4.2 Panel Admin (wajib login)

- **F5 — Kelola kategori**: tambah, ubah, hapus.
- **F6 — Kelola menu**: nama, kategori, deskripsi, harga, status tersedia/habis. Menu yang habis tidak tampil di halaman pelanggan.
- **F7 — Kelola meja**: nama meja dan kode unik untuk URL.
- **F8 — Daftar pesanan**: pesanan terbaru di atas, bisa difilter per status, menampilkan nomor, meja, nama pelanggan, item, dan total.
- **F9 — Ubah status pesanan**: alur `baru → diproses → siap → selesai`, plus `dibatalkan`.
- **F10 — Dashboard ringkas**: jumlah pesanan hari ini, jumlah pesanan berstatus `baru`, dan total penjualan hari ini (pesanan `selesai`).

## 5. Di Luar Cakupan

- Pembayaran online (pembayaran tetap di kasir)
- Login atau akun pelanggan
- Pembuatan atau cetak QR code di dalam aplikasi (QR disiapkan terpisah)
- Upload foto menu
- Multi-cabang, multi-role, laporan keuangan
- Notifikasi WhatsApp atau email
- Aplikasi mobile native

## 6. Batasan Teknis untuk Agent

### 6.1 Stack

| Komponen | Pilihan |
|---|---|
| Framework | Laravel (versi stabil terbaru) |
| Panel admin | Filament |
| Halaman pelanggan | Blade + Livewire |
| Styling | Tailwind CSS |
| Database | SQLite |

### 6.2 Aturan Kerja

1. **Buat rencana kerja terlebih dahulu** dan tunggu persetujuan sebelum menulis kode.
2. Buat proyek langsung di folder ini, bukan di subfolder baru.
3. Jangan menginstal package selain yang tercantum di 6.1 tanpa persetujuan.
4. Seluruh antarmuka memakai **Bahasa Indonesia**. Harga dalam format Rupiah (`Rp25.000`).
5. Halaman pelanggan wajib nyaman dipakai di layar HP.
6. Gunakan konvensi standar Laravel: migration, model, dan relasi Eloquent.
7. Saat menjalankan server untuk pengujian, gunakan host `0.0.0.0` agar bisa dibuka dari HP di jaringan yang sama.
8. Jangan mengubah file di luar folder proyek.

### 6.3 Data Contoh (Seeder)

- 1 akun admin: `admin@kedai.test` / `password`
- 3 kategori: **Kopi**, **Non-Kopi**, **Makanan**
- ±10 menu, antara lain: Kopi Flores Bajawa, Kopi Susu Gula Aren, Es Teh Serai, Jagung Bose, Se'i Sapi Sambal Lu'at, Pisang Goreng Keju
- 5 meja: `Meja 1` s.d. `Meja 5`, masing-masing dengan kode unik
- 10 pesanan contoh hari ini dengan nama pelanggan dan status yang beragam

## 7. Kriteria Penerimaan

- [ ] `php artisan migrate --seed` berjalan tanpa error pada database kosong
- [ ] Admin dapat login dan mengelola kategori, menu, dan meja
- [ ] Membuka `/meja/{kode}` dari HP menampilkan menu yang tersedia saja
- [ ] Pelanggan dapat memesan ≥2 item berbeda dan pesanan muncul di panel admin
- [ ] Perubahan status oleh admin terlihat di halaman status pelanggan tanpa reload manual
- [ ] Kode meja yang tidak valid menampilkan halaman 404
- [ ] Total harga dihitung di server, bukan dipercaya dari input browser
