<?php 
require_once 'auth.php';
require_login();
require_once 'koneksi.php';

$id_menu = (int)($_GET['id_menu'] ?? 0);

if ($id_menu <= 0) {
    header("Location: menu_pembeli.php");
    exit;
}

// 1. Cek stok produk di database
$stmt = mysqli_prepare($koneksi, "SELECT nama_menu, stok FROM produk WHERE id_menu = ?");
mysqli_stmt_bind_param($stmt, "i", $id_menu);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$produk) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location.href='menu_pembeli.php';</script>";
    exit;
}

$stok_tersedia = (int)$produk['stok'];
$jumlah_sekarang = (int)($_SESSION['pesanan'][$id_menu] ?? 0);

if ($stok_tersedia <= 0) {
    echo "<script>alert('Maaf, stok " . htmlspecialchars(addslashes($produk['nama_menu'])) . " sedang habis!'); window.location.href='menu_pembeli.php';</script>";
    exit;
}

if ($jumlah_sekarang + 1 > $stok_tersedia) {
    echo "<script>alert('Maaf, jumlah pesanan Anda untuk " . htmlspecialchars(addslashes($produk['nama_menu'])) . " sudah mencapai batas stok yang tersedia (" . $stok_tersedia . ")!'); window.location.href='pesanan_pembeli.php';</script>";
    exit;
}

// Tambahkan ke session keranjang
$_SESSION['pesanan'][$id_menu] = $jumlah_sekarang + 1;

echo "<script>alert('Menu " . htmlspecialchars(addslashes($produk['nama_menu'])) . " berhasil ditambahkan ke pesanan!'); window.location.href='pesanan_pembeli.php';</script>";
exit;
?>