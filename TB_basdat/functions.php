<?php
// Fungsi untuk format rupiah
function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fungsi untuk format tanggal Indonesia
function format_tanggal($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Fungsi untuk sanitize input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk redirect
function redirect($url) {
    header("Location: " . $url);
    exit();
}

function check_login() {
    if (!isset($_SESSION['admin'])) {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];  // simpan halaman awal
        header("Location: login.php");
        exit;
    }
}



// Fungsi untuk generate ID transaksi
function generate_invoice_id() {
    return 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(time()), 0, 6));
}
?>