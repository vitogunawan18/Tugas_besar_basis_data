<?php
session_start();
if (isset($_SESSION['kasir'])) {
    header("Location: kasir_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Kasir - TokaGadget POS</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0; /* Warna latar belakang seperti admin */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        .login-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .admin-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }

        .admin-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Kasir</h2>
        <form action="proses_login_kasir.php" method="POST">
            <label for="id_karyawan">ID Kasir:</label>
            <input type="text" id="id_karyawan" name="id_karyawan" required>
            <button type="submit">Login</button>
        </form>
        <a class="admin-link" href="login_admin.php">Login sebagai Admin</a>
    </div>
</body>
</html>
