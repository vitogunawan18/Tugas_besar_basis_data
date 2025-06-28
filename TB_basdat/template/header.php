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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #2d3748;
        }

        .header-container {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 50%, #2980b9 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar {
            padding: 12px 0;
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
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 24px;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .header-text h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            color: #fff;
        }

        .header-text p {
            opacity: 0.9;
            font-size: 13px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.8);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            align-items: center;
            gap: 5px;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .nav-menu a.logout {
            background: #e74c3c;
            margin-left: 8px;
        }

        .nav-menu a.logout:hover {
            background: #c0392b;
        }

        .header-actions {
            text-align: right;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-actions p {
            margin-bottom: 1px;
            opacity: 0.9;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
        }

        .header-actions .kasir-name {
            font-weight: 700;
            font-size: 14px;
            color: #fff;
        }

        /* Main Content Styles */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            font-size: 18px;
        }

        .card-body {
            padding: 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }

        .product-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .product-name {
            font-weight: 600;
            font-size: 16px;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .product-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .product-price {
            color: #e74c3c;
            font-weight: 700;
            font-size: 14px;
        }

        .product-stock {
            color: #27ae60;
            font-weight: 600;
            font-size: 14px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .btn-detail {
            background: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            background: #2980b9;
        }

        .quantity-input {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .qty-btn.minus {
            background: #95a5a6;
            color: white;
        }

        .qty-btn.plus {
            background: #3498db;
            color: white;
        }

        .qty-btn:hover {
            transform: scale(1.1);
        }

        .qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            font-size: 14px;
        }

        .cart-section {
            text-align: center;
            color: #6c757d;
            padding: 40px 20px;
        }

        .cart-icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .cart-empty-text {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .cart-subtext {
            font-size: 14px;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 0 15px;
                flex-direction: column;
                gap: 10px;
            }
            
            .nav-right {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }
            
            .nav-menu {
                gap: 3px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .nav-menu a {
                padding: 6px 10px;
                font-size: 12px;
            }
            
            .logo {
                font-size: 18px;
            }
            
            .header-title {
                justify-content: center;
            }
            
            .header-text h1 {
                font-size: 18px;
            }
            
            .header-text p {
                font-size: 12px;
            }
            
            .header-actions {
                text-align: center;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 8px 0;
            }
            
            .header-icon {
                width: 35px;
                height: 35px;
                font-size: 18px;
            }
            
            .header-text h1 {
                font-size: 16px;
            }
            
            .header-text p {
                font-size: 11px;
            }

            .nav-menu a {
                padding: 5px 8px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

<div class="header-container">
    <nav class="navbar">
        <div class="nav-container">
            <?php if (basename($_SERVER['PHP_SELF']) == 'kasir_dashboard.php'): ?>
            <div class="header-title">
                <div class="header-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="header-text">
                    <h1>Kasir</h1>
                    <p>Sistem Point of Sale - Toko Gadget</p>
                </div>
            </div>
            <?php else: ?>
            <a href="kasir_dashboard.php" class="logo">
                <i class="fas fa-store"></i>
                Kasir
            </a>
            <?php endif; ?>
            
            <div class="nav-right">
                <ul class="nav-menu">
                    <li><a href="kasir_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="kebijakan.php"><i class="fas fa-file-contract"></i> Kebijakan</a></li>
                    <li><a href="kontak.php"><i class="fas fa-phone"></i> Kontak</a></li>
                    <li><a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
                <?php if (basename($_SERVER['PHP_SELF']) == 'kasir_dashboard.php'): ?>
                <div class="header-actions">
                    <p>Selamat datang,</p>
                    <p class="kasir-name"><?= $_SESSION['nama_kasir'] ?? 'Kasir' ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>