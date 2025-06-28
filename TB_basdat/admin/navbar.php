<?php if (!isset($current_page)) $current_page = ''; ?>
<style>
    .navbar {
        background: #001F3F;
        padding: 15px 0;
    }
    .nav-container {
        max-width: 1200px;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }
    .logo {
        color: white;
        font-size: 26px;
        font-weight: bold;
        text-decoration: none;
    }
    .nav-menu {
        display: flex;
        list-style: none;
    }
    .nav-menu li {
        margin-left: 25px;
    }
    .nav-menu a {
        color: white;
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 5px;
        transition: background 0.3s;
    }
    .nav-menu a:hover,
    .nav-menu a.active {
        background: #003366;
    }
</style>

<nav class="navbar">
    <div class="nav-container">
        <a href="../index.php" class="logo">TOKOGADGET</a>
        <ul class="nav-menu">
            <li><a href="index.php" class="<?= ($current_page == 'dashboard') ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="produk.php" class="<?= ($current_page == 'produk') ? 'active' : '' ?>">Produk</a></li>
            <li><a href="kategori.php" class="<?= ($current_page == 'kategori') ? 'active' : '' ?>">Kategori</a></li>
            <li><a href="transaksi.php" class="<?= ($current_page == 'transaksi') ? 'active' : '' ?>">Transaksi</a></li>
            <li><a href="karyawan.php" class="<?= ($current_page == 'karyawan') ? 'active' : '' ?>">Karyawan</a></li>
            <li><a href="pembayaran.php" class="<?= ($current_page == 'rekening') ? 'active' : '' ?>">Rekening</a></li>
            <li><a href="laporan.php" class="<?= ($current_page == 'laporan') ? 'active' : '' ?>">Laporan</a></li>
            <li><a href="../logout.php" onclick="return confirm('Keluar dari admin?')">Logout</a></li>
        </ul>
    </div>
</nav>
