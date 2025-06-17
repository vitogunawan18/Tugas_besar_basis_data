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
            font-family: Arial, sans-serif;
            background-color: #f1f3f5;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 50px 20px 30px; /* BERI PADDING BAWAH LEBIH BESAR */
        }

        .footer-wrapper {
            padding-top: 30px; /* AGAR FOOTER SEDIKIT TERANGKAT */
        }


        .container {
            max-width: 800px;
            width: 100%;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #2c3e50;
        }

        .kebijakan-item {
            background-color: #f8f9fa;
            padding: 20px 25px;
            margin-bottom: 20px;
            border-left: 6px solid #007bff;
            border-radius: 8px;
        }

        .kebijakan-item h3 {
            margin-top: 0;
            color: #007bff;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .kebijakan-item p {
            margin: 0;
            color: #333;
            line-height: 1.6;
        }

        @media screen and (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="content">
        <div class="container">
            <h1>Kebijakan Toko</h1>

            <?php if (count($kebijakan) > 0): ?>
                <?php foreach ($kebijakan as $item): ?>
                    <div class="kebijakan-item">
                        <h3><?= htmlspecialchars($item['nama_kebijakan']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($item['deskripsi'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="kebijakan-item">
                <h3>Garansi</h3>
                <p>
                    - Garansi Handphone Inter selama 3 Bulan<br>
                    - Garansi Handphone iBox/Sein selama 1 Tahun<br>
                    - Semua produk bergaransi tidak bisa refund
                </p>
            </div>
        </div>
    </div>

    <div class="footer-wrapper">
    <?php require_once 'template/footer.php'; ?>
</div>

</div>
</body>
</html>
