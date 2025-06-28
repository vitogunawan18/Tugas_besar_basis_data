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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: none;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .detail-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 350px;
            margin: 0 auto;
        }

        .header-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 15px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        .content-area {
            background: white;
            padding: 20px;
            position: relative;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-card {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
            padding: 18px;
            border-radius: 12px;
            border-left: 4px solid #4facfe;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
        }

        .price-section {
            text-align: center;
            margin: 20px 0;
            padding: 18px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.3);
            position: relative;
            overflow: hidden;
        }

        .price-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: priceShine 2s infinite;
        }

        @keyframes priceShine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .price-label {
            font-size: 14px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 5px;
            font-weight: 500;
        }

        .price {
            color: white;
            font-size: 24px;
            font-weight: 900;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        .warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: none;
            border-left: 4px solid #f39c12;
            color: #856404;
            font-size: 12px;
            padding: 15px;
            margin: 15px 0 0 0;
            border-radius: 8px;
            text-align: left;
            box-shadow: 0 2px 10px rgba(243, 156, 18, 0.2);
            position: relative;
        }

        .warning::before {
            content: '⚠️';
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: 14px;
        }

        .warning-content {
            margin-left: 30px;
        }

        .warning strong {
            color: #d68910;
            font-size: 13px;
            display: block;
            margin-bottom: 5px;
        }

        .icon-smartphone {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            color: rgba(255,255,255,0.7);
        }

        .spec-highlight {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .spec-highlight .category {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 3px;
        }

        .spec-highlight .warranty {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="detail-wrapper">
        <div class="header-gradient">
            <div class="icon-smartphone">📱</div>
            <h1><?= htmlspecialchars($product['nama_produk']) ?></h1>
        </div>

        <div class="content-area">
            <div class="spec-highlight">
                <div class="category"><?= htmlspecialchars($product['nama_kategori']) ?></div>
                <div class="warranty">Garansi <?= htmlspecialchars($product['durasi_garansi']) ?></div>
            </div>

            <div class="price-section">
                <div class="price-label">Harga</div>
                <div class="price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>
            </div>

            <div class="warning">
                <div class="warning-content">
                    <strong>Perhatian Penting!</strong>
                    Untuk Garansi Handphone Inter selama 3 Bulan dan Garansi untuk Handphone iBox/Sein selama 1 Tahun,<br>
                    <b>tidak bisa refund!</b>
                </div>
            </div>
        </div>
    </div>
</body>
</html>