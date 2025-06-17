<?php
include 'config/database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, k.nama_kategori, k.durasi_garansi FROM produk p 
                       JOIN kategori_produk k ON p.id_kategori = k.id_kategori 
                       WHERE p.id_produk = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<p style='padding: 20px; color: red;'>Produk tidak ditemukan.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: none;
            margin: 0;
            padding: 0;
        }

        .detail-wrapper {
            padding: 10px;
            text-align: center;
        }

        h1 {
            margin: 10px 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .info {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .price {
            color: red;
            font-size: 22px;
            font-weight: bold;
            margin: 20px 0 10px;
        }

        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            font-size: 14px;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="detail-wrapper">
        <h1><?= htmlspecialchars($product['nama_produk']) ?></h1>

        <div class="info">
            <span class="label">Kategori:</span> <?= htmlspecialchars($product['nama_kategori']) ?>
        </div>

        <div class="info">
            <span class="label">Garansi:</span> <?= htmlspecialchars($product['durasi_garansi']) ?>
        </div>

        <div class="price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>

        <div class="warning">
            <strong>Perhatian:</strong><br>
            Untuk Garansi Handphone Inter selama 3 Bulan dan Garansi untuk Handphone iBox/Sein selama 1 Tahun,<br>
            <b>tidak bisa refund!</b>
        </div>
    </div>
</body>
</html>
