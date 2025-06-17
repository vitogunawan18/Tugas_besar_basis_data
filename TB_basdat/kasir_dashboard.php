<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config/database.php';
require_once 'template/header.php';

if (!isset($_SESSION['kasir'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['update_cart'])) {
    $id_produk = $_POST['id_produk'];
    $action = $_POST['action'];

    $stok_query = mysqli_query($conn, "SELECT stok FROM produk WHERE id_produk = $id_produk");
    $stok_data = mysqli_fetch_assoc($stok_query);
    $stok = $stok_data['stok'];

    $qty_saat_ini = $_SESSION['cart'][$id_produk] ?? 0;

    if ($action == 'increase' && $qty_saat_ini < $stok) {
        $_SESSION['cart'][$id_produk] = $qty_saat_ini + 1;
    } elseif ($action == 'decrease' && isset($_SESSION['cart'][$id_produk])) {
        $_SESSION['cart'][$id_produk]--;
        if ($_SESSION['cart'][$id_produk] <= 0) unset($_SESSION['cart'][$id_produk]);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>POS Kasir</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #e0e0e0; }
        .main-wrapper { display: flex; height: 100vh; }
        .product-section {
            flex: 3;
            background-color: #d0e8f2;
            padding: 20px;
            overflow-y: auto;
        }
        .checkout-section {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-left: 3px solid #999;
        }
        .product-card {
            background: #ffffff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            box-shadow: 0 0 3px rgba(0,0,0,0.1);
        }
        .product-title { font-weight: bold; }
        .quantity-form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .quantity-form button {
            width: 30px;
            height: 30px;
            border: none;
            background-color: #28a745;
            color: white;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
        }
        .quantity-form button[disabled] {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .checkout-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .btn-checkout {
            width: 100%;
            padding: 12px;
            background-color: #ff9800;
            border: none;
            font-weight: bold;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-checkout:hover {
            background-color: #f57c00;
        }
        .btn-detail {
            padding: 5px 10px;
            background-color: #007cba;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 10px;
        }
        .btn-detail:hover {
            background-color: #005a8b;
        }
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .popup-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .popup-close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }
        .popup-close:hover { color: #333; }
        .popup-price {
            color: red;
            font-weight: bold;
            font-size: 24px;
        }
        .popup-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <!-- Produk -->
    <div class="product-section">
        <h2>Daftar Produk</h2>
        <?php
        $produk = mysqli_query($conn, "SELECT p.*, k.nama_kategori, k.durasi_garansi FROM produk p 
                                      LEFT JOIN kategori_produk k ON p.id_kategori = k.id_kategori");
        while ($p = mysqli_fetch_assoc($produk)):
            $qty = $_SESSION['cart'][$p['id_produk']] ?? 0;
            $stok = $p['stok'];
        ?>
            <div class="product-card">
                <div class="product-title">
                    <?= $p['nama_produk']; ?>
                    <button class="btn-detail" onclick="showDetail(<?= $p['id_produk']; ?>)">Detail</button>
                </div>
                <div>Harga: Rp <?= number_format($p['harga'], 0, ',', '.'); ?></div>
                <div>Stok: <?= $stok ?></div>
                <form method="post" class="quantity-form">
                    <input type="hidden" name="id_produk" value="<?= $p['id_produk']; ?>">
                    <button type="submit" name="action" value="decrease">-</button>
                    <span><?= $qty ?></span>
                    <button type="submit" name="action" value="increase"
                        <?= $qty >= $stok ? 'disabled' : '' ?>>+</button>
                    <input type="hidden" name="update_cart" value="1">
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Checkout -->
    <div class="checkout-section">
        <h3>Checkout</h3>
        <?php
        $total = 0;
        if (!empty($_SESSION['cart'])):
            foreach ($_SESSION['cart'] as $id => $qty):
                $item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id"));
                if (!$item) continue;
                $subtotal = $item['harga'] * $qty;
                $total += $subtotal;
        ?>
            <div class="checkout-item">
                <span><?= $item['nama_produk']; ?> (x<?= $qty ?>)</span>
                <span>Rp <?= number_format($subtotal, 0, ',', '.'); ?></span>
            </div>
        <?php endforeach; ?>
            <div class="total">Total: Rp <?= number_format($total, 0, ',', '.'); ?></div>
            <form action="checkout.php" method="post" style="margin-top: 10px;">
                <button class="btn-checkout" type="submit">Bayar Sekarang</button>
            </form>
        <?php else: ?>
            <p>Keranjang kosong.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Popup Detail Produk -->
<div id="detailPopup" class="popup-overlay">
    <div class="popup-content">
        <button class="popup-close" onclick="closeDetail()">&times;</button>
        <div id="detailContent"><p>Loading...</p></div>
    </div>
</div>

<script>
function showDetail(idProduk) {
    fetch('detail_produk.php?id=' + idProduk)
        .then(response => response.text())
        .then(data => {
            document.getElementById('detailContent').innerHTML = data;
            document.getElementById('detailPopup').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            showDetailFallback(idProduk);
        });
}

function showDetailFallback(idProduk) {
    const productCards = document.querySelectorAll('.product-card');
    let productData = null;

    productCards.forEach(card => {
        const form = card.querySelector('form');
        const hiddenInput = form.querySelector('input[name="id_produk"]');
        if (hiddenInput && hiddenInput.value == idProduk) {
            const title = card.querySelector('.product-title').textContent.replace('Detail', '').trim();
            const harga = card.querySelectorAll('div')[1].textContent;
            const stok = card.querySelectorAll('div')[2].textContent;
            productData = { nama: title, harga: harga, stok: stok };
        }
    });

    if (productData) {
        document.getElementById('detailContent').innerHTML = `
            <h1>${productData.nama}</h1>
            <p><strong>Kategori:</strong> -</p>
            <p><strong>Garansi:</strong> -</p>
            <p class="popup-price">${productData.harga}</p>
            <div class="popup-warning">
                <strong>Perhatian:</strong><br>
                Untuk Garansi Handphone inter selama 3 Bulan dan Garansi untuk Handphone iBox/Sein selama 1 Tahun, tidak bisa refund!
            </div>
        `;
        document.getElementById('detailPopup').style.display = 'block';
    }
}

function closeDetail() {
    document.getElementById('detailPopup').style.display = 'none';
}

document.getElementById('detailPopup').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
});
</script>

</body>
</html>
<?php require_once 'template/footer.php'; ?>
