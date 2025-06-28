<?php
session_start();
require_once '../config/database.php';
require_once '../functions.php';

check_login(); // Pastikan admin sudah login

$current_page = 'kategori'; // tandai menu aktif di navbar
include 'navbar.php';

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

        .inline { display: inline-block; }
    </style>
</head>
<body>

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
