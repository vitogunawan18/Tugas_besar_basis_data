<?php
session_start();
include 'config/database.php';
require_once 'template/header.php';

if (!isset($_SESSION['kasir']) || empty($_SESSION['cart'])) {
    header("Location: kasir_dashboard.php");
    exit;
}

// Ambil ID kasir dari session
$id_kasir = $_SESSION['kasir'];

// Ambil nama kasir dari tabel karyawan
$queryKasir = mysqli_query($conn, "SELECT nama_karyawan FROM karyawan WHERE id_karyawan = $id_kasir");
$dataKasir = mysqli_fetch_assoc($queryKasir);
$nama_kasir = $dataKasir['nama_karyawan'] ?? 'Kasir';

// Ambil data rekening pembayaran dari database
$queryRekening = mysqli_query($conn, "SELECT * FROM rekening_pembayaran ORDER BY bank ASC");
$rekening_list = [];
while ($rekening = mysqli_fetch_assoc($queryRekening)) {
    $rekening_list[] = $rekening;
}

// Ambil data produk dari cart
$cart_items = [];
$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id");
    $produk = mysqli_fetch_assoc($result);
    if ($produk) {
        $produk['qty'] = $qty;
        $produk['subtotal'] = $produk['harga'] * $qty;
        $cart_items[] = $produk;
        $total += $produk['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - POS Kasir</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            padding: 0px 0;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
        }

        .header h2 {
            font-size: 2.2em;
            font-weight: 600;
            position: relative;
            z-index: 1;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 1.1em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px;
        }

        .order-summary {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
            border: 1px solid #e3f2fd;
            border-left: 5px solid #4facfe;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.1);
        }

        .order-summary h3 {
            color: #2c5282;
            font-size: 1.3em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .order-summary h3 i {
            color: #4facfe;
        }

        .order-summary ul {
            list-style: none;
            padding: 0;
        }

        .order-summary li {
            background: white;
            margin-bottom: 12px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 3px solid #4facfe;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .item-qty {
            color: #718096;
            font-size: 0.9em;
        }

        .item-price {
            font-weight: 600;
            color: #4facfe;
            font-size: 1.1em;
        }

        .total-section {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-top: 15px;
        }

        .total-section .total {
            font-size: 1.8em;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 1.1em;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            transition: all 0.3s ease;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .payment-methods {
            display: grid;
            gap: 15px;
            margin-top: 15px;
        }

        .payment-option {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .payment-option:hover {
            border-color: #4facfe;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.1);
            transform: translateY(-2px);
        }

        .payment-option.selected {
            border-color: #4facfe;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
        }

        .payment-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .payment-option .payment-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 8px;
        }

        .payment-option .payment-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: white;
        }

        .payment-option .payment-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 1.1em;
        }

        .payment-option .payment-details {
            color: #718096;
            font-size: 0.9em;
            margin-left: 55px;
        }

        .bank-bca .payment-icon { background: linear-gradient(135deg, #1e40af, #3b82f6); }
        .bank-bni .payment-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .bank-qris .payment-icon { background: linear-gradient(135deg, #10b981, #059669); }
        .bank-tunai .payment-icon { background: linear-gradient(135deg, #6b7280, #4b5563); }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }

        .btn {
            flex: 1;
            padding: 18px 25px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
            color: #4a5568;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .content {
                padding: 20px;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .order-summary li {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-shopping-cart"></i> Form Pembelian</h2>
        <div class="subtitle">Lengkapi data pembelian Anda</div>
    </div>

    <div class="content">
        <div class="order-summary">
            <h3><i class="fas fa-receipt"></i> Ringkasan Pesanan</h3>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li>
                        <div class="item-info">
                            <div class="item-name"><?= htmlspecialchars($item['nama_produk']); ?></div>
                            <div class="item-qty">Qty: <?= $item['qty'] ?></div>
                        </div>
                        <div class="item-price">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="total-section">
                <div class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></div>
            </div>
        </div>

        <form action="proses_checkout.php" method="post">
            <div class="form-group">
                <label for="nama"><i class="fas fa-user"></i> Nama Pelanggan:</label>
                <input type="text" name="nama" id="nama" placeholder="Masukkan nama pelanggan" required>
            </div>

            <div class="form-group">
                <label for="telepon"><i class="fas fa-phone"></i> No. Telepon:</label>
                <input type="text" name="telepon" id="telepon" placeholder="Masukkan nomor telepon" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-credit-card"></i> Metode Pembayaran:</label>
                <div class="payment-methods">
                    <?php if (!empty($rekening_list)): ?>
                        <?php foreach ($rekening_list as $rekening): ?>
                            <div class="payment-option bank-<?= strtolower($rekening['bank']); ?>">
                                <input type="radio" name="metode_pembayaran" value="<?= htmlspecialchars($rekening['bank']); ?>" data-rekening="<?= $rekening['id_rekening']; ?>" required>
                                <div class="payment-header">
                                    <div class="payment-icon">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div class="payment-name">Transfer <?= htmlspecialchars($rekening['bank']); ?></div>
                                </div>
                                <div class="payment-details">
                                    <?= htmlspecialchars($rekening['nama_penerima']); ?> - <?= htmlspecialchars($rekening['no_rekening']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <div class="payment-option bank-qris">
                        <input type="radio" name="metode_pembayaran" value="QRIS" data-rekening="0">
                        <div class="payment-header">
                            <div class="payment-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div class="payment-name">QRIS</div>
                        </div>
                        <div class="payment-details">Pembayaran melalui QR Code</div>
                    </div>
                    
                    <div class="payment-option bank-tunai">
                        <input type="radio" name="metode_pembayaran" value="Tunai" data-rekening="0">
                        <div class="payment-header">
                            <div class="payment-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="payment-name">Tunai</div>
                        </div>
                        <div class="payment-details">Pembayaran secara tunai</div>
                    </div>
                </div>
            </div>

            <!-- Hidden input untuk menyimpan ID rekening yang dipilih -->
            <input type="hidden" name="id_rekening" id="id_rekening" value="">

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pembelian
                </button>
                <a href="kasir_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const paymentRadios = document.querySelectorAll('input[name="metode_pembayaran"]');
    const idRekeningInput = document.getElementById('id_rekening');
    
    // Handle payment option selection
    paymentOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio && !radio.checked) {
                radio.checked = true;
                
                // Remove selected class from all options
                paymentOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Add selected class to clicked option
                this.classList.add('selected');
                
                // Update hidden input
                const rekeningId = radio.getAttribute('data-rekening');
                idRekeningInput.value = rekeningId;
            }
        });
    });
    
    // Handle radio button change
    paymentRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.checked) {
                // Remove selected class from all options
                paymentOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Add selected class to parent option
                this.closest('.payment-option').classList.add('selected');
                
                // Update hidden input
                const rekeningId = this.getAttribute('data-rekening');
                idRekeningInput.value = rekeningId;
            }
        });
    });
});
</script>

</body>
</html>