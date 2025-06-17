<?php
session_start();
require_once '../config/database.php';
require_once '../functions.php';

check_login(); // Pastikan admin sudah login

// Proses tambah/edit/hapus
if ($_POST) {
    if ($_POST['action'] == 'add') {
        $stmt = $pdo->prepare("INSERT INTO kategori_produk (nama_kategori, durasi_garansi) VALUES (?, ?)");
        $stmt->execute([$_POST['nama_kategori'], $_POST['durasi_garansi']]);
    } elseif ($_POST['action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE kategori_produk SET nama_kategori = ?, durasi_garansi = ? WHERE id_kategori = ?");
        $stmt->execute([$_POST['nama_kategori'], $_POST['durasi_garansi'], $_POST['id_kategori']]);
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM kategori_produk WHERE id_kategori = ?");
        $stmt->execute([$_POST['id_kategori']]);
    }

    header("Location: kategori.php");
    exit;
}

// Data untuk form edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM kategori_produk WHERE id_kategori = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}

// Ambil semua data kategori
$kategori_result = $pdo->query("SELECT * FROM kategori_produk")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Kategori</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
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
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #0056b3;
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
            width: 75px;
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

        a.button {
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 10px;
            text-decoration: none;
            text-align: center;
            border-radius: 3px;
        }
        a.button:hover {
            background: #5a6268;
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
    <h2 style="margin-bottom: 20px;"><?= $edit_data ? 'Edit Kategori' : 'Tambah Kategori'; ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?= $edit_data ? 'edit' : 'add'; ?>">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id_kategori" value="<?= $edit_data['id_kategori']; ?>">
        <?php endif; ?>

        <label>Nama Kategori:</label>
        <input type="text" name="nama_kategori" required value="<?= htmlspecialchars($edit_data['nama_kategori'] ?? ''); ?>">

        <label>Durasi Garansi:</label>
        <input type="text" name="durasi_garansi" required placeholder="Contoh: 1 Tahun" value="<?= htmlspecialchars($edit_data['durasi_garansi'] ?? ''); ?>">

        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Update' : 'Tambah'; ?></button>
        <?php if ($edit_data): ?>
            <a href="kategori.php" class="btn btn-danger">Batal</a>
        <?php endif; ?>
    </form>

    <h3>Daftar Kategori</h3>
    <table>
        <tr style="background-color: #eee;">
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Durasi Garansi</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($kategori_result as $row): ?>
        <tr>
            <td><?= $row['id_kategori']; ?></td>
            <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
            <td><?= htmlspecialchars($row['durasi_garansi']); ?></td>
            <td>
                <a href="kategori.php?edit=<?= $row['id_kategori']; ?>" class="btn btn-primary">Edit</a>
                <form method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_kategori" value="<?= $row['id_kategori']; ?>">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
