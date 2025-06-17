<?php
include 'config/database.php';
require_once 'template/header.php';

// Ambil data toko
$stmt = $pdo->query("SELECT * FROM toko LIMIT 1");
$toko = $stmt->fetch();

// Ambil data karyawan
$stmt = $pdo->query("SELECT * FROM karyawan");
$karyawan = $stmt->fetchAll();

// Ambil data rekening pembayaran
$stmt = $pdo->query("SELECT * FROM rekening_pembayaran");
$rekeningList = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontak Kami - TOKAGADGET</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; padding: 0;
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
            padding: 50px 20px 30px;
        }

        .container {
            max-width: 900px;
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

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #007bff;
        }

        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .staff-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .staff-item {
            background: #e9ecef;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }

        .staff-item h4 {
            margin-bottom: 5px;
            color: #333;
        }

        .staff-item p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        @media (max-width: 600px) {
            .container { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="content">
        <div class="container">
            <h1>Informasi Kontak</h1>

            <!-- Info Toko -->
            <?php if ($toko): ?>
                <div class="section">
                    <h2><?= htmlspecialchars($toko['nama_toko']) ?></h2>
                    <div class="info-box">
                        <p><strong>Telepon:</strong> <?= htmlspecialchars($toko['telepon']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($toko['email']) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Info Karyawan -->
            <div class="section">
                <h2>Tim Kami</h2>
                <div class="staff-list">
                    <?php foreach ($karyawan as $staff): ?>
                        <div class="staff-item">
                            <h4><?= htmlspecialchars($staff['nama_karyawan']) ?></h4>
                            <p><?= htmlspecialchars($staff['posisi']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Info Pembayaran -->
            <?php if (count($rekeningList) > 0): ?>
                <div class="section">
                    <h2>Pembayaran</h2>
                    <div class="info-box">
                        <?php foreach ($rekeningList as $rekening): ?>
                            <p>
                                <strong><?= htmlspecialchars($rekening['bank']) ?></strong><br>
                                Atas Nama: <?= htmlspecialchars($rekening['nama_penerima']) ?><br>
                                No. Rekening: <?= htmlspecialchars($rekening['no_rekening']) ?>
                            </p>
                            <hr style="border: 0; border-top: 1px solid #ccc;">
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer-wrapper" style="padding-top: 30px;">
        <?php require_once 'template/footer.php'; ?>
    </div>
</div>
</body>
</html>
