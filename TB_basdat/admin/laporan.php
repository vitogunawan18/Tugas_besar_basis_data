<?php
session_start();
require_once '../config/database.php';
require_once '../functions.php';

check_login();

$current_page = 'laporan';
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
$preset = $_GET['preset'] ?? '';
$data = [];
$title = '';
$query = '';

if ($preset) {
    switch ($preset) {
        case 'today':
            $start = $end = date('Y-m-d');
            break;
        case '7days':
            $start = date('Y-m-d', strtotime('-6 days'));
            $end = date('Y-m-d');
            break;
        case 'this_month':
            $start = date('Y-m-01');
            $end = date('Y-m-t');
            break;
        case 'this_year':
            $start = date('Y-01-01');
            $end = date('Y-12-31');
            break;
    }
}

switch ($laporan) {
    case 'penjualan_harian':
        $title = 'Laporan Penjualan per Hari';
        $query = "SELECT tanggal, COUNT(id_transaksi) AS jumlah_transaksi, SUM(total) AS total_penjualan
                  FROM transaksi";
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
                  JOIN produk p ON t.id_produk = p.id_produk";
        if ($start && $end) {
            $query .= " WHERE t.tanggal BETWEEN '$start' AND '$end'";
        }
        $query .= " GROUP BY t.id_produk, p.nama_produk ORDER BY jumlah_terjual DESC";
        $data = runQuery($pdo, $query);
        break;

    case 'transaksi_pelanggan':
        $title = 'Total Transaksi per Pelanggan (Gabung Nama & Telepon)';
        $query = "SELECT CONCAT(p.nama_pelanggan, ' (', p.telepon, ')') AS pelanggan,
                         COUNT(t.id_transaksi) AS jumlah_transaksi,
                         SUM(t.total) AS total_belanja
                  FROM transaksi t
                  JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan";
        if ($start && $end) {
            $query .= " WHERE t.tanggal BETWEEN '$start' AND '$end'";
        }
        $query .= " GROUP BY pelanggan ORDER BY total_belanja DESC";
        $data = runQuery($pdo, $query);
        break;

    case 'penjualan_karyawan':
        $title = 'Rekap Penjualan per Karyawan';
        $query = "SELECT k.nama_karyawan, COUNT(t.id_transaksi) AS jumlah_transaksi, SUM(t.total) AS total_penjualan
                  FROM transaksi t
                  JOIN karyawan k ON t.id_karyawan = k.id_karyawan";
        if ($start && $end) {
            $query .= " WHERE t.tanggal BETWEEN '$start' AND '$end'";
        }
        $query .= " GROUP BY k.nama_karyawan ORDER BY total_penjualan DESC";
        $data = runQuery($pdo, $query);
        break;

    case 'data_transaksi_lengkap':
        $title = 'Data Transaksi Lengkap';
        $query = "SELECT 
                    t.id_transaksi,
                    t.tanggal,
                    CONCAT(p.nama_pelanggan, ' (', p.telepon, ')') AS pelanggan,
                    pr.nama_produk,
                    t.banyaknya,
                    t.total,
                    k.nama_karyawan,
                    r.bank,
                    kj.nama_kebijakan
                  FROM transaksi t
                  JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                  JOIN produk pr ON t.id_produk = pr.id_produk
                  JOIN karyawan k ON t.id_karyawan = k.id_karyawan
                  JOIN rekening_pembayaran r ON t.id_rekening = r.id_rekening
                  JOIN kebijakan kj ON t.id_kebijakan = kj.id_kebijakan";
        if ($start && $end) {
            $query .= " WHERE t.tanggal BETWEEN '$start' AND '$end'";
        }
        $query .= " ORDER BY t.tanggal DESC";
        $data = runQuery($pdo, $query);
        break;
}

// Export Excel
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; background: #f4f7fa; margin: 0; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        select, input[type="date"], button { padding: 10px; margin-right: 10px; border-radius: 5px; border: 1px solid #ccc; }
        .btn-primary { background: #0b1f40; color: white; border: none; }
        .export-link { margin-top: 15px; display: inline-block; color: #0b1f40; font-weight: bold; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #0b1f40; color: white; }
        canvas { margin-top: 30px; }
        .no-data { margin-top: 20px; font-weight: bold; color: #333; }
    </style>
</head>
<body>
<div class="container">
    <h2>Laporan Penjualan</h2>
    <form method="get">
        <div class="form-group">
            <label>Laporan:</label>
            <select name="laporan" onchange="this.form.submit()">
                <option value="">--Pilih Laporan--</option>
                <option value="penjualan_harian" <?= $laporan == 'penjualan_harian' ? 'selected' : '' ?>>Penjualan per Hari</option>
                <option value="produk_terlaris" <?= $laporan == 'produk_terlaris' ? 'selected' : '' ?>>Produk Terlaris</option>
                <option value="transaksi_pelanggan" <?= $laporan == 'transaksi_pelanggan' ? 'selected' : '' ?>>Transaksi per Pelanggan</option>
                <option value="penjualan_karyawan" <?= $laporan == 'penjualan_karyawan' ? 'selected' : '' ?>>Penjualan per Karyawan</option>
                <option value="data_transaksi_lengkap" <?= $laporan == 'data_transaksi_lengkap' ? 'selected' : '' ?>>Data Transaksi Lengkap</option>
            </select>
            <select name="preset" onchange="this.form.submit()">
                <option value="">--Presets--</option>
                <option value="today" <?= $preset == 'today' ? 'selected' : '' ?>>Hari Ini</option>
                <option value="7days" <?= $preset == '7days' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                <option value="this_month" <?= $preset == 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
                <option value="this_year" <?= $preset == 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
            </select>
        </div>
        <?php if ($laporan): ?>
        <div class="form-group">
            <label>Tanggal Awal:</label>
            <input type="date" name="start" value="<?= htmlspecialchars($start) ?>">
            <label>Tanggal Akhir:</label>
            <input type="date" name="end" value="<?= htmlspecialchars($end) ?>">
            <button class="btn-primary" type="submit">Tampilkan</button>
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
                        <td><?= preg_match('/(total|pendapatan|belanja|penjualan)/i', $key) ? formatRupiah($cell) : htmlspecialchars($cell) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if ($laporan != 'data_transaksi_lengkap'): ?>
        <canvas id="chart" width="900" height="400"></canvas>
        <script>
        const ctx = document.getElementById('chart');
        const labels = <?= json_encode(array_column($data, array_keys($data[0])[0])) ?>;
        const values = <?= json_encode(array_column($data, array_keys($data[0])[count($data[0]) - 1])) ?>;
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '<?= $title ?>',
                    data: values,
                    backgroundColor: '#0b1f40',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        </script>
        <?php endif; ?>
    <?php elseif ($laporan): ?>
        <p class="no-data">Data tidak ditemukan untuk laporan ini.</p>
    <?php endif; ?>
</div>
</body>
</html>
