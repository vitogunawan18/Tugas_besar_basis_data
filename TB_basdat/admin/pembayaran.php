<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login_admin.php");
    exit();
}

$current_page = 'rekening'; // untuk navbar active
require_once '../config/database.php';
include 'navbar.php';

$message = '';
$edit_data = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO rekening_pembayaran (bank, nama_penerima, no_rekening) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['bank'], $_POST['nama_penerima'], $_POST['no_rekening']]);
            $message = "Data berhasil ditambahkan!";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST['edit'])) {
        try {
            $stmt = $pdo->prepare("UPDATE rekening_pembayaran SET bank = ?, nama_penerima = ?, no_rekening = ? WHERE id_rekening = ?");
            $stmt->execute([$_POST['bank'], $_POST['nama_penerima'], $_POST['no_rekening'], $_POST['id_rekening']]);
            $message = "Data berhasil diupdate!";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

// Handle delete
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM rekening_pembayaran WHERE id_rekening = ?");
        $stmt->execute([$_GET['hapus']]);
        $message = "Data berhasil dihapus!";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Handle edit mode
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM rekening_pembayaran WHERE id_rekening = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}

// Get all records
try {
    $rekening = $pdo->query("SELECT * FROM rekening_pembayaran ORDER BY id_rekening DESC")->fetchAll();
} catch (Exception $e) {
    $rekening = [];
    $message = "Error mengambil data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Rekening - Admin</title>
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

        .message {
            margin: 10px 0;
            padding: 10px;
            background: #e0f7e9;
            border-left: 5px solid #2ecc71;
            color: #2d6a4f;
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
        }

        td {
            padding: 10px;
            text-align: center;
        }

        .empty-state {
            text-align: center;
            color: #888;
        }

        .action-links a {
            text-decoration: none;
            margin: 0 5px;
            color: #0b1f40;
        }

        .action-links a.delete {
            color: #c0392b;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-container, .table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><?php echo $edit_data ? 'Edit Rekening Pembayaran' : 'Manajemen Rekening Pembayaran'; ?></h2>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_rekening" value="<?php echo htmlspecialchars($edit_data['id_rekening']); ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="bank">Nama Bank:</label>
                <input type="text" id="bank" name="bank" 
                       value="<?php echo $edit_data ? htmlspecialchars($edit_data['bank']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="nama_penerima">Nama Penerima:</label>
                <input type="text" id="nama_penerima" name="nama_penerima" 
                       value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama_penerima']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="no_rekening">No. Rekening:</label>
                <input type="text" id="no_rekening" name="no_rekening" 
                       value="<?php echo $edit_data ? htmlspecialchars($edit_data['no_rekening']) : ''; ?>" 
                       required>
            </div>
            
            <?php if ($edit_data): ?>
                <button type="submit" name="edit" class="btn btn-primary">Update</button>
                <a href="pembayaran.php" class="btn btn-danger">Batal</a>
            <?php else: ?>
                <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Bank</th>
                    <th>Nama Penerima</th>
                    <th>No. Rekening</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rekening)): ?>
                    <tr>
                        <td colspan="4" class="empty-state">Belum ada data rekening pembayaran</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rekening as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['bank']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_penerima']); ?></td>
                        <td><?php echo htmlspecialchars($row['no_rekening']); ?></td>
                        <td class="action-links">
                            <a href="pembayaran.php?edit=<?php echo urlencode($row['id_rekening']); ?>">Edit</a>
                            <a href="pembayaran.php?hapus=<?php echo urlencode($row['id_rekening']); ?>" 
                               class="delete"
                               onclick="return confirm('Yakin ingin menghapus rekening <?php echo htmlspecialchars($row['bank']); ?> - <?php echo htmlspecialchars($row['nama_penerima']); ?>?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
