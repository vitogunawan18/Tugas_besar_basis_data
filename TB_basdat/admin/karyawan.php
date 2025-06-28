<?php
session_start();
require_once '../config/database.php';
require_once '../functions.php';

check_login(); // pastikan admin login

$current_page = 'karyawan'; // tandai navbar aktif
include 'navbar.php';

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
