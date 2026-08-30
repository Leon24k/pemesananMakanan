<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

$id_menu = (int)($_GET['id_menu'] ?? 0);

if ($id_menu > 0) {
    // 1. Ambil nama file gambar untuk dihapus
    $stmt_img = mysqli_prepare($koneksi, "SELECT gambar FROM produk WHERE id_menu = ?");
    mysqli_stmt_bind_param($stmt_img, "i", $id_menu);
    mysqli_stmt_execute($stmt_img);
    $res_img = mysqli_stmt_get_result($stmt_img);
    if ($row = mysqli_fetch_assoc($res_img)) {
        if (!empty($row['gambar']) && file_exists(__DIR__ . '/upload/' . $row['gambar'])) {
            @unlink(__DIR__ . '/upload/' . $row['gambar']);
        }
    }
    mysqli_stmt_close($stmt_img);

    // 2. Hapus data produk dari database
    $stmt = mysqli_prepare($koneksi, "DELETE FROM produk WHERE id_menu = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_menu);
    $hapus = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($hapus) {
        echo "<script>alert('Menu berhasil dihapus!'); window.location.href = 'daftar_menu.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus menu.'); window.location.href = 'daftar_menu.php';</script>";
        exit;
    }
}

header('Location: daftar_menu.php');
exit;
?>