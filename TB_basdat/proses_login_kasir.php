<?php
session_start();
include 'config/database.php'; // pastikan file ini menghubungkan ke DB

$id_karyawan = $_POST['id_karyawan'] ?? '';
$_SESSION['kasir_nama'] = $data['nama'];

if ($id_karyawan === '') {
    header("Location: index.php?error=1");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'");
if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    $_SESSION['kasir'] = $data['id_karyawan'];
    $_SESSION['nama_kasir'] = $data['nama_karyawan'];
    header("Location: kasir_dashboard.php");
    exit;
} else {
    header("Location: index.php?error=1");
    exit;
}
?>
<?php
session_start();
include 'config/database.php'; // pastikan koneksi $conn tersedia

// Ambil input dari form
$id_karyawan = $_POST['id_karyawan'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi input kosong
if ($id_karyawan === '' || $password === '') {
    header("Location: index.php?error=1"); // bisa tambahkan pesan error
    exit;
}

// Cek apakah ID karyawan ada di database
$query = mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'");

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    
    // Verifikasi password (password di database harus di-hash)
    if (password_verify($password, $data['password'])) {
        // Set session
        $_SESSION['kasir'] = $data['id_karyawan'];
        $_SESSION['nama_kasir'] = $data['nama_karyawan'];
        header("Location: kasir_dashboard.php");
        exit;
    } else {
        // Password salah
        header("Location: index.php?error=2"); // bisa tampilkan pesan "password salah"
        exit;
    }
} else {
    // ID tidak ditemukan
    header("Location: index.php?error=1");
    exit;
}
?>
