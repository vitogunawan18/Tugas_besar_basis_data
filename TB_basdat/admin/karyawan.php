<?php
session_start();
require_once '../config/database.php';
require_once '../functions.php';

check_login();

// Handle form aksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $nama = $_POST['nama_karyawan'];
    $posisi = $_POST['posisi'];

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO karyawan (nama_karyawan, posisi) VALUES (?, ?)");
        $stmt->execute([$nama, $posisi]);
    } elseif ($action === 'edit') {
        $id = $_POST['id_karyawan'];
        $stmt = $pdo->prepare("UPDATE karyawan SET nama_karyawan = ?, posisi = ? WHERE id_karyawan = ?");
        $stmt->execute([$nama, $posisi, $id]);
    } elseif ($action === 'delete') {
        $id = $_POST['id_karyawan'];
        $stmt = $pdo->prepare("DELETE FROM karyawan WHERE id_karyawan = ?");
        $stmt->execute([$id]);
    }

    header("Location: karyawan.php");
    exit;
}

// Data edit jika ada
$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM karyawan WHERE id_karyawan = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}

// Ambil semua karyawan
$karyawan_result = $pdo->query("SELECT * FROM karyawan")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Karyawan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; }
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

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
        }

        .btn {
            padding: 8px 12px;
            border-radius: 3px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            border: none;
            margin-right: 5px;
            width: 75px; /* Samakan ukuran tombol */
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }

        .inline {
            display: inline-block;
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
    <h2><?= $edit_data ? 'Edit Karyawan' : 'Tambah Karyawan' ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?= $edit_data ? 'edit' : 'add' ?>">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id_karyawan" value="<?= $edit_data['id_karyawan'] ?>">
        <?php endif; ?>

        <label>Nama Karyawan:</label>
        <input type="text" name="nama_karyawan" required value="<?= htmlspecialchars($edit_data['nama_karyawan'] ?? '') ?>">

        <label>Posisi:</label>
        <input type="text" name="posisi" required value="<?= htmlspecialchars($edit_data['posisi'] ?? '') ?>">

        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Update' : 'Tambah' ?></button>
        <?php if ($edit_data): ?>
            <a href="karyawan.php" class="btn btn-danger">Batal</a>
        <?php endif; ?>
    </form>

    <h3>Daftar Karyawan</h3>
    <table>
        <tr style="background-color: #eee;">
            <th>ID</th>
            <th>Nama</th>
            <th>Posisi</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($karyawan_result as $row): ?>
        <tr>
            <td><?= $row['id_karyawan'] ?></td>
            <td><?= htmlspecialchars($row['nama_karyawan']) ?></td>
            <td><?= htmlspecialchars($row['posisi']) ?></td>
            <td>
                <a href="karyawan.php?edit=<?= $row['id_karyawan'] ?>" class="btn btn-primary">Edit</a>
                <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_karyawan" value="<?= $row['id_karyawan'] ?>">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
