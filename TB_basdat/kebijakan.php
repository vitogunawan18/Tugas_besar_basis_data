<?php
include 'config/database.php';
require_once 'template/header.php';

$stmt = $pdo->query("SELECT * FROM kebijakan");
$kebijakan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kebijakan Toko - TOKAGADGET</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #f1f3f5 0%, #e9ecef 100%);
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px 50px;
            background: transparent;
        }

        .footer-wrapper {
            padding-top: 30px;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background: white;
            padding: 0;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .header-section {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: headerShimmer 4s infinite;
        }

        @keyframes headerShimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            color: white;
            text-shadow: 0 3px 6px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        .subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
            margin-top: 8px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .content-area {
            padding: 40px 30px;
        }

        .kebijakan-grid {
            display: grid;
            gap: 25px;
            margin-bottom: 30px;
        }

        .kebijakan-item {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #4facfe;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .kebijakan-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 172, 254, 0.05), transparent);
            transition: left 0.5s ease;
        }

        .kebijakan-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(79, 172, 254, 0.2);
        }

        .kebijakan-item:hover::before {
            left: 100%;
        }

        .kebijakan-item h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .kebijakan-item h3::before {
            content: '📋';
            font-size: 18px;
        }

        .kebijakan-item p {
            margin: 0;
            color: #555;
            line-height: 1.7;
            font-size: 15px;
        }

        .garansi-special {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 5px solid #f39c12;
            box-shadow: 0 8px 25px rgba(243, 156, 18, 0.15);
        }

        .garansi-special h3 {
            color: #d68910;
        }

        .garansi-special h3::before {
            content: '🛡️';
        }

        .garansi-special p {
            color: #856404;
        }

        .policy-highlight {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(238, 90, 36, 0.3);
        }

        .policy-highlight h4 {
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 700;
        }

        .policy-highlight p {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
        }

        .icon-policy {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 20px;
            color: rgba(79, 172, 254, 0.3);
        }

        .warranty-list {
            list-style: none;
            padding: 0;
            margin: 10px 0 0 0;
        }

        .warranty-list li {
            padding: 8px 0;
            border-bottom: 1px solid rgba(213, 104, 16, 0.1);
            position: relative;
            padding-left: 25px;
        }

        .warranty-list li:last-child {
            border-bottom: none;
        }

        .warranty-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #27ae60;
            font-weight: bold;
        }

        @media screen and (max-width: 768px) {
            .container {
                margin: 20px;
                border-radius: 15px;
            }

            .header-section {
                padding: 30px 20px;
            }

            .content-area {
                padding: 30px 20px;
            }

            h1 {
                font-size: 24px;
            }

            .kebijakan-item {
                padding: 20px;
            }
        }

        @media screen and (max-width: 480px) {
            .content {
                padding: 20px 10px 30px;
            }

            .container {
                margin: 10px;
            }

            .header-section {
                padding: 25px 15px;
            }

            .content-area {
                padding: 25px 15px;
            }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="content">
        <div class="container">
            <div class="header-section">
                <div class="icon-policy">⚖️</div>
                <h1>Kebijakan Toko</h1>
                <div class="subtitle">Aturan dan ketentuan yang berlaku</div>
            </div>

            <div class="content-area">
                <div class="kebijakan-grid">
                    <?php if (count($kebijakan) > 0): ?>
                        <?php foreach ($kebijakan as $item): ?>
                            <div class="kebijakan-item">
                                <h3><?= htmlspecialchars($item['nama_kebijakan']) ?></h3>
                                <p><?= nl2br(htmlspecialchars($item['deskripsi'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="kebijakan-item garansi-special">
                        <h3>Kebijakan Garansi</h3>
                        <p>Ketentuan garansi yang berlaku untuk semua produk:</p>
                        <ul class="warranty-list">
                            <li>Garansi Handphone Inter selama 3 Bulan</li>
                            <li>Garansi Handphone iBox/Sein selama 1 Tahun</li>
                            <li>Semua produk bergaransi tidak bisa refund</li>
                        </ul>
                    </div>
                </div>

                <div class="policy-highlight">
                    <h4>🔔 Penting untuk Diperhatikan</h4>
                    <p>Harap membaca dan memahami semua kebijakan sebelum melakukan pembelian. Dengan melakukan transaksi, Anda dianggap telah menyetujui semua kebijakan yang berlaku.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-wrapper">
        <?php require_once 'template/footer.php'; ?>
    </div>
</div>
</body>
</html>