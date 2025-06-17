<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['faktur'])) {
    echo "Tidak ada data transaksi.";
    exit;
}

$faktur = $_SESSION['faktur'];

// Ambil nama kasir dari database
$id_kasir = $faktur['kasir'];
$kasir_data = mysqli_query($conn, "SELECT nama_karyawan FROM karyawan WHERE id_karyawan = $id_kasir");
$kasir = mysqli_fetch_assoc($kasir_data)['nama_karyawan'] ?? 'Kasir Tidak Dikenal';

// Ambil rekening jika metode pembayaran bukan tunai
$rekeningList = [];
if (strtolower($faktur['metode_pembayaran']) != 'tunai') {
    $rekening_data = mysqli_query($conn, "SELECT * FROM rekening_pembayaran");
    while ($r = mysqli_fetch_assoc($rekening_data)) {
        $rekeningList[] = $r;
    }
}

$tanggal = date('d F Y');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Pembelian - TOKAGADGET</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
            color: #000;
        }

        .header {
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #003366;
        }

        .header p {
            margin: 5px 0;
        }

        .section-title {
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .info-box {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #666;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .note {
            margin-top: 30px;
            padding: 15px;
            background-color: #f5f5f5;
            border-left: 4px solid #003366;
            font-size: 14px;
        }

        .bank-box {
            margin-top: 30px;
            border: 1px solid #ccc;
            padding: 15px;
            background-color: #f9f9f9;
            line-height: 1.6;
        }

        .bank-box h3 {
            margin-top: 0;
            color: #003366;
        }

        .kasir {
            margin-top: 40px;
            text-align: right;
            font-style: italic;
        }

        .back-button {
            margin-top: 30px;
            text-align: center;
        }

        .back-button a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        .back-button a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Faktur Pembelian</h2>
    <p><strong>TOKAGADGET</strong></p>
    <p>+62 896-5009-0645</p>
    <p>tokagadget@gmail.com</p>
</div>

<div class="info-box">
    <p><strong>Tanggal:</strong> <?= $tanggal; ?></p>
    <p><strong>Yth:</strong> <?= htmlspecialchars($faktur['nama_pelanggan']); ?> | <?= htmlspecialchars($faktur['telepon']); ?></p>
    <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($faktur['metode_pembayaran']); ?></p>
</div>

<div class="section-title">Deskripsi</div>

<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Banyaknya</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($faktur['produk'] as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['nama']); ?></td>
            <td><?= $item['qty']; ?></td>
            <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p class="total">Jumlah: Rp <?= number_format($faktur['total'], 0, ',', '.'); ?></p>

<?php if (!empty($rekeningList)): ?>
    <div class="bank-box">
        <h3>Transfer Ke:</h3>
        <?php foreach ($rekeningList as $rek): ?>
            <p>
                <strong><?= htmlspecialchars($rek['bank']); ?></strong><br>
                Atas Nama: <?= htmlspecialchars($rek['nama_penerima']); ?><br>
                No. Rekening: <?= htmlspecialchars($rek['no_rekening']); ?>
            </p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="note">
    <strong>Perhatian:</strong><br>
    Untuk Garansi Handphone <strong>inter</strong> selama 3 Bulan dan garansi untuk <strong>iBox/Sein</strong> selama 1 Tahun. <br>
    <strong>Garansi tidak mencakup refund.</strong>
</div>

<div class="kasir">
    Kasir: <?= htmlspecialchars($kasir); ?>
</div>

<div class="back-button">
    <a href="kasir_dashboard.php">Kembali ke Dashboard Kasir</a>
</div>

</body>
</html>
