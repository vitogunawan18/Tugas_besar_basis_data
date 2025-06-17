<?php
session_start();
if (!isset($_SESSION['faktur'])) {
    header("Location: kasir_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Berhasil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }

        .box {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            background-color: #e0ffe0;
            border: 2px solid #28a745;
            border-radius: 10px;
        }

        h2 {
            color: #28a745;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        a.button {
            display: inline-block;
            text-decoration: none;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        a.button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Transaksi Berhasil!</h2>
    <p>Terima kasih atas pembelian Anda.</p>

    <div class="button-group">
        <a href="faktur.php" class="button">Lihat Faktur</a>
        <a href="kasir_dashboard.php" class="button">← Kembali ke Dashboard Kasir</a>
    </div>
</div>

</body>
</html>
