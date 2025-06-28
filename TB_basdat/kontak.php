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
            max-width: 1000px;
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

        .sections-grid {
            display: grid;
            gap: 30px;
        }

        .section {
            position: relative;
        }

        .section h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toko-section h2::before {
            content: '🏪';
            font-size: 20px;
        }

        .team-section h2::before {
            content: '👥';
            font-size: 20px;
        }

        .payment-section h2::before {
            content: '💳';
            font-size: 20px;
        }

        .info-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #4facfe;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 172, 254, 0.05), transparent);
            transition: left 0.5s ease;
        }

        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(79, 172, 254, 0.2);
        }

        .info-box:hover::before {
            left: 100%;
        }

        .info-box p {
            margin: 10px 0;
            color: #555;
            font-size: 15px;
            line-height: 1.6;
        }

        .info-box p:first-child {
            margin-top: 0;
        }

        .info-box p:last-child {
            margin-bottom: 0;
        }

        .info-box strong {
            color: #2c3e50;
            font-weight: 600;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .contact-item {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .staff-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .staff-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .staff-item::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: staffShimmer 3s infinite;
        }

        @keyframes staffShimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .staff-item:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .staff-item h4 {
            margin: 0 0 8px 0;
            color: white;
            font-size: 18px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .staff-item p {
            margin: 0;
            font-size: 14px;
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .payment-grid {
            display: grid;
            gap: 20px;
        }

        .payment-item {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #f39c12;
            box-shadow: 0 8px 25px rgba(243, 156, 18, 0.15);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .payment-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(243, 156, 18, 0.05), transparent);
            transition: left 0.5s ease;
        }

        .payment-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(243, 156, 18, 0.25);
        }

        .payment-item:hover::before {
            left: 100%;
        }

        .bank-name {
            font-size: 18px;
            font-weight: 700;
            color: #d68910;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bank-name::before {
            content: '🏦';
            font-size: 16px;
        }

        .account-details {
            color: #856404;
        }

        .account-details p {
            margin: 5px 0;
            font-size: 14px;
        }

        .account-number {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 16px;
            color: #d68910;
            background: rgba(255,255,255,0.5);
            padding: 8px 12px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 5px;
        }

        .icon-contact {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 20px;
            color: rgba(79, 172, 254, 0.3);
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

            .staff-list {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }

            .contact-info {
                grid-template-columns: 1fr;
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

            .staff-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="content">
        <div class="container">
            <div class="header-section">
                <div class="icon-contact">📞</div>
                <h1>Kontak Kami</h1>
                <div class="subtitle">Hubungi kami untuk informasi lebih lanjut</div>
            </div>

            <div class="content-area">
                <div class="sections-grid">
                    <!-- Info Toko -->
                    <?php if ($toko): ?>
                        <div class="section toko-section">
                            <h2><?= htmlspecialchars($toko['nama_toko']) ?></h2>
                            <div class="info-box">
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <div class="contact-icon">📞</div>
                                        <div>
                                            <strong>Telepon</strong><br>
                                            <?= htmlspecialchars($toko['telepon']) ?>
                                        </div>
                                    </div>
                                    <div class="contact-item">
                                        <div class="contact-icon">📧</div>
                                        <div>
                                            <strong>Email</strong><br>
                                            <?= htmlspecialchars($toko['email']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Info Karyawan -->
                    <?php if (count($karyawan) > 0): ?>
                        <div class="section team-section">
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
                    <?php endif; ?>

                    <!-- Info Pembayaran -->
                    <?php if (count($rekeningList) > 0): ?>
                        <div class="section payment-section">
                            <h2>Metode Pembayaran</h2>
                            <div class="payment-grid">
                                <?php foreach ($rekeningList as $rekening): ?>
                                    <div class="payment-item">
                                        <div class="bank-name"><?= htmlspecialchars($rekening['bank']) ?></div>
                                        <div class="account-details">
                                            <p><strong>Atas Nama:</strong> <?= htmlspecialchars($rekening['nama_penerima']) ?></p>
                                            <p><strong>No. Rekening:</strong></p>
                                            <div class="account-number"><?= htmlspecialchars($rekening['no_rekening']) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
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