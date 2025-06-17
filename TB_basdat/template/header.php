<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page_title = $page_title ?? "Dashboard Kasir";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }

        .navbar {
            background: #2c3e50;
            padding: 10px 0;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #fff;
            font-size: 22px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            align-items: center;
        }

        .nav-menu li {
            margin-left: 20px;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background 0.3s;
            font-size: 14px;
        }

        .nav-menu a:hover {
            background: #34495e;
        }

        .logout {
            background: #e74c3c;
        }

        .logout:hover {
            background: #c0392b;
        }

        .dashboard {
            background: #3498db;
        }



        .user-greeting {
            color: #ecf0f1;
            font-size: 14px;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="kasir_dashboard.php" class="logo">POS Kasir - TokaGadget</a>
        <ul class="nav-menu">
            <li><a href="kasir_dashboard.php">Dashboard</a></li>
            <li><a href="kebijakan.php">Kebijakan</a></li>
            <li><a href="kontak.php">Kontak</a></li>
            <?php if (isset($_SESSION['nama_kasir'])): ?>
                <li><span class="user-greeting">Welcome, <?= htmlspecialchars($_SESSION['nama_kasir']) ?></span></li>
            <?php endif; ?>
            <li><a href="logout.php" class="logout">Logout</a></li>
        </ul>
    </div>
</nav>
