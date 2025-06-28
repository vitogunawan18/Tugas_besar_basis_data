<?php
session_start();

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin'])) {
    header("Location: ../login_admin.php");
    exit;
}

$current_page = 'dashboard'; // untuk navbar aktif
include 'navbar.php'; // navbar modular

// Koneksi database dengan error handling
$pdo = null;
$database_error = false;

try {
    if (file_exists('../config/database.php')) {
        require_once '../config/database.php';
    } else {
        throw new Exception("File config/database.php tidak ditemukan");
    }

    if (!isset($pdo) || $pdo === null) {
        throw new Exception("Koneksi database gagal");
    }

} catch(Exception $e) {
    $database_error = true;
    $error_message = $e->getMessage();
}

// Inisialisasi default values
$total_produk = 0;
$total_kategori = 0;
$total_transaksi = 0;

if (!$database_error && $pdo !== null) {
    try {
        $total_produk = $pdo->query("SELECT COUNT(*) as total FROM produk")->fetch()['total'];
    } catch(PDOException $e) {
        $total_produk = 0;
    }

    try {
        $total_kategori = $pdo->query("SELECT COUNT(*) as total FROM kategori_produk")->fetch()['total'];
    } catch(PDOException $e) {
        $total_kategori = 0;
    }

    try {
        $total_transaksi = $pdo->query("SELECT COUNT(*) as total FROM transaksi")->fetch()['total'];
    } catch(PDOException $e) {
        $total_transaksi = 0;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 25px;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2 {
            margin-bottom: 12px;
            color: #001F3F;
        }

        .stats {
            display: flex;
            gap: 30px;
            margin: 20px 0;
        }
        .stat-box {
            flex: 1;
            background: #f1f6fb;
            padding: 20px;
            border-left: 5px solid #001F3F;
            border-radius: 5px;
        }
        .stat-box h3 {
            margin: 0;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background: #001F3F;
            color: white;
        }

        .error-message {
            color: #721c24;
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Dashboard Admin TOKOGADGET</h2>
    <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['admin']); ?></strong>!</p>

    <hr>

    <?php if ($database_error): ?>
        <div class="error-message">
            <strong>Peringatan:</strong> <?php echo htmlspecialchars($error_message ?? 'Koneksi database bermasalah'); ?>
        </div>
    <?php endif; ?>

    <h3>Statistik</h3>
    <p>Total Produk: <?php echo $total_produk; ?></p>
    <p>Total Kategori: <?php echo $total_kategori; ?></p>
    <p>Total Transaksi: <?php echo $total_transaksi; ?></p>

    <hr>

    <h3>Transaksi Terbaru</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Total</th>
        </tr>
        <?php
        if (!$database_error && $pdo !== null) {
            try {
                $stmt = $pdo->query("
                    SELECT t.id_transaksi, t.tanggal, t.total, p.nama_produk 
                    FROM transaksi t 
                    JOIN produk p ON t.id_produk = p.id_produk 
                    ORDER BY t.tanggal DESC 
                    LIMIT 5
                ");
                $has_data = false;
                while($row = $stmt->fetch()):
                    $has_data = true;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_transaksi']); ?></td>
                    <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                    <td>Rp <?php echo number_format($row['total']); ?></td>
                </tr>
                <?php 
                endwhile;

                if (!$has_data) {
                    echo "<tr><td colspan='4' style='text-align: center;'>Belum ada data transaksi</td></tr>";
                }
            } catch(PDOException $e) {
                echo "<tr><td colspan='4' style='text-align: center;'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align: center;'>Database tidak tersedia</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
