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

<style>
    .dashboard-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-title {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-left: 4px solid #3498db;
    }

    .page-title h1 {
        margin: 0;
        color: #2c3e50;
        font-size: 24px;
    }

    .page-title p {
        margin: 5px 0 0 0;
        color: #7f8c8d;
        font-size: 14px;
    }

    .main-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .products-section {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
    }

    .section-header i {
        color: #3498db;
        font-size: 20px;
    }

    .section-title {
        color: #2c3e50;
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
        max-height: 65vh;
        overflow-y: auto;
        padding-right: 5px;
    }

    .product-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        border-color: #3498db;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.15);
    }

    .product-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .product-info {
        margin-bottom: 15px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 13px;
    }

    .info-label {
        color: #7f8c8d;
    }

    .info-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .price {
        color: #e74c3c;
        font-weight: 700;
    }

    .stock {
        color: #27ae60;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: #fff;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: #3498db;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qty-btn:hover:not(:disabled) {
        background: #2980b9;
    }

    .qty-btn:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
    }

    .qty-display {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 5px 10px;
        min-width: 40px;
        text-align: center;
        font-weight: 600;
    }

    .cart-section {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .cart-items {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 15px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        margin-bottom: 8px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .item-qty {
        color: #7f8c8d;
        font-size: 12px;
    }

    .item-price {
        font-weight: 600;
        color: #e74c3c;
        font-size: 14px;
    }

    .cart-total {
        background: #3498db;
        color: white;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        text-align: center;
    }

    .total-label {
        font-size: 14px;
        margin-bottom: 5px;
    }

    .total-amount {
        font-size: 20px;
        font-weight: 700;
    }

    .checkout-btn {
        width: 100%;
        background: #e74c3c;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .checkout-btn:hover {
        background: #c0392b;
    }

    .empty-cart {
        text-align: center;
        padding: 30px 20px;
        color: #7f8c8d;
    }

    .empty-cart i {
        font-size: 40px;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .detail-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        float: right;
    }

    .detail-btn:hover {
        background: #2980b9;
    }

    /* Popup Styles */
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
        padding: 25px;
        border-radius: 10px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .popup-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
    }

    .popup-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .popup-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #7f8c8d;
        padding: 5px;
    }

    .popup-close:hover {
        color: #2c3e50;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-content {
            grid-template-columns: 1fr;
        }
        
        .cart-section {
            position: static;
        }
        
        .product-grid {
            grid-template-columns: 1fr;
            max-height: 50vh;
        }
    }

    /* Scrollbar Styling */
    .product-grid::-webkit-scrollbar,
    .cart-items::-webkit-scrollbar {
        width: 6px;
    }

    .product-grid::-webkit-scrollbar-track,
    .cart-items::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .product-grid::-webkit-scrollbar-thumb,
    .cart-items::-webkit-scrollbar-thumb {
        background: #bdc3c7;
        border-radius: 3px;
    }

    .product-grid::-webkit-scrollbar-thumb:hover,
    .cart-items::-webkit-scrollbar-thumb:hover {
        background: #95a5a6;
    }
</style>

<div class="dashboard-container">
    <div class="main-content">
        <!-- Products Section -->
        <div class="products-section">
            <div class="section-header">
                <i class="fas fa-mobile-alt"></i>
                <h2 class="section-title">Daftar Produk</h2>
            </div>
            
            <div class="product-grid">
                <?php
                $produk = mysqli_query($conn, "SELECT p.*, k.nama_kategori, k.durasi_garansi FROM produk p 
                                              LEFT JOIN kategori_produk k ON p.id_kategori = k.id_kategori");
                while ($p = mysqli_fetch_assoc($produk)):
                    $qty = $_SESSION['cart'][$p['id_produk']] ?? 0;
                    $stok = $p['stok'];
                ?>
                    <div class="product-card">
                        <div class="product-name">
                            <?= htmlspecialchars($p['nama_produk']); ?>
                            <button class="detail-btn" onclick="showDetail(<?= $p['id_produk']; ?>)">
                                <i class="fas fa-info"></i> Detail
                            </button>
                        </div>
                        
                        <div class="product-info">
                            <div class="info-row">
                                <span class="info-label">Harga:</span>
                                <span class="info-value price">Rp <?= number_format($p['harga'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Stok:</span>
                                <span class="info-value stock"><?= $stok ?> unit</span>
                            </div>
                        </div>
                        
                        <form method="post" class="quantity-controls">
                            <input type="hidden" name="id_produk" value="<?= $p['id_produk']; ?>">
                            <input type="hidden" name="update_cart" value="1">
                            
                            <button type="submit" name="action" value="decrease" class="qty-btn" 
                                <?= $qty <= 0 ? 'disabled' : '' ?>>
                                <i class="fas fa-minus"></i>
                            </button>
                            
                            <div class="qty-display"><?= $qty ?></div>
                            
                            <button type="submit" name="action" value="increase" class="qty-btn"
                                <?= $qty >= $stok ? 'disabled' : '' ?>>
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section">
            <div class="section-header">
                <i class="fas fa-shopping-cart"></i>
                <h3 class="section-title">Keranjang Belanja</h3>
            </div>
            
            <?php
            $total = 0;
            if (!empty($_SESSION['cart'])):
            ?>
                <div class="cart-items">
                    <?php
                    foreach ($_SESSION['cart'] as $id => $qty):
                        $item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id"));
                        if (!$item) continue;
                        $subtotal = $item['harga'] * $qty;
                        $total += $subtotal;
                    ?>
                        <div class="cart-item">
                            <div class="item-details">
                                <div class="item-name"><?= htmlspecialchars($item['nama_produk']); ?></div>
                                <div class="item-qty"><?= $qty ?> × Rp <?= number_format($item['harga'], 0, ',', '.'); ?></div>
                            </div>
                            <div class="item-price">Rp <?= number_format($subtotal, 0, ',', '.'); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-total">
                    <div class="total-label">Total Pembayaran</div>
                    <div class="total-amount">Rp <?= number_format($total, 0, ',', '.'); ?></div>
                </div>
                
                <form action="checkout.php" method="post">
                    <button class="checkout-btn" type="submit">
                        <i class="fas fa-credit-card"></i> Proses Pembayaran
                    </button>
                </form>
            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Keranjang belanja kosong</p>
                    <small>Pilih produk untuk memulai transaksi</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Detail Product Popup -->
<div id="detailPopup" class="popup-overlay">
    <div class="popup-content">
        <div class="popup-header">
            <h3 class="popup-title">Detail Produk</h3>
            <button class="popup-close" onclick="closeDetail()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="detailContent">
            <p>Loading...</p>
        </div>
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
                // Fallback jika file detail_produk.php tidak ada
                const productCards = document.querySelectorAll('.product-card');
                let productData = null;

                productCards.forEach(card => {
                    const form = card.querySelector('form');
                    const hiddenInput = form.querySelector('input[name="id_produk"]');
                    if (hiddenInput && hiddenInput.value == idProduk) {
                        const title = card.querySelector('.product-name').textContent.trim();
                        const priceElement = card.querySelector('.price');
                        const stockElement = card.querySelector('.stock');
                        const harga = priceElement ? priceElement.textContent : 'Tidak tersedia';
                        const stok = stockElement ? stockElement.textContent : 'Tidak tersedia';
                        productData = { nama: title, harga: harga, stok: stok };
                    }
                });

                if (productData) {
                    document.getElementById('detailContent').innerHTML = `
                        <div style="margin-bottom: 15px;">
                            <h4 style="color: #2c3e50; margin-bottom: 15px;">${productData.nama}</h4>
                            <p><strong>Harga:</strong> ${productData.harga}</p>
                            <p><strong>Stok:</strong> ${productData.stok}</p>
                            <p><strong>Kategori:</strong> Tidak tersedia</p>
                            <p><strong>Garansi:</strong> Tidak tersedia</p>
                        </div>
                        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 6px; border-left: 4px solid #f39c12;">
                            <strong style="color: #8b5a00;">⚠️ Perhatian:</strong><br>
                            <small>Garansi Handphone inter 3 bulan, Handphone iBox/Sein 1 tahun. Tidak bisa refund!</small>
                        </div>
                    `;
                    document.getElementById('detailPopup').style.display = 'block';
                }
            });
    }

    function closeDetail() {
        document.getElementById('detailPopup').style.display = 'none';
    }

    // Close popup when clicking outside
    document.getElementById('detailPopup').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetail();
        }
    });

    // Close popup with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetail();
        }
    });
</script>

<?php require_once 'template/footer.php'; ?>