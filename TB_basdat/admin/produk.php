<?php
session_start();
require_once '../config/database.php';
require_once '../functions.php';

check_login();

// Handle form: tambah/edit/hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $nama = $_POST['nama_produk'];
    $id_kat = $_POST['id_kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // DEBUG: Tampilkan data POST
    // Hapus ini jika sudah tidak dibutuhkan
    // echo "<pre>"; print_r($_POST); echo "</pre>";

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO produk (nama_produk, id_kategori, harga, stok) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $id_kat, $harga, $stok]);
        header("Location: produk.php");
        exit;

    } elseif ($action === 'edit') {
        $id = $_POST['id_produk'];
        $stmt = $pdo->prepare("UPDATE produk SET nama_produk = ?, id_kategori = ?, harga = ?, stok = ? WHERE id_produk = ?");
        $success = $stmt->execute([$nama, $id_kat, $harga, $stok, $id]);

        if (!$success) {
            echo "❌ Gagal update produk.<br>";
            echo "🔎 Error info: <pre>";
            print_r($stmt->errorInfo());
            echo "</pre>";
            exit;
        }

        header("Location: produk.php");
        exit;

    } elseif ($action === 'delete') {
        $id = $_POST['id_produk'];
        $stmt = $pdo->prepare("DELETE FROM produk WHERE id_produk = ?");
        $stmt->execute([$id]);
        header("Location: produk.php");
        exit;
    }
}

// Data edit jika ada
$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}

// Ambil semua produk & kategori
$produk_result = $pdo->query("
    SELECT p.*, k.nama_kategori 
    FROM produk p 
    LEFT JOIN kategori_produk k ON p.id_kategori = k.id_kategori
")->fetchAll();

$kategori_result = $pdo->query("SELECT * FROM kategori_produk")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Produk</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
        .navbar { background: #333; padding: 10px 0; }
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
        .nav-menu { display: flex; list-style: none; }
        .nav-menu li { margin-left: 30px; }
        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav-menu a:hover { background: #555; }
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
            width: 75px;
        }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-danger:hover { background-color: #c82333; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        table, th, td { border: 1px solid #aaa; }
        th, td { padding: 10px; text-align: left; }
        .inline { display: inline-block; }
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
    <h2 style="margin-bottom: 20px;"><?= $edit_data ? 'Edit Produk' : 'Tambah Produk'; ?></h2>
    <form method="POST">
        <input type="hidden" name="action" value="<?= $edit_data ? 'edit' : 'add'; ?>">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id_produk" value="<?= $edit_data['id_produk']; ?>">
        <?php endif; ?>

        <label>Nama Produk:</label>
        <input type="text" name="nama_produk" required value="<?= htmlspecialchars($edit_data['nama_produk'] ?? '') ?>">

        <label>Kategori:</label>
        <select name="id_kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategori_result as $kat): ?>
                <option value="<?= $kat['id_kategori'] ?>" <?= ($edit_data && $edit_data['id_kategori'] == $kat['id_kategori']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Harga:</label>
        <input type="number" name="harga" step="0.01" required value="<?= htmlspecialchars($edit_data['harga'] ?? '') ?>">

        <label>Stok:</label>
        <input type="number" name="stok" required value="<?= htmlspecialchars($edit_data['stok'] ?? 0) ?>">

        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Update' : 'Tambah' ?></button>
        <?php if ($edit_data): ?>
            <a href="produk.php" class="btn btn-danger">Batal</a>
        <?php endif; ?>
    </form>

    <h3>Daftar Produk</h3>
    <table>
        <tr style="background-color: #eee;">
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($produk_result as $row): ?>
        <tr>
            <td><?= $row['id_produk']; ?></td>
            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
            <td>Rp <?= number_format($row['harga'], 2, ',', '.') ?></td>
            <td><?= $row['stok']; ?></td>
            <td>
                <a href="produk.php?edit=<?= $row['id_produk']; ?>" class="btn btn-primary">Edit</a>
                <form method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_produk" value="<?= $row['id_produk']; ?>">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
