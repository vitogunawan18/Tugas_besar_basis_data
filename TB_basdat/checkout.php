<?php
session_start();
include 'config/database.php';
require_once 'template/header.php';

if (!isset($_SESSION['kasir']) || empty($_SESSION['cart'])) {
    header("Location: kasir_dashboard.php");
    exit;
}

// Ambil ID kasir dari session
$id_kasir = $_SESSION['kasir'];

// Ambil nama kasir dari tabel karyawan
$queryKasir = mysqli_query($conn, "SELECT nama_karyawan FROM karyawan WHERE id_karyawan = $id_kasir");
$dataKasir = mysqli_fetch_assoc($queryKasir);
$nama_kasir = $dataKasir['nama_karyawan'] ?? 'Kasir';

// Ambil data rekening pembayaran dari database
$queryRekening = mysqli_query($conn, "SELECT * FROM rekening_pembayaran ORDER BY bank ASC");
$rekening_list = [];
while ($rekening = mysqli_fetch_assoc($queryRekening)) {
    $rekening_list[] = $rekening;
}

// Ambil data produk dari cart
$cart_items = [];
$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id");
    $produk = mysqli_fetch_assoc($result);
    if ($produk) {
        $produk['qty'] = $qty;
        $produk['subtotal'] = $produk['harga'] * $qty;
        $cart_items[] = $produk;
        $total += $produk['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - POS Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .order-summary {
            background: #f9f9f9;
            border-left: 5px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .order-summary ul {
            list-style: none;
            padding: 0;
        }

        .order-summary li {
            margin-bottom: 8px;
            font-size: 16px;
        }

        .order-summary .total {
            font-weight: bold;
            font-size: 18px;
            color: #e53935;
            margin-top: 10px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        .payment-method {
            margin-top: 15px;
        }

        .payment-method label {
            display: block;
            margin-bottom: 10px;
            font-weight: normal;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .payment-method label:hover {
            background: #e9ecef;
        }

        .payment-method input[type="radio"] {
            margin-right: 8px;
        }

        .bank-details {
            font-size: 12px;
            color: #666;
            margin-left: 20px;
            font-style: italic;
        }

        .btn-submit {
            margin-top: 25px;
            width: 100%;
            background-color: #007bff;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
        
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-back {
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Pembelian</h2>

    <div class="order-summary">
        <h3>Ringkasan Pesanan:</h3>
        <ul>
            <?php foreach ($cart_items as $item): ?>
                <li><?= htmlspecialchars($item['nama_produk']); ?> (x<?= $item['qty'] ?>) - Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></li>
            <?php endforeach; ?>
        </ul>
        <div class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></div>
    </div>

    <form action="proses_checkout.php" method="post">
        <label for="nama">Nama Pelanggan:</label>
        <input type="text" name="nama" id="nama" required>

        <label for="telepon">No. Telepon:</label>
        <input type="text" name="telepon" id="telepon" required>

        <label for="metode_pembayaran">Metode Pembayaran:</label>
        <div class="payment-method">
            <?php if (!empty($rekening_list)): ?>
                <?php foreach ($rekening_list as $rekening): ?>
                    <label>
                        <input type="radio" name="metode_pembayaran" value="<?= htmlspecialchars($rekening['bank']); ?>" data-rekening="<?= $rekening['id_rekening']; ?>" required> 
                        Transfer <?= htmlspecialchars($rekening['bank']); ?>
                        <div class="bank-details">
                            <?= htmlspecialchars($rekening['nama_penerima']); ?> - <?= htmlspecialchars($rekening['no_rekening']); ?>
                        </div>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <label>
                <input type="radio" name="metode_pembayaran" value="QRIS" data-rekening="0"> QRIS
            </label>
            
            <label>
                <input type="radio" name="metode_pembayaran" value="Tunai" data-rekening="0"> Tunai
            </label>
        </div>

        <!-- Hidden input untuk menyimpan ID rekening yang dipilih -->
        <input type="hidden" name="id_rekening" id="id_rekening" value="">

        <div class="button-group">
            <button type="submit" class="btn-submit">Konfirmasi Pembelian</button>
            <a href="kasir_dashboard.php" class="btn-submit btn-back">← Kembali ke Dashboard Kasir</a>
        </div>
    </form>
</div>

<script>
// JavaScript untuk mengatur id_rekening berdasarkan pilihan metode pembayaran
document.addEventListener('DOMContentLoaded', function() {
    const paymentRadios = document.querySelectorAll('input[name="metode_pembayaran"]');
    const idRekeningInput = document.getElementById('id_rekening');
    
    paymentRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const rekeningId = this.getAttribute('data-rekening');
                idRekeningInput.value = rekeningId;
            }
        });
    });
});
</script>

</body>
</html>