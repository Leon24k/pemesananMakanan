<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_edit'])) {
    $id_menu     = (int)($_POST['id_menu'] ?? 0);
    $nama_menu   = trim($_POST['nama_menu'] ?? '');
    $jenis_menu  = trim($_POST['jenis_menu'] ?? 'Makanan');
    $stok        = (int)($_POST['stok'] ?? 0);
    $harga       = (int)($_POST['harga'] ?? 0);
    $gambar_lama = trim($_POST['gambar_lama'] ?? '');

    if ($id_menu <= 0 || empty($nama_menu) || $harga <= 0) {
        echo "<script>alert('Data menu tidak valid!'); window.history.back();</script>";
        exit;
    }

    $gambar_final = $gambar_lama;

    // Cek apakah ada file foto baru yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_ext)) {
            echo "<script>alert('Format gambar tidak didukung! Hanya JPG, JPEG, PNG, dan WEBP yang diizinkan.'); window.history.back();</script>";
            exit;
        }

        if ($file_size > 3 * 1024 * 1024) {
            echo "<script>alert('Ukuran file gambar terlalu besar (Maks 3MB)!'); window.history.back();</script>";
            exit;
        }

        if (@getimagesize($file_tmp) === false) {
            echo "<script>alert('File yang diunggah bukan file gambar yang valid!'); window.history.back();</script>";
            exit;
        }

        $gambar_final = uniqid('menu_', true) . '.' . $file_ext;
        $target_path  = __DIR__ . '/upload/' . $gambar_final;

        if (move_uploaded_file($file_tmp, $target_path)) {
            // Hapus gambar lama jika ada dan file fisiknya ada
            if (!empty($gambar_lama) && file_exists(__DIR__ . '/upload/' . $gambar_lama)) {
                @unlink(__DIR__ . '/upload/' . $gambar_lama);
            }
        } else {
            echo "<script>alert('Gagal mengunggah foto baru!'); window.history.back();</script>";
            exit;
        }
    }

    // Eksekusi UPDATE dengan Prepared Statement
    $stmt = mysqli_prepare($koneksi, "UPDATE produk SET nama_menu = ?, jenis_menu = ?, stok = ?, harga = ?, gambar = ? WHERE id_menu = ?");
    mysqli_stmt_bind_param($stmt, "ssiisi", $nama_menu, $jenis_menu, $stok, $harga, $gambar_final, $id_menu);
    $edit = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($edit) {
        echo "<script>alert('Menu berhasil diperbarui!'); window.location.href = 'daftar_menu.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui menu database.'); window.history.back();</script>";
        exit;
    }
} else {
    header('Location: daftar_menu.php');
    exit;
}
?>