<?php  
require_once 'auth.php';
require_login();
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

    <title>Beranda - Restoran Web</title>
  </head>
  <body>

  <!-- Jumbotron -->
      <div class="jumbotron jumbotron-fluid text-center mb-0">
        <div class="container">
          <h1 class="display-4"><span class="font-weight-bold">Restoran Web</span></h1>
          <hr>
          <p class="lead font-weight-bold">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['login_user']); ?>! <br> 
          Silahkan Pesan Menu Sesuai Keinginan Anda &bull; Enjoy Your Meal</p>
        </div>
      </div>
  <!-- Akhir Jumbotron -->

  <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
        <a class="navbar-brand" href="index.php"><strong>Resto</strong> Web</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link mr-3" href="index.php"><i class="fas fa-home mr-1"></i> HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-3" href="menu_pembeli.php"><i class="fas fa-utensils mr-1"></i> DAFTAR MENU</a>
            </li>
            <li class="nav-item">
              <a class="nav-link mr-3" href="pesanan_pembeli.php"><i class="fas fa-shopping-cart mr-1"></i> PESANAN ANDA 
                <?php if (!empty($_SESSION['pesanan'])): ?>
                  <span class="badge badge-pill badge-danger"><?php echo array_sum($_SESSION['pesanan']); ?></span>
                <?php endif; ?>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i> LOGOUT</a>
            </li>
          </ul>
        </div>
       </div> 
      </nav>
  <!-- Akhir Navbar -->

  <!-- Menu Utama -->    
      <div class="container my-5">
        <div class="judul text-center mb-5">
          <h3 class="font-weight-bold">Restoran Web Kuliner Lezat</h3>
          <p class="text-muted">Kota Bekasi, Gang Macan &bull; Buka Jam <strong>16:00 - 23:00</strong></p>
        </div>

        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
              <img src="upload/mieayam.jpg" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Daftar Menu" onerror="this.src='images/menu2.jpg'">
              <div class="card-body text-center d-flex flex-column justify-content-between">
                <div>
                  <h4 class="card-title font-weight-bold">Lihat Menu Favorit</h4>
                  <p class="card-text text-muted">Jelajahi berbagai pilihan menu makanan dan minuman segar dengan harga terjangkau.</p>
                </div>
                <div class="mt-3">
                  <a href="menu_pembeli.php" class="btn btn-primary btn-block btn-lg"><i class="fas fa-utensils mr-1"></i> LIHAT DAFTAR MENU</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
              <img src="upload/ayambakar.jpg" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Pesanan Anda" onerror="this.src='images/menu3.jpg'">
              <div class="card-body text-center d-flex flex-column justify-content-between">
                <div>
                  <h4 class="card-title font-weight-bold">Keranjang & Pesanan Anda</h4>
                  <p class="card-text text-muted">Lihat rincian menu yang telah Anda pilih dan lanjutkan ke konfirmasi pesanan.</p>
                </div>
                <div class="mt-3">
                  <a href="pesanan_pembeli.php" class="btn btn-success btn-block btn-lg"><i class="fas fa-shopping-cart mr-1"></i> LIHAT PESANAN SAYA</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  <!-- Akhir Menu Utama -->

  <!-- Awal Footer -->
      <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
          <p class="mb-0">&copy; <?php echo date('Y'); ?> Restoran Web. All Rights Reserved.</p>
        </div>
      </footer>
  <!-- Akhir Footer -->

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>