<?php
session_start();

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['admin'])) {
    header("Location: ../login_admin.php");
    exit;
}

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
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }
        .navbar {
            background: #333;
            padding: 10px 0;
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }
        .nav-menu {
            display: flex;
            list-style: none;
        }
        .nav-menu li {
            margin-left: 30px;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav-menu a:hover {
            background: #555;
        }
        .nav-menu a.active {
            background: #555;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 10px;
        }
        hr {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .error-message {
            color: red;
            background: #ffebee;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="../index.php" class="logo">TOKAGADGET</a>
        <ul class="nav-menu">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="produk.php">Produk</a></li>
            <li><a href="kategori.php">Kategori</a></li>
            <li><a href="transaksi.php">Transaksi</a></li>
            <li><a href="karyawan.php">Karyawan</a></li>
            <li><a href="pembayaran.php">Rekening</a></li>
            <li><a href="../logout.php" onclick="return confirm('Keluar dari admin?')">Logout</a></li>
        </ul>
    </div>
</nav>


<div class="container">
    <h2>Dashboard Admin TOKAGADGET</h2>
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
