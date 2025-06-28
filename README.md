```markdown
```
# 💻 Tugas Besar Basis Data – Aplikasi Kasir Toko Gadget

Aplikasi Point of Sale (POS) berbasis web untuk mengelola transaksi penjualan, manajemen produk, pelanggan, karyawan, dan laporan secara lengkap di Toko Gadget. Proyek ini dikembangkan dalam rangka memenuhi tugas besar mata kuliah Basis Data.
```
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
---

Berikut adalah versi yang sudah **diperbaiki dan rapi** untuk bagian **📂 Struktur Folder** di dokumen kamu. Kamu tinggal salin dan tempel ke README atau dokumentasimu:

```markdown
## 📂 Struktur Folder

```
'''
```

config/
├── database.php              # Koneksi database MySQL

template/
├── header.php                # Template header
├── footer.php                # Template footer (jika ada)

checkout.php                  # Form checkout transaksi
proses\_checkout.php           # Logic penyimpanan transaksi
laporan.php                   # Laporan transaksi
kasir\_dashboard.php           # Dashboard kasir

produk.php                    # Manajemen produk
pelanggan.php                 # Manajemen pelanggan
karyawan.php                  # Manajemen karyawan
rekening\_pembayaran.php       # Manajemen rekening pembayaran
kebijakan.php                 # Manajemen kebijakan refund

login.php                     # Halaman login
logout.php                    # Logout user
```
```


## 🛠️ Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/vitogunawan18/Tugas_besar_basis_data.git
````

2. **Buat database**
   Masuk ke MySQL / phpMyAdmin:

   ```sql
   CREATE DATABASE tokogadget;
   ```

3. **Import file SQL**

   * Buka phpMyAdmin.
   * Pilih database `tokogadget`.
   * Import file `tokogadget_db.sql` yang ada di project.

4. **Atur koneksi database**
   Edit file `config/database.php`:

   ```php
   $conn = mysqli_connect("localhost", "root", "", "tokogadget");
   ```

   Sesuaikan user/password MySQL Anda jika berbeda.

5. **Siapkan server lokal**

   * Letakkan folder di `htdocs` (XAMPP) atau `www` (Laragon).
   * Jalankan Apache + MySQL.
   * Akses:

     ```
     http://localhost/Tugas_besar_basis_data/login.php
     ```

---

## 💻 Cara Penggunaan

### ✨ Login

1. Buka halaman login.
2. Masukkan akun kasir/admin.

### ✨ Transaksi Penjualan

1. Tambahkan produk ke keranjang.
2. Klik checkout.
3. Isi nama pelanggan dan nomor telepon.
4. Pilih metode pembayaran:

   * BCA
   * BNI
   * QRIS
   * Tunai
5. Konfirmasi pembelian.

> ✅ Transaksi akan otomatis disimpan sesuai metode pembayaran yang dipilih.

### ✨ Laporan Penjualan

1. Masuk halaman laporan.
2. Pilih jenis laporan:

   * Penjualan per hari
   * Produk terlaris
   * Transaksi per pelanggan
   * Penjualan per karyawan
   * Data transaksi lengkap
3. Filter tanggal atau preset periode.
4. Klik export untuk mengunduh laporan Excel.

---



## 👨‍💻 Anggota Kelompok

| Nama                 | NIM      |
| -------------------- | -------- |
| **Vito Gunawan**     | 23016149 |
| **Salma Aulia Nisa** | 2306143  |
| **Siti Rahmawati**   | 2306146  |

---

## 🌐 Repository

🔗 [GitHub Repository](https://github.com/vitogunawan18/Tugas_besar_basis_data)

---

## ❤️ Lisensi

Proyek ini dibuat untuk keperluan akademik. Silakan modifikasi sesuai kebutuhan tugas.

---
