# 💻 Tugas Besar Basis Data – Aplikasi Kasir Toko Gadget

Aplikasi Point of Sale (POS) berbasis web untuk mengelola transaksi penjualan, manajemen produk, pelanggan, karyawan, dan laporan secara lengkap di Toko Gadget. Proyek ini dikembangkan dalam rangka memenuhi tugas besar mata kuliah Basis Data.

---

## 🎯 Fitur Utama

✅ **Otentikasi Pengguna**
- Login kasir/admin
- Logout

✅ **Dashboard Kasir**
- Menampilkan ringkasan transaksi
- Akses cepat ke modul transaksi

✅ **Manajemen Data**
- Produk
- Pelanggan
- Karyawan
- Rekening Pembayaran
- Kebijakan Refund

✅ **Transaksi Penjualan**
- Keranjang belanja
- Checkout dengan pilihan metode pembayaran (BCA, BNI, QRIS, Tunai)
- Simpan transaksi secara otomatis

✅ **Laporan Penjualan**
- Penjualan per Hari
- Produk Terlaris
- Transaksi per Pelanggan
- Penjualan per Karyawan
- Data Transaksi Lengkap
- Export laporan ke Excel

✅ **Tampilan Modern**
- Desain responsif dan profesional
- UI ramah pengguna

---

## 📂 Struktur Folder

config/
database.php # Koneksi database MySQL
template/
header.php # Template header
footer.php # Template footer
checkout.php # Form checkout transaksi
proses_checkout.php # Logic penyimpanan transaksi
laporan.php # Laporan transaksi
kasir_dashboard.php # Dashboard kasir
produk.php # Manajemen produk
pelanggan.php # Manajemen pelanggan
karyawan.php # Manajemen karyawan
rekening_pembayaran.php # Manajemen rekening pembayaran
kebijakan.php # Manajemen kebijakan refund
login.php # Halaman login
logout.php # Logout user

yaml
Copy
Edit

---

## 🛠️ Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/vitogunawan18/Tugas_besar_basis_data.git
Buat database
Masuk ke MySQL atau phpMyAdmin:

sql
Copy
Edit
CREATE DATABASE tokogadget;
Import file SQL

Buka phpMyAdmin.

Pilih database tokogadget.

Import file tokogadget_db.sql yang ada di project.

Atur koneksi database
Edit file config/database.php:

php
Copy
Edit
$conn = mysqli_connect("localhost", "root", "", "tokogadget");
Sesuaikan user/password MySQL jika berbeda.

Siapkan server lokal

Letakkan folder di htdocs (XAMPP) atau www (Laragon).

Jalankan Apache dan MySQL.

Akses:

bash
Copy
Edit
http://localhost/Tugas_besar_basis_data/login.php
💻 Cara Penggunaan
✨ Login
Buka halaman login.

Masukkan akun kasir/admin.

✨ Transaksi Penjualan
Tambahkan produk ke keranjang.

Klik checkout.

Isi nama pelanggan dan nomor telepon.

Pilih metode pembayaran:

BCA

BNI

QRIS

Tunai

Konfirmasi pembelian.

✅ Transaksi akan otomatis disimpan sesuai metode pembayaran yang dipilih.

✨ Laporan Penjualan
Masuk halaman laporan.

Pilih jenis laporan:

Penjualan per hari

Produk terlaris

Transaksi per pelanggan

Penjualan per karyawan

Data transaksi lengkap

Filter tanggal atau preset periode.

Klik export untuk mengunduh laporan Excel.

👥 Anggota Kelompok
Nama	NIM
Vito Gunawan	23016149
Salma Aulia Nisa	2306142
Siti Rahmawati	2306151

🌐 Repository
🔗 GitHub Repository

❤️ Lisensi
Proyek ini dibuat untuk keperluan akademik. Silakan modifikasi sesuai kebutuhan tugas.
