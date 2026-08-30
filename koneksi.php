<?php 

$host = "localhost";
$user = "root";
$pass = "";
$db   = "dbpemesanan"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");

// Auto-check & update skema database jika kolom belum ada
// 1. Pastikan kolom password di tabel user muat hash bcrypt (VARCHAR 255)
$cek_user_col = mysqli_query($koneksi, "SHOW COLUMNS FROM `user` LIKE 'password'");
if ($cek_user_col && mysqli_num_rows($cek_user_col) > 0) {
    $col_info = mysqli_fetch_assoc($cek_user_col);
    if (strpos(strtolower($col_info['Type']), 'varchar(255)') === false && strpos(strtolower($col_info['Type']), 'text') === false) {
        @mysqli_query($koneksi, "ALTER TABLE `user` MODIFY `password` VARCHAR(255) NOT NULL");
    }
}

// 2. Pastikan kolom id_user dan status_pembayaran ada di tabel pemesanan
$cek_pemesanan_user = mysqli_query($koneksi, "SHOW COLUMNS FROM `pemesanan` LIKE 'id_user'");
if ($cek_pemesanan_user && mysqli_num_rows($cek_pemesanan_user) === 0) {
    @mysqli_query($koneksi, "ALTER TABLE `pemesanan` ADD COLUMN `id_user` INT(50) NULL AFTER `id_pemesanan`");
}

$cek_pemesanan_status = mysqli_query($koneksi, "SHOW COLUMNS FROM `pemesanan` LIKE 'status_pembayaran'");
if ($cek_pemesanan_status && mysqli_num_rows($cek_pemesanan_status) === 0) {
    @mysqli_query($koneksi, "ALTER TABLE `pemesanan` ADD COLUMN `status_pembayaran` ENUM('Belum Bayar','Lunas') NOT NULL DEFAULT 'Belum Bayar' AFTER `total_belanja`");
}
?>