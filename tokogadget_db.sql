-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Jun 2025 pada 10.54
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tokogadget_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '123456');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama_karyawan` varchar(50) DEFAULT NULL,
  `posisi` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`, `posisi`, `password`) VALUES
(1, 'Dina', 'Kasir', '11111'),
(2, 'Raka Maulidz', 'Kasir', '22222');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_produk`
--

CREATE TABLE `kategori_produk` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `durasi_garansi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_produk`
--

INSERT INTO `kategori_produk` (`id_kategori`, `nama_kategori`, `durasi_garansi`) VALUES
(1, 'inter', '2 minggu'),
(2, 'iBox', '1 Bulan'),
(3, 'Sein', '1 Tahun');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kebijakan`
--

CREATE TABLE `kebijakan` (
  `id_kebijakan` int(11) NOT NULL,
  `nama_kebijakan` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kebijakan`
--

INSERT INTO `kebijakan` (`id_kebijakan`, `nama_kebijakan`, `deskripsi`) VALUES
(1, 'refund', 'tidak bisa refund');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `telepon`) VALUES
(1, 'alex', '048234'),
(2, 'alex', '048234'),
(3, 'vito', '048234'),
(4, 'alex', '048234'),
(5, 'alex', '048234'),
(6, 'alex', '048234'),
(7, 'vito', '048234'),
(8, 'alex', '048234'),
(9, 'alex', '048234'),
(10, 'alex', '048234'),
(11, 'alex', '048234'),
(12, 'alex', '048234'),
(13, 'alex', '048234'),
(14, 'vito', '08989987'),
(15, 'vito', '08989987'),
(16, 'cecep', '089776'),
(17, 'vito', '08989987'),
(18, 'alex', '08989987'),
(19, 'alex', '08989987'),
(20, 'jenab', '0839485794725'),
(21, 'sueb', '08221343414'),
(22, 'juned', '098787897'),
(23, 'sulis', '098457248572'),
(24, 'yono', '084958427'),
(25, 'berginho', '3458938453');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `harga` decimal(15,2) DEFAULT NULL,
  `stok` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `id_kategori`, `harga`, `stok`) VALUES
(1, 'iPhone 11 64GB', 2, 4400000.00, 4),
(2, 'iPhone 12 64GB', 2, 7200000.00, 4),
(4, 'iPhone 13 128GB', 3, 8500000.00, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekening_pembayaran`
--

CREATE TABLE `rekening_pembayaran` (
  `id_rekening` int(11) NOT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rekening_pembayaran`
--

INSERT INTO `rekening_pembayaran` (`id_rekening`, `bank`, `nama_penerima`, `no_rekening`) VALUES
(1, 'Bank BCA', 'Raka Maulidz', '1481028472'),
(2, 'BNI', 'surya', '123232141'),
(4, 'QRIS', 'Toko Gadget', 'qris-0123456789'),
(5, 'Tunai', 'Toko Gadget', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `toko`
--

CREATE TABLE `toko` (
  `id_toko` int(11) NOT NULL,
  `nama_toko` varchar(100) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `toko`
--

INSERT INTO `toko` (`id_toko`, `nama_toko`, `telepon`, `email`) VALUES
(1, 'TOKO GADGET', '+62 896-5009-0645', 'tokogadget@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_rekening` int(11) DEFAULT NULL,
  `id_karyawan` int(11) DEFAULT NULL,
  `id_kebijakan` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `banyaknya` int(11) DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_toko`, `id_pelanggan`, `id_produk`, `id_rekening`, `id_karyawan`, `id_kebijakan`, `tanggal`, `banyaknya`, `total`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2025-06-16', 1, 300000.00),
(2, 1, 2, 1, 1, 1, 1, '2025-06-16', 1, 300000.00),
(3, 1, 3, 1, 1, 2, 1, '2025-06-16', 1, 300000.00),
(4, 1, 4, 1, 1, 2, 1, '2025-06-16', 1, 300000.00),
(5, 1, 5, 1, 1, 2, 1, '2025-06-16', 1, 300000.00),
(6, 1, 6, 1, 1, 2, 1, '2025-06-16', 1, 300000.00),
(7, 1, 7, 1, 1, 2, 1, '2025-06-16', 1, 300000.00),
(8, 1, 8, 1, 1, 2, 1, '2025-06-16', 2, 600000.00),
(9, 1, 9, 1, 1, 2, 1, '2025-06-16', 1, 300000.00),
(10, 1, 10, 1, 1, 2, 1, '2025-06-16', 1, 300000.00),
(11, 1, 11, 1, 1, 1, 1, '2025-06-16', 1, 300000.00),
(12, 1, 12, 2, 1, 1, 1, '2025-06-16', 1, 500000.00),
(13, 1, 13, 1, 1, 1, 1, '2025-06-16', 1, 300000.00),
(14, 1, 14, 1, 1, 1, 1, '2025-06-17', 1, 300000.00),
(15, 1, 15, 1, 1, 1, 1, '2025-06-17', 1, 300000.00),
(16, 1, 16, 2, 1, 1, 1, '2025-06-17', 1, 7200000.00),
(17, 1, 17, 1, 1, 1, 1, '2025-06-23', 1, 4400000.00),
(18, 1, 18, 2, 1, 1, 1, '2025-06-23', 1, 7200000.00),
(19, 1, 19, 2, 1, 1, 1, '2025-06-23', 1, 7200000.00),
(20, 1, 20, 4, 1, 1, 1, '2025-06-28', 1, 8500000.00),
(21, 1, 21, 4, 1, 1, 1, '2025-06-28', 1, 8500000.00),
(22, 1, 22, 4, 4, 2, 1, '2025-06-28', 1, 8500000.00),
(23, 1, 23, 4, 2, 2, 1, '2025-06-28', 1, 8500000.00),
(24, 1, 24, 2, 4, 2, 1, '2025-06-28', 1, 7200000.00),
(25, 1, 25, 4, 5, 2, 1, '2025-06-28', 1, 8500000.00);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indeks untuk tabel `kategori_produk`
--
ALTER TABLE `kategori_produk`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `kebijakan`
--
ALTER TABLE `kebijakan`
  ADD PRIMARY KEY (`id_kebijakan`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `rekening_pembayaran`
--
ALTER TABLE `rekening_pembayaran`
  ADD PRIMARY KEY (`id_rekening`);

--
-- Indeks untuk tabel `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id_toko`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_toko` (`id_toko`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_rekening` (`id_rekening`),
  ADD KEY `id_karyawan` (`id_karyawan`),
  ADD KEY `id_kebijakan` (`id_kebijakan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kategori_produk`
--
ALTER TABLE `kategori_produk`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kebijakan`
--
ALTER TABLE `kebijakan`
  MODIFY `id_kebijakan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `rekening_pembayaran`
--
ALTER TABLE `rekening_pembayaran`
  MODIFY `id_rekening` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `toko`
--
ALTER TABLE `toko`
  MODIFY `id_toko` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_produk` (`id_kategori`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `transaksi_ibfk_4` FOREIGN KEY (`id_rekening`) REFERENCES `rekening_pembayaran` (`id_rekening`),
  ADD CONSTRAINT `transaksi_ibfk_5` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`),
  ADD CONSTRAINT `transaksi_ibfk_6` FOREIGN KEY (`id_kebijakan`) REFERENCES `kebijakan` (`id_kebijakan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
