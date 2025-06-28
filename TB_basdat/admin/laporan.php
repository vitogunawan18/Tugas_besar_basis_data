<?php
session_start();
require_once '../config/database.php';
require_once '../functions.php';

check_login(); // Pastikan admin sudah login

$current_page = 'laporan'; // Untuk highlight menu aktif
include 'navbar.php';

function runQuery($pdo, $query) {
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatRupiah($angka) {
    return "Rp." . number_format($angka, 0, ',', '.');
}

$laporan = $_GET['laporan'] ?? '';
$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';
$data = [];
$title = '';
$query = '';

switch ($laporan) {
    case 'penjualan_harian':
        $title = 'Laporan Penjualan per Hari';
        $query = "SELECT tanggal, COUNT(id_transaksi) AS jumlah_transaksi, SUM(total) AS total_penjualan FROM transaksi";
        if ($start && $end) {
            $query .= " WHERE tanggal BETWEEN '$start' AND '$end'";
        }
        $query .= " GROUP BY tanggal ORDER BY tanggal";
        $data = runQuery($pdo, $query);
        break;
    case 'produk_terlaris':
        $title = 'Produk Terlaris';
        $query = "SELECT p.nama_produk, COUNT(t.id_produk) AS jumlah_terjual, SUM(t.total) AS total_pendapatan 
                  FROM transaksi t 
                  JOIN produk p ON t.id_produk = p.id_produk 
                  GROUP BY t.id_produk, p.nama_produk 
                  ORDER BY jumlah_terjual DESC";
        $data = runQuery($pdo, $query);
        break;
    case 'transaksi_pelanggan':
        $title = 'Total Transaksi Setiap Pelanggan';
        $query = "SELECT p.nama_pelanggan, COUNT(t.id_transaksi) AS jumlah_transaksi, SUM(t.total) AS total_belanja 
                  FROM transaksi t 
                  JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
                  GROUP BY t.id_pelanggan, p.nama_pelanggan 
                  ORDER BY total_belanja DESC";
        $data = runQuery($pdo, $query);
        break;
    case 'penjualan_karyawan':
        $title = 'Rekap Penjualan per Karyawan';
        $query = "SELECT k.nama_karyawan, COUNT(t.id_transaksi) AS jumlah_transaksi, SUM(t.total) AS total_penjualan 
                  FROM transaksi t 
                  JOIN karyawan k ON t.id_karyawan = k.id_karyawan 
                  GROUP BY k.nama_karyawan 
                  ORDER BY total_penjualan DESC";
        $data = runQuery($pdo, $query);
        break;
}

// Export to Excel
if (isset($_GET['export']) && $data) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$laporan.xls");
    echo "<table border='1'>";
    echo "<tr>";
    foreach (array_keys($data[0]) as $col) {
        echo "<th>" . ucfirst(str_replace('_', ' ', $col)) . "</th>";
    }
    echo "</tr>";
    foreach ($data as $row) {
        echo "<tr>";
        foreach ($row as $key => $cell) {
            echo "<td>";
            if (preg_match('/(total|pendapatan|belanja|penjualan)/i', $key)) {
                echo formatRupiah($cell);
            } else {
                echo htmlspecialchars($cell);
            }
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan | TOKAGADGET</title>
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

        select, input[type="date"], button {
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
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
            text-decoration: none;
        }

        .btn-primary { background-color: #0b1f40; color: white; }
        .btn-primary:hover { background-color: #1d3557; }

        .export-link {
            margin-top: 15px;
            display: inline-block;
            color: #0b1f40;
            text-decoration: underline;
            font-weight: bold;
        }

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
            padding: 10px;
        }

        td {
            padding: 10px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .no-data {
            color: #999;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 style="margin-bottom: 20px;">Laporan Penjualan</h2>
    
    <form method="get">
        <div class="form-group">
            <label for="laporan">Pilih Laporan:</label>
            <select name="laporan" id="laporan" onchange="this.form.submit()">
                <option value="">--Pilih Jenis Laporan--</option>
                <option value="penjualan_harian" <?= $laporan == 'penjualan_harian' ? 'selected' : '' ?>>Penjualan per Hari</option>
                <option value="produk_terlaris" <?= $laporan == 'produk_terlaris' ? 'selected' : '' ?>>Produk Terlaris</option>
                <option value="transaksi_pelanggan" <?= $laporan == 'transaksi_pelanggan' ? 'selected' : '' ?>>Transaksi per Pelanggan</option>
                <option value="penjualan_karyawan" <?= $laporan == 'penjualan_karyawan' ? 'selected' : '' ?>>Penjualan per Karyawan</option>
            </select>
        </div>

        <?php if ($laporan == 'penjualan_harian'): ?>
            <div class="form-group">
                <label>Filter Tanggal:</label>
                <input type="date" name="start" value="<?= htmlspecialchars($start) ?>">
                <input type="date" name="end" value="<?= htmlspecialchars($end) ?>">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        <?php endif; ?>
    </form>

    <?php if ($laporan && $data): ?>
        <h3><?= $title ?></h3>
        <a class="export-link" href="?laporan=<?= $laporan ?>&start=<?= $start ?>&end=<?= $end ?>&export=1">⬇ Export ke Excel</a>
        <table>
            <tr>
                <?php foreach (array_keys($data[0]) as $col): ?>
                    <th><?= ucfirst(str_replace('_', ' ', $col)) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <?php foreach ($row as $key => $cell): ?>
                        <td>
                            <?php 
                            if (preg_match('/(total|pendapatan|belanja|penjualan)/i', $key)) {
                                echo formatRupiah($cell);
                            } else {
                                echo htmlspecialchars($cell);
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($laporan): ?>
        <p class="no-data">Data tidak ditemukan untuk laporan yang dipilih.</p>
    <?php endif; ?>
</div>

</body>
</html>
