<?php
require_once "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username      = trim($_POST["username"] ?? '');
    $password      = trim($_POST["password"] ?? '');
    $nama_lengkap  = trim($_POST["nama_lengkap"] ?? '');
    $jenis_kelamin = trim($_POST["jenis_kelamin"] ?? 'Laki-Laki');
    $tanggal_lahir = trim($_POST["tanggal_lahir"] ?? '');
    $alamat        = trim($_POST["alamat"] ?? '');
    $hp            = trim($_POST["hp"] ?? '');
    $status        = 'user'; // Keamanan: Selalu tetapkan status sebagai 'user'

    if (empty($username) || empty($password) || empty($nama_lengkap) || empty($tanggal_lahir)) {
        echo "<script>
                alert('Semua field wajib diisi!');
                window.history.back();
              </script>";
        exit;
    }

    // 1. Cek apakah username sudah ada
    $check_stmt = mysqli_prepare($koneksi, "SELECT id_user FROM user WHERE username = ?");
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    $check_res = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_res) > 0) {
        mysqli_stmt_close($check_stmt);
        echo "<script>
                alert('Username sudah terdaftar! Silahkan gunakan username lain.');
                window.history.back();
              </script>";
        exit;
    }
    mysqli_stmt_close($check_stmt);

    // 2. Hash password menggunakan bcrypt
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // 3. Masukkan data ke database dengan Prepared Statement
    $stmt = mysqli_prepare($koneksi, "INSERT INTO user (username, password, nama_lengkap, jenis_kelamin, tanggal_lahir, alamat, hp, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssssss", $username, $password_hashed, $nama_lengkap, $jenis_kelamin, $tanggal_lahir, $alamat, $hp, $status);
    $hasil = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($hasil) {
        echo "<script>
                alert('Registrasi Berhasil! Silahkan Login.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>
                alert('Registrasi Gagal: Terjadi kesalahan pada server.');
                window.location.href = 'register.php';
              </script>";
    }
} else {
    header("Location: register.php");
    exit;
}
?>