<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // 1. Hapus rincian produk pesanan terlebih dahulu
    $stmt1 = mysqli_prepare($koneksi, "DELETE FROM pemesanan_produk WHERE id_pemesanan = ?");
    mysqli_stmt_bind_param($stmt1, "i", $id);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    // 2. Hapus data utama pesanan
    $stmt2 = mysqli_prepare($koneksi, "DELETE FROM pemesanan WHERE id_pemesanan = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id);
    $hapus = mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    if ($hapus) {
        echo "<script>alert('Data pesanan berhasil dihapus!'); window.location.href = 'pesanan.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data pesanan.'); window.location.href = 'pesanan.php';</script>";
        exit;
    }
}

header('Location: pesanan.php');
exit;
?>