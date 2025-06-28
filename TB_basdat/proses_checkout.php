<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['kasir']) || empty($_SESSION['cart'])) {
    header("Location: kasir_dashboard.php");
    exit;
}

$nama_pelanggan = $_POST['nama'] ?? '';
$telepon = $_POST['telepon'] ?? '';
$id_rekening = (int)($_POST['id_rekening'] ?? 0);  // PERBAIKAN: Ambil id_rekening yang benar
$id_kasir = $_SESSION['kasir'];

if (empty($nama_pelanggan) || empty($telepon) || $id_rekening <= 0) {
    die("Data pelanggan atau metode pembayaran tidak lengkap.");
}

// Ambil nama bank untuk session faktur
$queryBank = mysqli_query($conn, "SELECT bank FROM rekening_pembayaran WHERE id_rekening = $id_rekening");
$dataBank = mysqli_fetch_assoc($queryBank);
$nama_bank = $dataBank['bank'] ?? 'Unknown';

// Simpan pelanggan
$nama_pelanggan_escaped = mysqli_real_escape_string($conn, $nama_pelanggan);
$telepon_escaped = mysqli_real_escape_string($conn, $telepon);

mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan, telepon) VALUES ('$nama_pelanggan_escaped', '$telepon_escaped')");
$id_pelanggan = mysqli_insert_id($conn);

// Siapkan data faktur untuk session
$_SESSION['faktur'] = [
    'nama_pelanggan' => $nama_pelanggan,
    'telepon' => $telepon,
    'metode_pembayaran' => $nama_bank,  // PERBAIKAN: Gunakan nama bank yang sesuai
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

        // PERBAIKAN: Simpan dengan id_rekening yang benar
        mysqli_query($conn, "INSERT INTO transaksi (
            id_toko, id_pelanggan, id_produk, id_rekening, id_karyawan, id_kebijakan, tanggal, banyaknya, total
        ) VALUES (
            1, $id_pelanggan, $id_produk, $id_rekening, $id_kasir, 1, CURDATE(), $qty, $subtotal
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
?>