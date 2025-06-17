<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login_admin.php");
    exit();
}

require_once '../config/database.php';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Rekening - Admin</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            line-height: 1.6;
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
        
        .nav-menu a:hover,
        .nav-menu a.active {
            background: #555;
        }
        
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .form-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #007bff;
            color: white;
            font-weight: bold;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .action-links a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
            font-size: 14px;
        }
        
        .action-links a:hover {
            text-decoration: underline;
        }
        
        .action-links .delete {
            color: #dc3545;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
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
            <li><a href="pembayaran.php" class="active">Rekening</a></li>
            <li><a href="../logout.php" onclick="return confirm('Keluar dari admin?')">Logout</a></li>
        </ul>
    </div>
</nav>

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
                <button type="submit" name="edit" class="btn">Update</button>
                <a href="pembayaran.php" class="btn btn-secondary">Batal</a>
            <?php else: ?>
                <button type="submit" name="tambah" class="btn">Tambah</button>
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