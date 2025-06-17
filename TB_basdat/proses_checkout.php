<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['kasir']) || empty($_SESSION['cart'])) {
    header("Location: kasir_dashboard.php");
    exit;
}

$nama_pelanggan = $_POST['nama'] ?? '';
$telepon = $_POST['telepon'] ?? '';
$metode_pembayaran = $_POST['metode_pembayaran'] ?? 'Tunai';
$id_kasir = $_SESSION['kasir'];

if (empty($nama_pelanggan) || empty($telepon) || empty($metode_pembayaran)) {
    die("Data pelanggan atau metode pembayaran tidak lengkap.");
}

// Simpan pelanggan
mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan, telepon) VALUES ('$nama_pelanggan', '$telepon')");
$id_pelanggan = mysqli_insert_id($conn);

// Siapkan data faktur untuk session
$_SESSION['faktur'] = [
    'nama_pelanggan' => $nama_pelanggan,
    'telepon' => $telepon,
    'metode_pembayaran' => $metode_pembayaran,
    'produk' => [],
    'total' => 0,
    'kasir' => $id_kasir
];

$total_bayar = 0;

foreach ($_SESSION['cart'] as $id_produk => $qty) {
    $queryProduk = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id_produk");
    $produk = mysqli_fetch_assoc($queryProduk);

    if ($produk) {
        // Cek stok terlebih dahulu
        if ($produk['stok'] < $qty) {
            die("Stok untuk produk '{$produk['nama_produk']}' tidak mencukupi. Stok tersedia: {$produk['stok']}, diminta: $qty");
        }

        $subtotal = $produk['harga'] * $qty;

        $_SESSION['faktur']['produk'][] = [
            'nama' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'qty' => $qty,
            'subtotal' => $subtotal
        ];

        $total_bayar += $subtotal;

        // Simpan transaksi ke DB
        mysqli_query($conn, "INSERT INTO transaksi (
            id_toko, id_pelanggan, id_produk, id_rekening, id_karyawan, id_kebijakan, tanggal, banyaknya, total
        ) VALUES (
            1, $id_pelanggan, $id_produk, 1, $id_kasir, 1, CURDATE(), $qty, $subtotal
        )");

        // Kurangi stok produk
        mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk = $id_produk");
    }
}

$_SESSION['faktur']['total'] = $total_bayar;

// Bersihkan keranjang
unset($_SESSION['cart']);

// Redirect ke halaman sukses
header("Location: checkout_success.php");
exit;
