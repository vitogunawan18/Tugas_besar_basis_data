<?php
session_start();
require_once '../config/database.php';
require_once '../functions.php';

check_login(); // Pastikan user admin login

$current_page = 'transaksi'; // untuk navbar active
include 'navbar.php';

// Tanggal filter (default kosong)
$tanggal_filter = $_GET['tanggal'] ?? '';

// Ambil data transaksi berdasarkan filter tanggal
if ($tanggal_filter) {
    $stmt = $pdo->prepare("
        SELECT t.*, p.nama_produk, pel.nama_pelanggan 
        FROM transaksi t 
        LEFT JOIN produk p ON t.id_produk = p.id_produk 
        LEFT JOIN pelanggan pel ON t.id_pelanggan = pel.id_pelanggan 
        WHERE DATE(t.tanggal) = ?
        ORDER BY t.tanggal DESC
    ");
    $stmt->execute([$tanggal_filter]);
} else {
    $stmt = $pdo->query("
        SELECT t.*, p.nama_produk, pel.nama_pelanggan 
        FROM transaksi t 
        LEFT JOIN produk p ON t.id_produk = p.id_produk 
        LEFT JOIN pelanggan pel ON t.id_pelanggan = pel.id_pelanggan 
        ORDER BY t.tanggal DESC
    ");
}

$transaksi_result = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lihat Transaksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f7fa; }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            border: none;
            margin-right: 5px;
            width: 75px;
        }

        .btn-primary { background-color: #0b1f40; color: white; }
        .btn-primary:hover { background-color: #1d3557; }

        .btn-danger { background-color: #c0392b; color: white; }
        .btn-danger:hover { background-color: #a93226; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        table, th, td { border: 1px solid #ccc; }
        th {
            background-color: #0b1f40;
            color: white;
            text-align: center;
        }
        td {
            padding: 10px;
            text-align: center;
        }

        .filter label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .no-data {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Lihat Transaksi</h2>

    <form method="GET" class="filter">
        <label for="tanggal">Filter Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" value="<?= htmlspecialchars($tanggal_filter) ?>">
        <button type="submit" class="btn btn-primary">Tampilkan</button>
        <?php if ($tanggal_filter): ?>
            <a href="transaksi.php" style="margin-left: 10px; text-decoration:none; color:#dc3545;">Reset</a>
        <?php endif; ?>
    </form>

    <?php if (count($transaksi_result) > 0): ?>
    <table>
        <tr style="background-color: #eee;">
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
        <?php foreach ($transaksi_result as $row): ?>
        <tr>
            <td><?= $row['id_transaksi']; ?></td>
            <td><?= date('Y-m-d H:i', strtotime($row['tanggal'])) ?></td>
            <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
            <td><?= htmlspecialchars($row['nama_produk']); ?></td>
            <td><?= $row['banyaknya']; ?></td>
            <td>Rp <?= number_format($row['total']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p class="no-data">Tidak ada transaksi pada tanggal ini.</p>
    <?php endif; ?>
</div>

</body>
</html>
