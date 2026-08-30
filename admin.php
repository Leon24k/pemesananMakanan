<?php 
require_once 'auth.php';
require_admin();
require_once 'koneksi.php';

// Hitung ringkasan data untuk dashboard admin
$count_menu = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk");
$total_menu = mysqli_fetch_assoc($count_menu)['total'] ?? 0;

$count_pesanan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pemesanan");
$total_pesanan = mysqli_fetch_assoc($count_pesanan)['total'] ?? 0;
?>

<!doctype html>
<html lang="id">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">

    <title>Dashboard Admin - Restoran Web</title>
  </head>
  <body>

  <!-- Jumbotron -->
      <div class="jumbotron jumbotron-fluid text-center mb-0">
        <div class="container">
          <h1 class="display-4"><span class="font-weight-bold">Restoran Web - Panel Admin</span></h1>
          <hr>
          <p class="lead font-weight-bold">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['login_user']); ?>! <br> 
          Kelola Menu dan Data Pesanan Pelanggan</p>
        </div>
      </div>
  <!-- Akhir Jumbotron -->

  <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
        <a class="navbar-brand" href="admin.php"><strong>Resto</strong> Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link mr-3" href="admin.php"><i class="fas fa-home mr-1"></i> HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-3" href="daftar_menu.php"><i class="fas fa-utensils mr-1"></i> DAFTAR MENU</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-3" href="pesanan.php"><i class="fas fa-receipt mr-1"></i> PESANAN</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i> LOGOUT</a>
            </li>
          </ul>
        </div>
       </div> 
      </nav>
  <!-- Akhir Navbar -->

  <!-- Menu Dashboard -->    
      <div class="container my-5">
        <div class="judul text-center mb-4">
          <h3 class="font-weight-bold">Panel Kontrol Manajemen Restoran</h3>
          <p class="text-muted">Kota Bekasi &bull; Buka Jam <strong>16:00 - 23:00</strong></p>
        </div>

        <div class="row mb-4">
          <div class="col-md-6 mb-3">
            <div class="card bg-primary text-white shadow-sm">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="card-title text-uppercase font-weight-bold">Total Menu Makanan/Minuman</h6>
                  <h2 class="display-4 font-weight-bold mb-0"><?php echo $total_menu; ?></h2>
                </div>
                <i class="fas fa-utensils fa-3x opacity-50"></i>
              </div>
              <div class="card-footer bg-transparent border-0">
                <a href="daftar_menu.php" class="text-white font-weight-bold">Kelola Menu &rarr;</a>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="card bg-success text-white shadow-sm">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="card-title text-uppercase font-weight-bold">Total Transaksi Pesanan</h6>
                  <h2 class="display-4 font-weight-bold mb-0"><?php echo $total_pesanan; ?></h2>
                </div>
                <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
              </div>
              <div class="card-footer bg-transparent border-0">
                <a href="pesanan.php" class="text-white font-weight-bold">Lihat Pesanan &rarr;</a>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
              <div class="card-body text-center d-flex flex-column justify-content-center">
                <i class="fas fa-hamburger fa-4x text-primary mb-3"></i>
                <h4 class="card-title font-weight-bold">Kelola Daftar Menu</h4>
                <p class="card-text text-muted">Tambah menu baru, edit nama, harga, stok, atau hapus menu restoran.</p>
                <div class="mt-auto">
                  <a href="daftar_menu.php" class="btn btn-primary btn-block">LIHAT DAFTAR MENU</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
              <div class="card-body text-center d-flex flex-column justify-content-center">
                <i class="fas fa-clipboard-list fa-4x text-success mb-3"></i>
                <h4 class="card-title font-weight-bold">Kelola Data Pesanan</h4>
                <p class="card-text text-muted">Pantau pesanan pelanggan yang masuk, rincian produk, dan konfirmasi pembayaran.</p>
                <div class="mt-auto">
                  <a href="pesanan.php" class="btn btn-success btn-block">LIHAT DATA PESANAN</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  <!-- Akhir Menu Dashboard -->

  <!-- Awal Footer -->
      <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
          <div class="row">
            <div class="col-md-6 text-center text-md-left mb-2 mb-md-0">
              <p class="mb-0">&copy; <?php echo date('Y'); ?> Restoran Web. All Rights Reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-right">
              <span>Admin Panel Restoran Web</span>
            </div>
          </div>
        </div>
      </footer>
  <!-- Akhir Footer -->

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>