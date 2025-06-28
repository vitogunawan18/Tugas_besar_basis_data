<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tokogadget_db";

try {
    // PDO connection for admin dashboard
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $pdo = null;
    error_log("Database connection failed: " . $e->getMessage());
}

// MySQLi connection for compatibility with other files
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    error_log("MySQLi connection failed: " . mysqli_connect_error());
}
?>