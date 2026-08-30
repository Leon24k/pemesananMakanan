<?php 
require_once 'auth.php';
require_login();
require_once 'koneksi.php';

$query = mysqli_query($koneksi, 'SELECT * FROM produk ORDER BY jenis_menu ASC, id_menu DESC');
$menu_list = mysqli_fetch_all($query, MYSQLI_ASSOC);
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

    <title>Daftar Menu - Restoran Web</title>
  </head>
  <body>
  <!-- Jumbotron -->
      <div class="jumbotron jumbotron-fluid text-center mb-0">
        <div class="container">
          <h1 class="display-4"><span class="font-weight-bold">Restoran Web</span></h1>
          <hr>
          <p class="lead font-weight-bold">Silahkan Pesan Menu Sesuai Keinginan Anda &bull; Enjoy Your Meal</p>
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
            <li class="nav-item">
              <a class="nav-link mr-3" href="index.php"><i class="fas fa-home mr-1"></i> HOME</a>
            </li>
            <li class="nav-item active">
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

  <!-- Menu Pembeli -->
      <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="font-weight-bold mb-0">Menu Pilihan Hari Ini</h3>
          <a href="pesanan_pembeli.php" class="btn btn-outline-success">
            <i class="fas fa-shopping-cart mr-1"></i> Lihat Keranjang (<?php echo !empty($_SESSION['pesanan']) ? array_sum($_SESSION['pesanan']) : 0; ?>)
          </a>
        </div>

        <div class="row">
          <?php if (empty($menu_list)): ?>
            <div class="col-12 text-center py-5">
              <p class="text-muted">Maaf, belum ada menu yang tersedia saat ini.</p>
            </div>
          <?php else: ?>
            <?php foreach($menu_list as $menu) : ?>
              <div class="col-md-3 mt-4">
                <div class="card shadow-sm h-100">
                  <?php 
                    $gambar_path = "upload/" . $menu['gambar'];
                    if (!empty($menu['gambar']) && file_exists($gambar_path)) {
                        $src = htmlspecialchars($gambar_path);
                    } else {
                        $src = "images/menu1.jpg";
                    }
                  ?>
                  <img src="<?php echo $src; ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="<?php echo htmlspecialchars($menu['nama_menu']); ?>">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold"><?php echo htmlspecialchars($menu['nama_menu']); ?></h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="badge badge-info"><?php echo htmlspecialchars($menu['jenis_menu']); ?></span>
                      <?php if ($menu['stok'] > 0): ?>
                        <span class="badge badge-success">Tersedia: <?php echo $menu['stok']; ?></span>
                      <?php else: ?>
                        <span class="badge badge-danger">Habis</span>
                      <?php endif; ?>
                    </div>
                    <label class="card-text harga text-primary font-weight-bold">
                      Rp. <?php echo number_format($menu['harga'], 0, ',', '.'); ?>
                    </label>
                    <div class="mt-auto pt-2">
                      <?php if ($menu['stok'] > 0): ?>
                        <a href="beli.php?id_menu=<?php echo urlencode($menu['id_menu']); ?>" class="btn btn-success btn-sm btn-block">
                          <i class="fas fa-cart-plus mr-1"></i> PESAN
                        </a>
                      <?php else: ?>
                        <button class="btn btn-secondary btn-sm btn-block" disabled>STOK HABIS</button>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div> 
      </div>
  <!-- Akhir Menu Pembeli -->

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